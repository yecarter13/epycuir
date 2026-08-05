<?php

namespace App\Http\Controllers;

use App\Mail\AdminMail;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use App\Support\OrderMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function cart(CartService $cart)
    {
        return view('checkout.cart', [
            'cart' => $cart->get(),
            'subtotal' => $cart->subtotal(),
            'shipping' => $cart->shippingCost(),
            'total' => $cart->total(),
            'freeShipping' => $cart->hasFreeShipping(),
            'isFirstOrder' => $cart->isFirstOrder(),
            'count' => $cart->count(),
        ]);
    }

    public function checkout(CartService $cart)
    {
        if (OrderMode::isWhatsapp()) {
            return redirect()->route('cart')->with('info', 'Le paiement en ligne est désactivé. Passez votre commande via WhatsApp ou contactez-nous.');
        }

        if ($cart->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Votre panier est vide.');
        }

        return view('checkout.checkout', [
            'cart' => $cart->get(),
            'subtotal' => $cart->subtotal(),
            'shipping' => $cart->shippingCost(),
            'total' => $cart->total(),
            'freeShipping' => $cart->hasFreeShipping(),
            'isFirstOrder' => $cart->isFirstOrder(),
        ]);
    }

    public function createSession(Request $request, CartService $cart)
    {
        if (OrderMode::isWhatsapp()) {
            return response()->json(['error' => 'Le paiement en ligne est désactivé. Merci de passer commande via WhatsApp.'], 400);
        }

        if ($cart->isEmpty()) {
            return response()->json(['error' => 'Panier vide'], 400);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $orderNumber = 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8));

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            if (config('services.stripe.api_version')) {
                Stripe::setApiVersion(config('services.stripe.api_version'));
            }

            $lineItems = [];
            foreach ($cart->get() as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item['name'],
                            'images' => $item['image'] ? [$item['image']] : [],
                        ],
                        'unit_amount' => (int) round($item['price'] * 100),
                    ],
                    'quantity' => $item['quantity'],
                ];
            }

            $shippingCost = $cart->shippingCost();
            if ($shippingCost > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => ['name' => 'Livraison (France Standard)'],
                        'unit_amount' => (int) round($shippingCost * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            $baseUrl = $request->getSchemeAndHttpHost();
            $session = Session::create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'payment_method_types' => ['card', 'klarna', 'revolut_pay', 'bancontact', 'eps'],
                'success_url' => $baseUrl . route('checkout.success', ['order' => $orderNumber], false) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $baseUrl . route('checkout.cancel', [], false),
                'customer_email' => $request->email,
                'metadata' => [
                    'order_number' => $orderNumber,
                ],
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Illuminate\Support\Facades\Log::error('Stripe session creation failed', [
                'message' => $e->getMessage(),
                'type' => get_class($e),
                'code' => $e->getHttpStatus(),
                'stripe_code' => $e->getError()->code ?? null,
                'stripe_param' => $e->getError()->param ?? null,
            ]);
            return response()->json(['error' => 'Erreur du prestataire de paiement : ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Checkout session error', [
                'message' => $e->getMessage(),
                'type' => get_class($e),
            ]);
            return response()->json(['error' => 'Une erreur inattendue est survenue : ' . $e->getMessage()], 500);
        }

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => auth()->id(),
            'stripe_session_id' => $session->id,
            'status' => 'pending',
            'subtotal' => $cart->subtotal(),
            'shipping' => $cart->shippingCost(),
            'total' => $cart->total(),
            'customer_name' => $request->name,
            'customer_email' => $request->email,
            'customer_phone' => $request->phone,
            'shipping_address' => $request->address,
            'shipping_city' => $request->city,
            'shipping_postcode' => $request->postcode,
            'notes' => $request->notes,
        ]);

        session(['first_order_shipping_free' => false]);

        $validItems = $cart->get()->filter(function ($item) {
            return Product::where('id', $item['id'])->exists();
        });

        if ($validItems->isEmpty()) {
            $cart->clear();
            return response()->json(['error' => 'Votre panier contient des produits qui n\'existent plus. Merci de réessayer.'], 400);
        }

        foreach ($validItems as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['price'] * $item['quantity'],
            ]);

            Product::where('id', $item['id'])->decrement('stock_quantity', $item['quantity']);
        }

        $cart->clear();

        return response()->json(['url' => $session->url]);
    }

    public function success(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if ($order->status === 'pending') {
            if ($request->filled('session_id') && $request->session_id === $order->stripe_session_id) {
                try {
                    Stripe::setApiKey(config('services.stripe.secret'));
                    if (config('services.stripe.api_version')) {
                        Stripe::setApiVersion(config('services.stripe.api_version'));
                    }
                    $session = Session::retrieve($request->session_id);
                    if (in_array($session->payment_status, ['paid', 'no_payment_required'])) {
                        $order->update([
                            'status' => 'paid',
                            'stripe_payment_intent' => $session->payment_intent,
                        ]);
                        Mail::to($order->customer_email)->send(new OrderConfirmation($order));
                        $this->notifyAdmin($order);
                    }
                } catch (\Exception $e) {
                    $order->update(['status' => 'paid']);
                    Mail::to($order->customer_email)->send(new OrderConfirmation($order));
                    $this->notifyAdmin($order);
                }
            }
        }

        return view('checkout.success', compact('order'));
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }

    public function buyNow(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $qty = max(1, (int) $request->quantity);

        if (OrderMode::isWhatsapp()) {
            $link = OrderMode::waLink(OrderMode::productMessage($product, $qty));
            return $link
                ? redirect()->away($link)
                : redirect()->route('product.show', $product->slug)->with('error', 'La commande WhatsApp n\'est pas configurée. Veuillez nous contacter.');
        }

        $cart = app(CartService::class);
        $cart->clear();
        $cart->add($product->id, $qty);

        return redirect()->route('checkout');
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = app(CartService::class);

        if ($cart->get()->has($request->product_id)) {
            $cart->update($request->product_id, $request->quantity);
        } else {
            $cart->add($request->product_id, max(1, $request->quantity));
        }

        return response()->json([
            'count' => $cart->count(),
            'message' => 'Panier mis à jour',
        ]);
    }

    public function removeFromCart(Request $request, $id)
    {
        $cart = app(CartService::class);
        $cart->remove((int) $id);
        return redirect()->route('cart');
    }

    public function clearCart()
    {
        $cart = app(CartService::class);
        $cart->clear();
        return redirect()->route('cart');
    }

    protected function notifyAdmin(Order $order): void
    {
        try {
            $items = $order->items->map(fn($i) => "- {$i->product_name} x{$i->quantity} - €" . number_format($i->total,2))->implode("\n");
            $body = "<p><strong>Nouvelle commande reçue !</strong></p>
<p><strong>Commande :</strong> #{$order->order_number}<br>
<strong>Client :</strong> {$order->customer_name}<br>
<strong>Email :</strong> {$order->customer_email}<br>
<strong>Téléphone :</strong> {$order->customer_phone}<br>
<strong>Total :</strong> €" . number_format($order->total,2) . "<br>
<strong>Statut :</strong> Payée</p>
<p><strong>Articles :</strong><br>" . nl2br($items) . "</p>
<p><a href=\"" . route('admin.orders.show', $order->id) . "\" style=\"color:#b58d3d;\">Voir la commande dans l'admin</a></p>";

            Mail::to('info@scelle.com')->send(new AdminMail('Nouvelle commande — #' . $order->order_number, $body));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Admin notification failed', ['error' => $e->getMessage()]);
        }
    }
}
