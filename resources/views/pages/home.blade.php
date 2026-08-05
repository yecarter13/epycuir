@extends('layouts.master')

@section('title', 'Sellerie Super Confort — Équipement équestre premium livré partout en France')
@section('og_title', 'Sellerie Super Confort — Équipement équestre premium livré partout en France')
@section('og_description', 'Sellerie Super Confort, votre sellerie en ligne : selles, brides, tapis, licols et équipement équestre. Livraison rapide en France.')
@section('og_url', url('/'))
@section('og_type', 'website')

@section('content')

{{-- Hero Carousel --}}
<section class="relative bg-stable-900 overflow-hidden">
    <div id="hero-carousel" class="relative h-[70vh] min-h-[500px] lg:min-h-[600px]">
        @foreach($slides as $index => $slide)
        <div class="hero-slide absolute inset-0 transition-opacity duration-700 ease-in-out" data-active="{{ $index === 0 ? 'true' : 'false' }}" style="opacity: {{ $index === 0 ? 1 : 0 }}; z-index: {{ $index === 0 ? 10 : 0 }};">
            <div class="absolute inset-0">
                <img src="{{ $slide->image }}" alt="" class="w-full h-full object-cover" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                <div class="absolute inset-0 bg-gradient-to-r from-stable-900/95 via-stable-900/70 to-stable-900/30"></div>
            </div>
            <div class="relative z-10 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                <div class="max-w-2xl">
                    <span class="inline-block px-3 py-1 bg-safety/90 text-white text-xs font-semibold uppercase tracking-widest rounded-full mb-4">{{ $slide->tag }}</span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4">{{ $slide->title }}</h1>
                    <p class="text-lg sm:text-xl text-stable-200 mb-8 max-w-xl leading-relaxed">{{ $slide->subtitle }}</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('shop') }}" class="inline-flex items-center px-6 py-3.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-safety/25 hover:shadow-safety/40 hover:-translate-y-0.5">
                            {{ $slide->cta_primary }}
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ match($slide->cta_secondary) { 'En savoir plus' => route('about'), 'Livraison' => route('delivery'), default => route('shop') } }}" class="inline-flex items-center px-6 py-3.5 border-2 border-white/20 hover:border-white/40 text-white font-semibold rounded-xl transition-all duration-300 hover:bg-white/5">
                            {{ $slide->cta_secondary }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
            @foreach($slides as $index => $slide)
            <button class="hero-dot w-2.5 h-2.5 rounded-full transition-all duration-300" data-index="{{ $index }}" style="background: {{ $index === 0 ? '#ff6b00' : 'rgba(255,255,255,0.4)' }}; {{ $index === 0 ? 'width: 2rem;' : '' }}" aria-label="Diapositive {{ $index + 1 }}"></button>
            @endforeach
        </div>

        <button id="hero-prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/30 hover:bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button id="hero-next" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/30 hover:bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>
</section>

{{-- Trust Badges --}}
<section class="bg-white border-b border-stable-100 py-8 lg:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6">
            <div class="flex flex-col items-center text-center p-4 lg:p-5 bg-stable-50 rounded-2xl border border-stable-100 hover:border-safety/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-12 h-12 lg:w-14 lg:h-14 bg-green-100 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 lg:w-7 lg:h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h4 class="text-sm lg:text-base font-bold text-stable-900 mb-0.5">Garantie 12 mois</h4>
                <p class="text-[11px] lg:text-xs text-stable-500">Sur tous nos produits</p>
            </div>
            <div class="flex flex-col items-center text-center p-4 lg:p-5 bg-stable-50 rounded-2xl border border-stable-100 hover:border-safety/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-12 h-12 lg:w-14 lg:h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 lg:w-7 lg:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h4 class="text-sm lg:text-base font-bold text-stable-900 mb-0.5">Livraison offerte</h4>
                <p class="text-[11px] lg:text-xs text-stable-500">Commandes dès &euro;80 &bull; 1re commande offerte</p>
            </div>
            <div class="flex flex-col items-center text-center p-4 lg:p-5 bg-stable-50 rounded-2xl border border-stable-100 hover:border-safety/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-12 h-12 lg:w-14 lg:h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 lg:w-7 lg:h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <h4 class="text-sm lg:text-base font-bold text-stable-900 mb-0.5">Retours 30 jours</h4>
                <p class="text-[11px] lg:text-xs text-stable-500">Sans justification</p>
            </div>
            <div class="flex flex-col items-center text-center p-4 lg:p-5 bg-stable-50 rounded-2xl border border-stable-100 hover:border-safety/20 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-12 h-12 lg:w-14 lg:h-14 bg-safety/10 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 lg:w-7 lg:h-7 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h4 class="text-sm lg:text-base font-bold text-stable-900 mb-0.5">Paiement sécurisé</h4>
                <p class="text-[11px] lg:text-xs text-stable-500">Chiffrement SSL 256 bits</p>
            </div>
        </div>
    </div>
</section>

{{-- Categories --}}
<section class="py-16 lg:py-20 bg-stable-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 lg:mb-14">
            <span class="text-safety font-semibold text-sm uppercase tracking-widest">Catégories</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-stable-900 mt-2">Acheter par catégorie</h2>
            <p class="text-stable-500 mt-3 max-w-2xl mx-auto">Parcourez notre large gamme d'équipement équestre — des centaines d'articles en stock</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 lg:gap-5">
            @foreach($categories as $i => $cat)
            <a href="{{ route('shop') }}?category={{ $cat->slug }}" class="group relative bg-white rounded-xl border border-stable-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 {{ $i >= 6 ? 'hidden lg:block' : '' }}">
                <div class="aspect-[4/3] bg-stable-50 overflow-hidden">
                    <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}" class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><svg class=\'w-10 h-10 text-stable-300\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\'/></svg></div>'">
                </div>
                <div class="p-3 text-center">
                    <h3 class="font-semibold text-stable-900 text-sm">{{ $cat->name }}</h3>
                    <p class="text-stable-400 text-xs mt-0.5">{{ number_format($cat->count) }} articles</p>
                </div>
            </a>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('categories.all') }}" class="inline-flex items-center text-safety hover:text-safety-dark font-semibold transition-colors">
                Voir toutes les catégories
                <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Search Bar --}}
<section class="bg-gradient-to-b from-stable-900 to-stable-800 py-6 lg:py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <form action="{{ route('shop') }}" method="GET" class="relative" autocomplete="off">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ex. selle de dressage CWD, tapis laine, licol cuir..."
                       class="w-full pl-5 pr-14 py-3.5 bg-white rounded-xl text-sm lg:text-base text-stable-900 placeholder-stable-400 focus:outline-none focus:ring-2 focus:ring-safety shadow-xl" id="search-hero">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-5 py-2 bg-safety hover:bg-safety-dark text-white font-semibold rounded-lg text-sm transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
            <div id="suggest-hero" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-stable-100 overflow-hidden hidden z-50 text-left"></div>
        </form>
    </div>
</section>

{{-- Nouveautés --}}
<section class="py-10 lg:py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-cta font-semibold text-sm uppercase tracking-widest">Nouveautés</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-stable-900 mt-2">Nouveautés</h2>
                <p class="text-stable-500 mt-2">Les derniers articles ajoutés à notre catalogue — vérifiés et prêts à expédier</p>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($products as $product)
            <div class="group bg-white rounded-xl border border-stable-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer" onclick="window.location='{{ route('product.show', $product->slug) }}'">
                <div class="relative overflow-hidden bg-stable-50 aspect-square">
                    <img src="{{ $product->image ?? asset('images/default.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    @if($product->is_new)
                    <span class="absolute top-2 left-2 px-2 py-0.5 bg-cta text-white text-[10px] font-bold rounded-lg">Nouveau</span>
                    @endif
                    @if($product->old_price)
                    <span class="absolute top-2 right-2 px-2 py-0.5 bg-safety text-white text-[10px] font-bold rounded-lg">-{{ round((1 - $product->price / $product->old_price) * 100) }}%</span>
                    @endif
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <span class="px-4 py-2 bg-white/90 text-stable-900 text-xs font-semibold rounded-lg shadow-lg">Voir les détails</span>
                    </div>
                </div>
                <div class="p-3">
                    <p class="text-[10px] text-stable-400 font-medium mb-1 truncate">{{ $product->compatibility }}</p>
                    <h3 class="font-semibold text-stable-900 text-xs leading-snug mb-1 line-clamp-2">{{ $product->name }}</h3>
                    <div class="flex items-center gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-2.5 h-2.5 {{ $i <= floor($product->rating) ? 'text-yellow-400' : 'text-stable-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-base font-bold text-stable-900">&euro;{{ number_format($product->price, 2) }}</span>
                        @include('partials.product-card-cta', ['product' => $product])
                    </div>
                    @if($product->old_price)
                    <div class="mt-1"><span class="text-xs text-stable-400 line-through">&euro;{{ number_format($product->old_price, 2) }}</span></div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('shop') }}" class="inline-flex items-center px-6 py-3 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-safety/25">
                Voir tous les produits
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Pourquoi nous choisir --}}
<section class="py-16 lg:py-20 bg-stable-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 w-72 h-72 bg-safety rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-cta rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 lg:mb-16">
            <span class="text-safety font-semibold text-sm uppercase tracking-widest">Pourquoi nous choisir</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-white mt-2">Achetez en toute confiance</h2>
            <p class="text-stable-300 mt-3 max-w-2xl mx-auto">Nous nous engageons à fournir le meilleur du matériel équestre, partout en France</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 mb-12">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 lg:p-8 text-center hover:bg-white/10 transition-all duration-300 group">
                <div class="w-14 h-14 mx-auto mb-5 bg-safety/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Expédition 48h</h3>
                <p class="text-stable-400 text-sm leading-relaxed">Commandez avant 16h pour une expédition sous 48h ouvrées partout en France.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 lg:p-8 text-center hover:bg-white/10 transition-all duration-300 group">
                <div class="w-14 h-14 mx-auto mb-5 bg-safety/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-white font-semibold mb-2">100% Paiement sécurisé</h3>
                <p class="text-stable-400 text-sm leading-relaxed">Chiffrement SSL et protection anti-fraude de niveau bancaire. Vos données sont en sécurité.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 lg:p-8 text-center hover:bg-white/10 transition-all duration-300 group">
                <div class="w-14 h-14 mx-auto mb-5 bg-safety/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Garantie d'articles authentiques</h3>
                <p class="text-stable-400 text-sm leading-relaxed">Garantie minimale de 12 mois sur chaque article. Les produits premium jusqu'à 24 mois.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 lg:p-8 text-center hover:bg-white/10 transition-all duration-300 group">
                <div class="w-14 h-14 mx-auto mb-5 bg-safety/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Conseil expert</h3>
                <p class="text-stable-400 text-sm leading-relaxed">Nos selliers vous conseillent pour choisir l'équipement parfait. Par téléphone, email ou chat.</p>
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('shop') }}" class="inline-flex items-center px-8 py-3.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-safety/25 hover:shadow-safety/40 hover:-translate-y-0.5">
                Voir tous les produits
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Mixed Produits --}}
<section class="py-16 lg:py-20 bg-stable-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-safety font-semibold text-sm uppercase tracking-widest">Vous aimerez peut-être</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-stable-900 mt-2">Articles en vedette</h2>
                <p class="text-stable-500 mt-2">Une sélection de produits de qualité, renouvelée à chaque visite</p>
            </div>
            <a href="{{ route('shop') }}" class="inline-flex items-center text-safety hover:text-safety-dark font-semibold text-sm transition-colors">
                Tout voir
                <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($products->shuffle() as $product)
            <div class="group bg-white rounded-xl border border-stable-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer" onclick="window.location='{{ route('product.show', $product->slug) }}'">
                <div class="relative overflow-hidden bg-stable-50 aspect-square">
                    <img src="{{ $product->image ?? asset('images/default.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    @if($product->is_new)
                    <span class="absolute top-2 left-2 px-2 py-0.5 bg-cta text-white text-[10px] font-bold rounded-lg">Nouveau</span>
                    @endif
                    @if($product->old_price)
                    <span class="absolute top-2 right-2 px-2 py-0.5 bg-safety text-white text-[10px] font-bold rounded-lg">-{{ round((1 - $product->price / $product->old_price) * 100) }}%</span>
                    @endif
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <span class="px-4 py-2 bg-white/90 text-stable-900 text-xs font-semibold rounded-lg shadow-lg">Voir les détails</span>
                    </div>
                </div>
                <div class="p-3">
                    <p class="text-[10px] text-stable-400 font-medium mb-1 truncate">{{ $product->compatibility }}</p>
                    <h3 class="font-semibold text-stable-900 text-xs leading-snug mb-1 line-clamp-2">{{ $product->name }}</h3>
                    <div class="flex items-center gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-2.5 h-2.5 {{ $i <= floor($product->rating) ? 'text-yellow-400' : 'text-stable-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-base font-bold text-stable-900">&euro;{{ number_format($product->price, 2) }}</span>
                        @include('partials.product-card-cta', ['product' => $product])
                    </div>
                    @if($product->old_price)
                    <div class="mt-1"><span class="text-xs text-stable-400 line-through">&euro;{{ number_format($product->old_price, 2) }}</span></div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('shop') }}" class="inline-flex items-center px-6 py-3 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-safety/25">
                Voir tout le catalogue
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Marques — affichage logo statique (aucun lien) --}}
<section class="py-14 lg:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <span class="text-safety font-semibold text-sm uppercase tracking-widest">Nos marques</span>
            <h2 class="text-2xl lg:text-3xl font-bold text-stable-900 mt-2">Les grandes marques que nous distribuons</h2>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-8 lg:gap-14">
            @foreach($brands as $brand)
            <div class="flex items-center justify-center py-2" title="{{ $brand->name }}">
                @if($brand->image)
                <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}" class="h-10 lg:h-12 w-auto max-w-44 object-contain grayscale opacity-75 hover:grayscale-0 hover:opacity-100 transition-all duration-300" loading="lazy" onerror="this.outerHTML='<span class=\'text-lg font-bold text-stable-500\'>{{ $brand->name }}</span>'">
                @else
                <span class="text-lg font-bold text-stable-500">{{ $brand->name }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Avis clients Carousel --}}
<section class="py-16 lg:py-20 bg-stable-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 lg:mb-14">
            <span class="text-safety font-semibold text-sm uppercase tracking-widest">Avis clients</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-stable-900 mt-2">Ils nous font confiance</h2>
            <p class="text-stable-500 mt-3 max-w-2xl mx-auto">De vrais avis de clients vérifiés — plus de 15 ans de confiance</p>
        </div>
    </div>
    <div class="relative">
        <div id="testimonials-track" class="flex gap-6" style="animation: scrollAvis clients 40s linear infinite; width: max-content;">
            @for($r = 0; $r < 3; $r++)
            @foreach($testimonials as $testimonial)
            <div class="bg-white rounded-xl border border-stable-100 p-6 w-80 lg:w-96 flex-shrink-0 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 {{ $i < $testimonial->rating ? 'text-yellow-400' : 'text-stable-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-stable-600 text-sm leading-relaxed mb-4 italic line-clamp-4">"{{ $testimonial->text }}"</p>
                <div class="flex items-center gap-3">
                    <img src="{{ $testimonial->avatar }}" alt="{{ $testimonial->name }}" class="w-9 h-9 rounded-full object-cover" loading="lazy">
                    <div>
                        <p class="font-semibold text-stable-900 text-sm">{{ $testimonial->name }}</p>
                        <p class="text-stable-400 text-xs">{{ $testimonial->location }}</p>
                    </div>
                </div>
            </div>
            @endforeach
            @endfor
        </div>
    </div>
</section>

<style>
@keyframes scrollAvis clients {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
#testimonials-track:hover { animation-play-state: paused; }
</style>

{{-- Payment & Security --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 lg:mb-14">
            <span class="text-safety font-semibold text-sm uppercase tracking-widest">Paiement sécurisé</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-stable-900 mt-2">Nous acceptons</h2>
            <p class="text-stable-500 mt-3 max-w-2xl mx-auto">Vos paiements sont traités en toute sécurité. Nous acceptons tous les moyens de paiement courants.</p>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-6 lg:gap-10 mb-10">
            <div class="h-12 px-5 bg-stable-50 rounded-xl flex items-center justify-center border border-stable-100">
                <img src="{{ asset('images/s-visa.2285dc84.svg') }}" alt="Visa" class="h-7" loading="lazy">
            </div>
            <div class="h-12 px-5 bg-stable-50 rounded-xl flex items-center justify-center border border-stable-100">
                <img src="{{ asset('images/s-mastercard.ebdcfa0e.svg') }}" alt="Mastercard" class="h-7" loading="lazy">
            </div>
            <div class="h-12 px-5 bg-stable-50 rounded-xl flex items-center justify-center border border-stable-100">
                <img src="{{ asset('images/s-amex.c961722f.svg') }}" alt="American Express" class="h-7" loading="lazy">
            </div>
            <div class="h-12 px-5 bg-stable-50 rounded-xl flex items-center justify-center border border-stable-100">
                <img src="{{ asset('images/s-paypal.2843dda3.svg') }}" alt="PayPal" class="h-7" loading="lazy">
            </div>
            <div class="h-12 px-5 bg-stable-50 rounded-xl flex items-center justify-center border border-stable-100">
                <img src="{{ asset('images/s-applepay.18b5e830.svg') }}" alt="Apple Pay" class="h-7" loading="lazy">
            </div>
            <div class="h-12 px-5 bg-stable-50 rounded-xl flex items-center justify-center border border-stable-100">
                <img src="{{ asset('images/s-googlepay.2673545a.svg') }}" alt="Google Pay" class="h-7" loading="lazy">
            </div>
        </div>

        <div class="text-center mb-10">
            <span class="text-safety font-semibold text-sm uppercase tracking-widest">Sécurité & confiance</span>
            <h2 class="text-2xl lg:text-3xl font-bold text-stable-900 mt-2">Votre sécurité compte</h2>
            <p class="text-stable-500 mt-2 max-w-2xl mx-auto">Chaque transaction est protégée par un chiffrement de niveau bancaire</p>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-6 lg:gap-10">
            <div class="h-14 px-6 bg-stable-50 rounded-xl flex items-center justify-center border border-stable-100">
                <img src="{{ asset('images/s-verified-visa.29536af4.svg') }}" alt="Verified by Visa" class="h-9" loading="lazy">
            </div>
            <div class="h-14 px-6 bg-stable-50 rounded-xl flex items-center justify-center border border-stable-100">
                <img src="{{ asset('images/s-mastercard-secure.e2fa0d66.svg') }}" alt="Mastercard SecureCode" class="h-9" loading="lazy">
            </div>
            <div class="h-14 px-6 bg-stable-50 rounded-xl flex items-center justify-center border border-stable-100">
                <img src="{{ asset('images/s-amex-safekey.53c3c754.svg') }}" alt="American Express SafeKey" class="h-9" loading="lazy">
            </div>
            <div class="h-14 px-6 bg-stable-50 rounded-xl flex items-center justify-center border border-stable-100">
                <img src="{{ asset('images/s-sectigo.a7eb9c36.svg') }}" alt="Sectigo SSL" class="h-9" loading="lazy">
            </div>
        </div>
        <div class="text-center mt-8">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 rounded-xl text-green-700 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Chiffrement SSL 256 bits — vos données sont protégées
            </div>
        </div>
    </div>
</section>

{{-- Nouveausletter / CTA Banner --}}
<section class="py-16 lg:py-20 bg-stable-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-safety rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Restez informé</h2>
        <p class="text-stable-300 mb-8 max-w-xl mx-auto">Abonnez-vous à notre newsletter pour découvrir nos nouveautés, offres exclusives et conseils d'entretien.</p>
        <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <input type="email" placeholder="Entrez votre adresse email" class="flex-1 px-5 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-stable-400 focus:outline-none focus:border-safety focus:ring-1 focus:ring-safety transition-all duration-200">
            <button type="submit" class="px-6 py-3.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 whitespace-nowrap">S'abonner</button>
        </form>
    </div>
</section>

@push('scripts')
<script>
(function() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const prev = document.getElementById('hero-prev');
    const next = document.getElementById('hero-next');
    let current = 0;
    let interval;

    function goTo(index) {
        slides.forEach((s, i) => {
            s.style.opacity = i === index ? '1' : '0';
            s.style.zIndex = i === index ? '10' : '0';
            s.dataset.active = i === index ? 'true' : 'false';
        });
        dots.forEach((d, i) => {
            d.style.background = i === index ? '#ff6b00' : 'rgba(255,255,255,0.4)';
            d.style.width = i === index ? '2rem' : '0.625rem';
        });
        current = index;
    }

    function nextSlide() { goTo((current + 1) % slides.length); }
    function prevSlide() { goTo((current - 1 + slides.length) % slides.length); }

    dots.forEach(dot => {
        dot.addEventListener('click', function() { clearInterval(interval); goTo(parseInt(this.dataset.index)); startAuto(); });
    });
    if (prev) prev.addEventListener('click', function() { clearInterval(interval); prevSlide(); startAuto(); });
    if (next) next.addEventListener('click', function() { clearInterval(interval); nextSlide(); startAuto(); });

    function startAuto() { interval = setInterval(nextSlide, 6000); }
    startAuto();
})();
</script>
@endpush

@endsection
