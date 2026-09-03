@extends('layouts.master')

@section('title', 'Panier — Sellerie Epycuir')

@section('content')

<section class="bg-stable-900 py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-safety/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            </div>
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-white">Votre panier</h1>
            </div>
        </div>
    </div>
</section>

<section class="py-10 lg:py-14 bg-stable-50 min-h-[50vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">{{ session('error') }}</div>
        @endif

        @if(session('info'))
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-700 text-sm">{{ session('info') }}</div>
        @endif

        @if($cart->isEmpty())
        <div class="text-center py-16">
            <div class="w-20 h-20 mx-auto bg-stable-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-stable-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-stable-900 mb-2">Votre panier est vide</h2>
            <p class="text-stable-500 mb-6">Vous n'avez pas encore ajouté d'article à votre panier.</p>
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all">Parcourir la boutique</a>
        </div>
        @else
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-stable-500">{{ $count }} {{ Str::plural('article', $count) }}</p>
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        <button class="text-xs text-stable-400 hover:text-cta transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Tout effacer
                        </button>
                    </form>
                </div>
                @foreach($cart as $id => $item)
                <div class="bg-white rounded-xl border border-stable-100 p-4 flex gap-4 items-center">
                    <div class="w-20 h-20 bg-stable-50 rounded-xl overflow-hidden flex-shrink-0">
                        <img src="{{ $item['image'] ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=200&q=80' }}" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('product.show', $item['slug']) }}" class="font-semibold text-stable-900 hover:text-safety transition-colors text-sm line-clamp-1">{{ $item['name'] }}</a>
                        <p class="text-lg font-bold text-stable-900 mt-1">&euro;{{ number_format($item['price'], 2) }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="flex items-center border border-stable-200 rounded-lg overflow-hidden">
                                <button class="qty-btn px-2 py-1 text-stable-500 hover:bg-stable-50 transition-colors text-sm" data-id="{{ $id }}" data-action="decr">-</button>
                                <span class="px-3 py-1 text-sm font-medium border-x border-stable-200 qty-display" data-id="{{ $id }}">{{ $item['quantity'] }}</span>
                                <button class="qty-btn px-2 py-1 text-stable-500 hover:bg-stable-50 transition-colors text-sm" data-id="{{ $id }}" data-action="incr">+</button>
                            </div>
                            <form method="POST" action="{{ route('cart.remove', $id) }}" class="inline">
                                @csrf
                                <button class="text-xs text-stable-400 hover:text-cta transition-colors">Retirer</button>
                            </form>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-bold text-stable-900">&euro;{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 lg:mt-0">
                <div class="bg-white rounded-xl border border-stable-100 p-6 sticky top-28">
                    <h3 class="font-bold text-stable-900 mb-4">Résumé de la commande</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-stable-500">
                            <span>Sous-total</span>
                            <span>&euro;{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-stable-500">
                            <span>Livraison</span>
                            <span>@if($freeShipping)<span class="text-safety font-semibold">OFFERTE</span>@else&euro;{{ number_format($shipping, 2) }}@endif</span>
                        </div>
                        @if($freeShipping && $isFirstOrder)
                        <div class="bg-stable-50 border border-stable-200 rounded-lg p-2.5 flex items-center gap-2">
                            <svg class="w-4 h-4 text-safety shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span class="text-xs text-safety-dark font-medium">Première commande — livraison offerte appliquée</span>
                        </div>
                        @elseif(!$freeShipping)
                        <p class="text-xs text-stable-400">Livraison offerte dès 80 &euro;</p>
                        @endif
                        <div class="border-t border-stable-100 pt-3 flex justify-between font-bold text-stable-900">
                            <span>Total</span>
                            <span class="text-xl">&euro;{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                    @if(\App\Support\OrderMode::isWhatsapp())
                    @php $waCartUrl = \App\Support\OrderMode::waLink(\App\Support\OrderMode::cartMessage($cart, $total)); @endphp
                    @if($waCartUrl)
                    <a href="{{ $waCartUrl }}" target="_blank" rel="noopener" class="mt-6 w-full px-6 py-3.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-green-500/25">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Commander via WhatsApp
                    </a>
                    <p class="text-xs text-stable-400 text-center mt-3">Paiement en ligne non requis — votre commande sera confirmée sur WhatsApp.</p>
                    @else
                    <a href="{{ route('contact') }}" class="mt-6 w-full px-6 py-3.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-safety/25">
                        Contactez-nous pour commander
                    </a>
                    @endif
                    @else
                    <a href="{{ route('checkout') }}" class="mt-6 w-full px-6 py-3.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-safety/25">
                        Procéder au paiement
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    @endif
                    <a href="{{ route('shop') }}" class="mt-3 w-full px-6 py-3 bg-stable-50 hover:bg-stable-100 text-stable-700 font-medium rounded-xl transition-all flex items-center justify-center gap-2 text-sm">
                        Continuer mes achats
                    </a>
                    <div class="mt-4 pt-4 border-t border-stable-100">
                        <div class="flex items-center justify-center gap-1.5 text-xs text-stable-400">
                            <svg class="w-3.5 h-3.5 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            @if(\App\Support\OrderMode::isWhatsapp())Commandes confirmées via WhatsApp@else Paiement sécurisé via Stripe @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const display = document.querySelector(`.qty-display[data-id="${id}"]`);
        let qty = parseInt(display.textContent);

        if (this.dataset.action === 'decr' && qty <= 1) {
            fetch('{{ route("cart.remove", ["id" => "__ID__"]) }}'.replace('__ID__', id), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            }).then(() => location.reload());
            return;
        }

        qty = this.dataset.action === 'incr' ? qty + 1 : qty - 1;
        display.textContent = qty;
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ product_id: parseInt(id), quantity: qty })
        }).then(r => r.json()).then(d => {
            document.querySelectorAll('.cart-count').forEach(el => el.textContent = d.count);
            location.reload();
        });
    });
});
</script>
@endpush

@endsection
