@extends('layouts.master')

@section('title', 'Commande — Sellerie Super Confort')

@section('content')

<section class="bg-stable-900 py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-safety/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-white">Paiement sécurisé</h1>
                <p class="text-stable-300 text-sm mt-1">Vos informations sont chiffrées et sécurisées</p>
            </div>
        </div>
    </div>
</section>

{{-- Trust Banner --}}
<div class="bg-safety py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-center gap-6 text-white text-sm flex-wrap">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span class="font-medium">Chiffrement SSL 256 bits</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span class="font-medium">Conforme PCI</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span class="font-medium">Payez avec Visa, Mastercard, Amex</span>
            </div>
        </div>
    </div>
</div>

<section class="py-10 lg:py-14 bg-stable-50 min-h-[50vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-5 lg:gap-8">

            {{-- Billing Form --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-stable-100 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-stable-900 mb-6">Coordonnées de livraison</h2>

                    <form id="checkout-form" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-stable-900 mb-1.5">Nom complet <span class="text-cta">*</span></label>
                                <input type="text" id="name" name="name" required class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stable-900 mb-1.5">Email <span class="text-cta">*</span></label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stable-900 mb-1.5">Téléphone</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stable-900 mb-1.5">Adresse <span class="text-cta">*</span></label>
                            <textarea id="address" name="address" rows="2" required class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-stable-900 mb-1.5">Ville <span class="text-cta">*</span></label>
                                <input type="text" id="city" name="city" required class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-stable-900 mb-1.5">Code postal <span class="text-cta">*</span></label>
                                <input type="text" id="postcode" name="postcode" required class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stable-900 mb-1.5">Notes de commande (optionnel)</label>
                            <textarea id="notes" name="notes" rows="2" placeholder="Instructions particulières, notes de livraison, etc." class="w-full px-4 py-2.5 border border-stable-200 rounded-xl text-sm focus:outline-none focus:border-safety transition-all"></textarea>
                        </div>

                        <button type="submit" id="submit-btn" class="w-full px-6 py-3.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-safety/25 flex items-center justify-center gap-2 text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Payer &euro;{{ number_format($total, 2) }}
                        </button>

                        <p class="text-xs text-stable-400 text-center mt-4">Votre paiement est traité en toute sécurité via Stripe. Nous ne stockons pas vos données bancaires.</p>
                    </form>
                </div>
            </div>

            {{-- Résumé de la commande --}}
            <div class="lg:col-span-2 mt-8 lg:mt-0">
                <div class="bg-white rounded-2xl border border-stable-100 p-6 lg:p-8 sticky top-28">
                    <h3 class="font-bold text-stable-900 mb-4">Résumé de la commande</h3>

                    <div class="space-y-3 mb-6">
                        @foreach($cart as $id => $item)
                        <div class="flex items-center gap-3 pb-3 border-b border-stable-50">
                            <div class="w-14 h-14 bg-stable-50 rounded-xl overflow-hidden flex-shrink-0">
                                <img src="{{ $item['image'] ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=200&q=80' }}" alt="" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-stable-900 line-clamp-1">{{ $item['name'] }}</p>
                                <p class="text-xs text-stable-400">Qté : {{ $item['quantity'] }}</p>
                            </div>
                            <p class="text-sm font-semibold text-stable-900">&euro;{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-stable-500">
                            <span>Sous-total</span>
                            <span>&euro;{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-stable-500">
                            <span>Livraison</span>
                            <span>@if($freeShipping)<span class="text-green-600 font-semibold">OFFERTE</span>@else&euro;{{ number_format($shipping, 2) }}@endif</span>
                        </div>
                        @if($freeShipping && $isFirstOrder)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-2.5 flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span class="text-xs text-green-700 font-medium">Première commande — livraison offerte appliquée</span>
                        </div>
                        @elseif(!$freeShipping)
                        <div class="bg-stable-50 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs text-stable-500">Livraison offerte dès 80 &euro;</span>
                                <span class="text-xs font-medium text-stable-700">Encore {{ number_format(max(0, 80 - $subtotal), 2) }} &euro; pour la livraison offerte</span>
                            </div>
                            <div class="w-full h-1.5 bg-stable-200 rounded-full overflow-hidden">
                                <div class="h-full bg-safety rounded-full transition-all" style="width: {{ min(100, ($subtotal / 80) * 100) }}%"></div>
                            </div>
                        </div>
                        @endif
                        <div class="border-t border-stable-100 pt-3 flex justify-between font-bold text-stable-900">
                            <span>Total <span class="font-normal text-stable-400 text-xs">(livraison incluse)</span></span>
                            <span class="text-xl">&euro;{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-stable-100">
                        <p class="text-xs text-stable-400 flex items-center gap-2 mb-2">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Votre paiement est sécurisé par Stripe
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@push('scripts')
<script>
    const form = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('submit-btn');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'text-sm text-cta mt-3 text-center';
    form.appendChild(errorDiv);

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Traitement...';
        errorDiv.textContent = '';

        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            postcode: document.getElementById('postcode').value,
            notes: document.getElementById('notes').value,
        };

        try {
            const res = await fetch('{{ route("checkout.session") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(formData),
            });

            const data = await res.json();
            if (data.url) {
                window.location.href = data.url;
            } else {
                errorDiv.textContent = data.error || 'Une erreur est survenue. Veuillez réessayer.';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Payer &euro;{{ number_format($total, 2) }}';
            }
        } catch (e) {
            errorDiv.textContent = 'Erreur réseau. Vérifiez votre connexion et réessayez.';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Payer &euro;{{ number_format($total, 2) }}';
        }
    });
</script>
@endpush

@endsection
