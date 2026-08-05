<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    protected string $sessionKey = 'cart';

    public function get(): Collection
    {
        return collect(session($this->sessionKey, []));
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $cart = $this->get();
        $product = Product::findOrFail($productId);

        if ($cart->has($productId)) {
            $item = $cart->get($productId);
            $item['quantity'] += $quantity;
            $cart->put($productId, $item);
        } else {
            $cart->put($productId, [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'slug' => $product->slug,
                'quantity' => $quantity,
                'stock' => $product->stock_quantity,
            ]);
        }

        session([$this->sessionKey => $cart->toArray()]);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->get();
        if ($cart->has($productId)) {
            $item = $cart->get($productId);
            $item['quantity'] = max(1, min($quantity, $item['stock']));
            $cart->put($productId, $item);
        }
        session([$this->sessionKey => $cart->toArray()]);
    }

    public function remove(int $productId): void
    {
        $cart = $this->get();
        $cart->forget($productId);
        session([$this->sessionKey => $cart->toArray()]);
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    public function count(): int
    {
        return $this->get()->sum('quantity');
    }

    public function subtotal(): float
    {
        return $this->get()->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function isEmpty(): bool
    {
        return $this->get()->isEmpty();
    }

    public function isFirstOrder(): bool
    {
        if (auth()->check()) {
            return \App\Models\Order::where('user_id', auth()->id())->count() === 0;
        }
        return session('first_order_shipping_free', true);
    }

    public function hasFreeShipping(): bool
    {
        if ($this->isFirstOrder()) {
            return true;
        }
        return $this->subtotal() >= 80;
    }

    public function shippingCost(): float
    {
        return $this->hasFreeShipping() ? 0 : 8.90;
    }

    public function total(): float
    {
        return $this->subtotal() + $this->shippingCost();
    }
}
