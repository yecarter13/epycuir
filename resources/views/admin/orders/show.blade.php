@extends('admin.layouts.master')

@section('title', 'Commande ' . $order->order_number)

@section('content')
<div class="max-w-4xl">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 text-sm text-stable-500 hover:text-stable-700 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux commandes
    </a>

    <div class="bg-white rounded-xl border border-stable-100 p-6 lg:p-8 space-y-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-stable-900">Commande #{{ $order->order_number }}</h1>
                <p class="text-sm text-stable-400 mt-1">Passée le {{ $order->created_at->format('d/m/Y \à H:i') }}</p>
            </div>
            @php
                $statusClass = match($order->status) {
                    'paid' => 'bg-stable-100 text-safety-dark border-stable-200',
                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                    'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                    default => 'bg-stable-100 text-stable-600',
                };
                $statusLabels = [
                    'paid' => 'Payée',
                    'pending' => 'En attente',
                    'processing' => 'En cours',
                    'shipped' => 'Expédiée',
                    'delivered' => 'Livrée',
                    'cancelled' => 'Annulée',
                    'refunded' => 'Remboursée',
                ];
            @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-full border {{ $statusClass }}">
                @switch($order->status)
                    @case('paid')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @break
                    @case('pending')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @break
                    @case('cancelled')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @break
                @endswitch
                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
            </span>
            @if($order->status === 'pending')
            <form method="POST" action="{{ route('admin.orders.markPaid', $order->id) }}" onsubmit="return confirm('Marquer cette commande comme payée ?')">
                @csrf
                <button type="submit" class="px-4 py-2 bg-safety hover:bg-safety-dark text-white text-sm font-medium rounded-lg transition-colors">Marquer comme payée</button>
            </form>
            @endif
        </div>

        {{-- Items --}}
        <div>
            <h3 class="font-bold text-stable-900 mb-4">Articles de la commande</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between p-4 bg-stable-50 rounded-xl">
                    <div>
                        <p class="font-medium text-stable-900">{{ $item->product_name }}</p>
                        <p class="text-sm text-stable-400">&euro;{{ number_format($item->price, 2) }} &times; {{ $item->quantity }}</p>
                    </div>
                    <p class="font-semibold text-stable-900">&euro;{{ number_format($item->total, 2) }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Totals --}}
        <div class="border-t border-stable-100 pt-4 space-y-2 text-sm max-w-xs ml-auto">
            <div class="flex justify-between text-stable-500">
                <span>Sous-total</span>
                <span>&euro;{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between text-stable-500">
                <span>Livraison</span>
                <span>{{ $order->shipping > 0 ? '&euro;' . number_format($order->shipping, 2) : 'OFFERTE' }}</span>
            </div>
            <div class="flex justify-between font-bold text-stable-900 text-base pt-2 border-t border-stable-100">
                <span>Total</span>
                <span>&euro;{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        {{-- Customer Details --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-t border-stable-100 pt-6">
            <div>
                <h4 class="font-semibold text-stable-900 mb-2 text-sm uppercase tracking-wider">Adresse de livraison</h4>
                <div class="text-sm text-stable-500 space-y-1">
                    <p>{{ $order->customer_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}</p>
                    <p>{{ $order->shipping_postcode }}</p>
                </div>
            </div>
            <div>
                <h4 class="font-semibold text-stable-900 mb-2 text-sm uppercase tracking-wider">Contact</h4>
                <div class="text-sm text-stable-500 space-y-1">
                    <p>{{ $order->customer_email }}</p>
                    <p>{{ $order->customer_phone ?? 'Aucun téléphone' }}</p>
                </div>
                @if($order->notes)
                <div class="mt-4">
                    <h4 class="font-semibold text-stable-900 mb-2 text-sm uppercase tracking-wider">Notes</h4>
                    <p class="text-sm text-stable-500">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Stripe Info --}}
        @if($order->stripe_session_id)
        <div class="border-t border-stable-100 pt-6">
            <h4 class="font-semibold text-stable-900 mb-2 text-sm uppercase tracking-wider">Paiement</h4>
            <div class="text-sm text-stable-500 space-y-1">
                <p>Session: <span class="font-mono text-xs">{{ $order->stripe_session_id }}</span></p>
                @if($order->stripe_payment_intent)
                <p>Intention de paiement : <span class="font-mono text-xs">{{ $order->stripe_payment_intent }}</span></p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
