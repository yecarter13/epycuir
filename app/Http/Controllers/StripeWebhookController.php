<?php

namespace App\Http\Controllers;

use App\Mail\AdminMail;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        Stripe::setApiVersion(config('services.stripe.api_version'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook.secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException) {
            return response('Invalid signature', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCompletedSession($session);
                break;

            case 'checkout.session.expired':
                $session = $event->data->object;
                $this->handleExpiredSession($session);
                break;
        }

        return response('Webhook received', 200);
    }

    protected function handleCompletedSession($session)
    {
        $order = Order::where('stripe_session_id', $session->id)->first();
        if ($order && $order->status === 'pending') {
            $order->update([
                'status' => 'paid',
                'stripe_payment_intent' => $session->payment_intent,
            ]);
            try {
                Mail::to($order->customer_email)->send(new OrderConfirmation($order));
                $this->notifyAdmin($order);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send confirmation email', [
                    'order' => $order->order_number,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function notifyAdmin($order): void
    {
        try {
            $items = $order->items->map(fn($i) => "- {$i->product_name} x{$i->quantity} - £" . number_format($i->total,2))->implode("\n");
            $body = "<p><strong>Nouvelle commande reçue !</strong></p>
<p><strong>Order:</strong> #{$order->order_number}<br>
<strong>Client :</strong> {$order->customer_name}<br>
<strong>Email :</strong> {$order->customer_email}<br>
<strong>Téléphone :</strong> {$order->customer_phone}<br>
<strong>Total :</strong> £" . number_format($order->total,2) . "<br>
<strong>Statut :</strong> Paid</p>
<p><strong>Articles :</strong><br>" . nl2br($items) . "</p>";
            Mail::to('info@scelle.com')->send(new AdminMail('Nouvelle commande — #' . $order->order_number, $body));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Admin notification failed', ['error' => $e->getMessage()]);
        }
    }

    protected function handleExpiredSession($session)
    {
        $order = Order::where('stripe_session_id', $session->id)->first();
        if ($order && $order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
        }
    }
}
