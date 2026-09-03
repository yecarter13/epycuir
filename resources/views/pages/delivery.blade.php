@extends('layouts.master')

@section('title', 'Livraison — Sellerie Super Confort')

@section('content')

<section class="bg-stable-900 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 bg-safety/90 text-white text-xs font-semibold uppercase tracking-widest rounded-full mb-4">Service client</span>
        <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4">Livraison</h1>
        <p class="text-lg text-stable-300 max-w-2xl mx-auto">Livraison rapide et fiable dans toute la France métropolitaine</p>
    </div>
</section>

<section class="py-12 lg:py-16 bg-stable-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-stable-100 p-8 lg:p-10 space-y-8">
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Livraison en France</h2>
                <p class="text-stable-600 leading-relaxed">Nous proposons une livraison rapide et fiable dans toute la France métropolitaine. La plupart des commandes passées avant 14 h sont expédiées sous 24 à 48 heures ouvrées, puis livrées sous 48 à 72 heures ouvrées.</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Tarifs de livraison</h2>
                <div class="bg-stable-50 border border-stable-200 rounded-lg p-4 mb-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-safety shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-safety-dark">Livraison offerte dès 80 &euro;</p>
                        <p class="text-xs text-safety">La livraison standard est offerte pour toute commande de 80 &euro; ou plus. Aucun code requis — la réduction est appliquée automatiquement au moment du paiement.</p>
                    </div>
                </div>
                <div class="space-y-3 text-stable-600">
                    <div class="flex justify-between items-center py-2 border-b border-stable-100"><span class="font-medium">Traitement et expédition des commandes</span><span class="font-semibold text-stable-900">24 à 48 h ouvrées</span></div>
                    <div class="flex justify-between items-center py-2 border-b border-stable-100"><span class="font-medium">Livraison standard en France métropolitaine (48 à 72 h ouvrées)</span><span class="font-semibold text-stable-900">8,90 &euro;</span></div>
                    <div class="flex justify-between items-center py-2"><span class="font-medium text-safety-dark">Livraison offerte</span><span class="font-semibold text-safety-dark">Commandes dès 80 &euro;</span></div>
                </div>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Zones de livraison</h2>
                <p class="text-stable-600 leading-relaxed">Nous livrons dans toute la France métropolitaine. Certains articles volumineux ou lourds peuvent nécessiter une livraison sur palette, auquel cas notre équipe vous contactera afin de convenir d'une date de livraison adaptée.</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Livraison internationale</h2>
                <p class="text-stable-600 leading-relaxed">Actuellement, nous livrons uniquement en France métropolitaine. Pour toute demande de livraison à l'étranger, contactez notre équipe, qui pourra vous assister au cas par cas.</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-stable-900 mb-3">Article endommagé</h2>
                <p class="text-stable-600 leading-relaxed">Si vous recevez un article endommagé pendant le transport, contactez-nous sous 14 jours à l'adresse info@scelle.com ou au +33 7 56 96 57 89. Nous organisons le remplacement de l'article ou son remboursement, sans frais supplémentaires.</p>
            </div>
        </div>
    </div>
</section>

@endsection
