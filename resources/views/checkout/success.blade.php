@extends('layouts.master')

@section('title', 'Commande confirmée — Sellerie Epycuir')

@section('content')

<section class="py-20 bg-stable-900 text-center">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-20 h-20 bg-safety/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-safety-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-3xl lg:text-4xl font-bold text-white mb-3">Commande confirmée !</h1>
        <p class="text-stable-300 text-lg mb-2">Merci pour votre commande, <strong class="text-white">{{ $order->customer_name }}</strong>.</p>
        <p class="text-stable-400">Votre numéro de commande est le <span class="text-safety font-bold">{{ $order->order_number }}</span></p>
    </div>
</section>

<section class="py-14 bg-stable-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-stable-100 p-6 lg:p-8">
            <h2 class="text-xl font-bold text-stable-900 mb-6">Détails de la commande</h2>

            <div class="space-y-4 mb-8">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between pb-3 border-b border-stable-50">
                    <div>
                        <p class="font-medium text-stable-900">{{ $item->product_name }}</p>
                        <p class="text-sm text-stable-400">Qté : {{ $item->quantity }} &times; &euro;{{ number_format($item->price, 2) }}</p>
                    </div>
                    <p class="font-semibold text-stable-900">&euro;{{ number_format($item->total, 2) }}</p>
                </div>
                @endforeach
            </div>

            <div class="space-y-2 text-sm border-t border-stable-100 pt-4">
                <div class="flex justify-between text-stable-500">
                    <span>Sous-total</span>
                    <span>&euro;{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-stable-500">
                    <span>Livraison</span>
                    <span>{{ $order->shipping > 0 ? '&euro;' . number_format($order->shipping, 2) : 'OFFERTE' }}</span>
                </div>
                <div class="flex justify-between font-bold text-stable-900 text-base pt-2 border-t border-stable-100">
                    <span>Total payé</span>
                    <span>&euro;{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-stable-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <h4 class="font-semibold text-stable-900 mb-1">Adresse de livraison</h4>
                    <p class="text-stable-500">{{ $order->customer_name }}<br>{{ $order->shipping_address }}<br>{{ $order->shipping_city }}<br>{{ $order->shipping_postcode }}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-stable-900 mb-1">Contact</h4>
                    <p class="text-stable-500">{{ $order->customer_email }}<br>{{ $order->customer_phone ?? 'Aucun téléphone' }}</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all">
                Continue mes achats
            </a>
        </div>
    </div>
</section>

@endsection
