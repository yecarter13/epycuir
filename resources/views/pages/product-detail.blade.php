@extends('layouts.master')

@section('title', $product->name . ' — Sellerie Epycuir')

@section('meta_description', $product->meta_description ?? Str::limit(strip_tags($product->description), 160))

@section('canonical', route('product.show', $product->slug))

@section('og_title', $product->name)
@section('og_description', $product->meta_description ?? Str::limit(strip_tags($product->description), 160))
@section('og_url', route('product.show', $product->slug))
@section('og_image', $product->image ?? asset('favicon.svg'))
@section('og_type', 'product')

@php
$desc = strip_tags($product->description ?? $product->meta_description ?? $product->name);
$descShort = Str::limit($desc, 160);
$price = number_format($product->price, 2, '.', '');
$productUrl = route('product.show', $product->slug);
$imageUrl = $product->image ?? asset('favicon.svg');
$inStock = $product->stock_quantity > 0;

$ldProduct = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->name,
    'description' => $descShort,
    'url' => $productUrl,
    'sku' => $product->sku,
    'mpn' => $product->sku,
    'name' => $product->name,    'image' => $imageUrl,
    'offers' => [
        '@type' => 'Offer',
        'url' => $productUrl,
        'priceCurrency' => 'EUR',
        'price' => $price,
        'priceValidUntil' => now()->addYear()->format('Y-m-d'),
        'availability' => $inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        'itemCondition' => 'https://schema.org/NouveauCondition',
        'seller' => ['@type' => 'Organization', 'name' => 'Sellerie Epycuir'],
    ],
];
if ($product->category) $ldProduct['category'] = $product->category->name;
if ($product->gallery_images) $ldProduct['photos'] = $product->gallery_images;
if ($product->rating && $product->review_count) {
    $ldProduct['aggregateRating'] = [
        '@type' => 'AggregateRating',
        'ratingValue' => number_format($product->rating, 1),
        'reviewCount' => $product->review_count,
        'bestRating' => 5,
        'worstRating' => 1,
    ];
    $ldProduct['review'] = [
        ['@type' => 'Review', 'reviewRating' => ['@type' => 'Rating', 'ratingValue' => number_format($product->rating, 1), 'bestRating' => 5]],
    ];
}

$ldBreadcrumb = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Boutique', 'item' => route('shop')],
    ],
];
if ($product->category) {
    $catUrl = url('/shop?category=' . $product->category->slug);
    $ldBreadcrumb['itemListElement'][] = [
        '@type' => 'ListItem', 'position' => 3, 'name' => $product->category->name,
        'item' => $catUrl,
    ];
}
@endphp

@section('seo_head')
<meta name="robots" content="index, follow">
<meta property="product:price:amount" content="{{ $price }}">
<meta property="product:price:currency" content="EUR">
<meta property="article:modified_time" content="{{ $product->updated_at->toIso8601String() }}">
<script type="application/ld+json">@json($ldProduct, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
<script type="application/ld+json">@json($ldBreadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
@endsection

@section('content')

{{-- Breadcrumb --}}
<section class="bg-stable-50 border-b border-stable-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center gap-2 text-sm">
        <a href="{{ route('home') }}" class="text-stable-400 hover:text-stable-600 transition-colors">Accueil</a>
        <svg class="w-3.5 h-3.5 text-stable-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('shop') }}" class="text-stable-400 hover:text-stable-600 transition-colors">Boutique</a>
        @if($product->category)
        <svg class="w-3.5 h-3.5 text-stable-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-stable-400">{{ $product->category->name }}</span>
        @endif
        <svg class="w-3.5 h-3.5 text-stable-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-stable-900 font-medium truncate">{{ $product->name }}</span>
    </div>
</section>

{{-- Product Detail --}}
<section class="py-10 lg:py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-12">

            {{-- Gallery --}}
            <div>
                <div class="relative aspect-square bg-stable-50 rounded-2xl overflow-hidden border border-stable-100 mb-4 cursor-pointer" id="gallery-trigger">
                    <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600&q=80' }}" alt="{{ $product->name }}" class="w-full h-full object-cover" id="main-image">
                    @if($product->is_new)<span class="absolute top-4 left-4 px-3 py-1 bg-cta text-white text-xs font-bold rounded-lg z-10">Nouveau</span>@endif
                    @if($product->old_price)<span class="absolute top-4 right-4 px-3 py-1 bg-safety text-white text-xs font-bold rounded-lg z-10">-{{ round((1 - $product->price / $product->old_price) * 100) }}%</span>@endif
                    <div class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-colors flex items-center justify-center">
                        <svg class="w-10 h-10 text-white opacity-0 hover:opacity-100 transition-opacity drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                    </div>
                </div>
                @if($product->gallery_images)
                <div class="flex gap-3 overflow-x-auto pb-2" id="gallery-thumbs">
                    @foreach($product->gallery_images as $i => $img)
                    <div class="w-20 h-20 flex-shrink-0 bg-stable-50 rounded-xl overflow-hidden border-2 transition-all cursor-pointer gallery-thumb {{ $i === 0 ? 'border-safety' : 'border-stable-100 hover:border-safety' }}" data-index="{{ $i }}">
                        <img src="{{ $img }}" alt="{{ $product->name }} - vue {{ $i + 1 }}" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="mt-8 lg:mt-0">
                <h1 class="text-2xl lg:text-3xl font-bold text-stable-900 leading-tight">{{ $product->name }}</h1>

                <div class="flex items-center gap-4 mt-3">
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= round($product->rating) ? 'text-yellow-400' : 'text-stable-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span class="text-sm text-stable-500">{{ number_format($product->rating, 1) }} ({{ $product->review_count ?: 0 }} avis)</span>
                    <span class="text-sm text-stable-300">|</span>
                    <span class="text-sm text-stable-500">SKU: {{ $product->sku }}</span>
                </div>

                <div class="mt-6">
                    <div class="flex items-baseline gap-3">
                        <span class="text-4xl font-bold text-stable-900">&euro;{{ number_format($product->price, 2) }}</span>
                        @if($product->old_price)
                        <span class="text-xl text-stable-400 line-through">&euro;{{ number_format($product->old_price, 2) }}</span>
                        <span class="px-2.5 py-1 bg-stable-50 text-safety text-xs font-bold rounded-lg">Économisez &euro;{{ number_format($product->old_price - $product->price, 2) }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-stable-500 mt-1">TTC — Livraison offerte dès &euro;80</p>
                </div>

                @if($product->compatibility)
                <div class="mt-6 p-4 bg-stable-50 rounded-xl border border-stable-100">
                    <div class="flex items-center gap-2 text-sm font-medium text-stable-900 mb-1">
                        <svg class="w-4 h-4 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Compatibilité
                    </div>
                    <p class="text-sm text-stable-600">{{ $product->compatibility }}</p>
                </div>
                @endif

                {{-- Stock Status --}}
                <div class="mt-4 flex items-center gap-2">
                    @if($product->stock_quantity > 10)
                    <span class="w-2 h-2 rounded-full bg-safety"></span>
                    <span class="text-sm text-safety font-medium">En stock — prêt à expédier</span>
                    @elseif($product->stock_quantity > 0)
                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                    <span class="text-sm text-yellow-600 font-medium">Stock limité — plus que {{ $product->stock_quantity }}</span>
                    @else
                    <span class="w-2 h-2 rounded-full bg-cta"></span>
                    <span class="text-sm text-cta font-medium">Rupture de stock</span>
                    @endif
                </div>

                {{-- Quantité + Buttons --}}
                <div class="mt-8 space-y-3">
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-stable-900">Quantité :</label>
                        <div class="flex items-center border border-stable-200 rounded-xl overflow-hidden">
                            <button class="px-3 py-2 text-stable-500 hover:bg-stable-50 transition-colors">-</button>
                            <input type="number" value="1" min="1" max="{{ $product->stock_quantity ?: 1 }}" class="w-16 text-center py-2 text-sm font-medium border-x border-stable-200 focus:outline-none">
                            <button class="px-3 py-2 text-stable-500 hover:bg-stable-50 transition-colors">+</button>
                        </div>
                    </div>

                    @if(\App\Support\OrderMode::isWhatsapp())
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a id="waCommanderBtn" href="#" target="_blank" rel="noopener" class="flex-1 px-8 py-3.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Commander via WhatsApp
                        </a>
                    </div>
                    <p class="text-xs text-stable-400 text-center mt-2">Paiement en ligne non requis — votre commande sera confirmée sur WhatsApp.</p>
                    @else
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button id="addToCartBtn" class="flex-1 px-8 py-3.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-safety/25 hover:shadow-safety/40 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                            Ajouter au panier
                        </button>
                        <form method="POST" action="{{ route('buy-now', $product->slug) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="quantity" id="buyNowQty" value="1">
                            <button type="submit" class="w-full px-8 py-3.5 bg-stable-900 hover:bg-stable-800 text-white font-semibold rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                Acheter maintenant
                            </button>
                        </form>
                    </div>
                    @endif

                    <div class="flex items-center gap-4 justify-center pt-2">
                        <button class="flex items-center gap-1.5 text-sm text-stable-500 hover:text-cta transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            Ajouter à ma liste
                        </button>
                        <button class="flex items-center gap-1.5 text-sm text-stable-500 hover:text-safety transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            Partager
                        </button>
                    </div>
                </div>

                {{-- Trust Badges --}}
                <div class="mt-8 p-5 bg-stable-50 rounded-xl border border-stable-100">
                    <div class="flex items-center justify-center gap-4 flex-wrap">
                        <div class="flex items-center gap-2 text-xs text-stable-500">
                            <svg class="w-4 h-4 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Livraison offerte dès &euro;80
                        </div>
                        <div class="flex items-center gap-2 text-xs text-stable-500">
                            <svg class="w-4 h-4 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Garantie 12 mois
                        </div>
                        <div class="flex items-center gap-2 text-xs text-stable-500">
                            <svg class="w-4 h-4 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Retours 30 jours
                        </div>
                        <div class="flex items-center gap-2 text-xs text-stable-500">
                            <svg class="w-4 h-4 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Paiement sécurisé
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Description & Specs Tabs --}}
<section class="py-10 lg:py-14 bg-stable-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-stable-100 overflow-hidden">
            <div class="flex border-b border-stable-100">
                <button class="tab-btn px-6 py-4 text-sm font-semibold text-stable-900 border-b-2 border-safety" data-tab="description">Description</button>
                @if($product->specifications)
                <button class="tab-btn px-6 py-4 text-sm font-medium text-stable-400 hover:text-stable-900 transition-colors" data-tab="specs">Caractéristiques</button>
                @endif
                <button class="tab-btn px-6 py-4 text-sm font-medium text-stable-400 hover:text-stable-900 transition-colors" data-tab="avis">Avis ({{ $product->review_count ?: 0 }})</button>
            </div>
            <div class="p-6 lg:p-8">
                <div id="tab-description" class="tab-content">
                    <div class="prose prose-sm max-w-none text-stable-600 leading-relaxed">
                        {!! $product->description ?? '<p>Aucune description disponible pour cet article. Contactez notre équipe support pour plus d\'informations.</p>' !!}
                    </div>
                </div>
                @if($product->specifications)
                <div id="tab-specs" class="tab-content hidden">
                    <div class="prose prose-sm max-w-none text-stable-600 leading-relaxed">
                        {!! $product->specifications !!}
                    </div>
                </div>
                @endif
                <div id="tab-avis" class="tab-content hidden">
                    <p class="text-stable-400 text-sm">Les avis sont en cours de vérification. Revenez bientôt ou <a href="{{ route('contact') }}" class="text-safety hover:underline">contactez-nous</a> pour plus d'informations sur cet article.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Related Produits --}}
@if($related->count())
<section class="py-10 lg:py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-safety font-semibold text-sm uppercase tracking-widest">Articles similaires</span>
                <h2 class="text-2xl lg:text-3xl font-bold text-stable-900 mt-1">Articles similaires qui pourraient vous intéresser</h2>
            </div>
            <a href="{{ route('shop') }}" class="text-sm text-safety hover:text-safety-dark font-medium transition-colors hidden sm:block">Tout voir</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($related as $rel)
            <div class="group bg-white rounded-xl border border-stable-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="aspect-square bg-stable-50 overflow-hidden">
                    <img src="{{ $rel->image ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400&q=80' }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-sm text-stable-900 line-clamp-2">{{ $rel->name }}</h3>
                    <p class="text-lg font-bold text-stable-900 mt-2">&euro;{{ number_format($rel->price, 2) }}</p>
                    <a href="{{ route('product.show', $rel->slug) }}" class="mt-2 inline-flex items-center text-sm text-safety hover:text-safety-dark font-medium transition-colors">Voir les détails <svg class="ml-1 w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Lightbox --}}
<div id="lightbox" class="fixed inset-0 z-50 bg-black/90 hidden items-center justify-center">
    <button id="lightbox-close" class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center text-white/70 hover:text-white transition-colors z-10">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    <button id="lightbox-prev" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-all">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button id="lightbox-next" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-all">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
    <div class="max-w-4xl max-h-[90vh] mx-4 flex items-center justify-center" id="lightbox-container">
        <img id="lightbox-img" src="" alt="{{ $product->name }}" class="max-w-full max-h-[90vh] object-contain rounded-lg">
    </div>
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/60 text-sm" id="lightbox-counter"></div>
</div>

@push('scripts')
<script>
    // Data
    const galleryImages = @json($product->gallery_images ?? []);
    const mainImageSrc = (galleryImages.length ? galleryImages[0] : '{{ $product->image ?? "https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600&q=80" }}').replace('w=600&q=80', 'w=1200&q=90');
    let currentIndex = 0;
    let allImages = galleryImages.length ? galleryImages : [mainImageSrc];

    // Thumbnail click - swap main image
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.addEventListener('click', function() {
            const idx = parseInt(this.dataset.index);
            currentIndex = idx;
            const img = document.getElementById('main-image');
            img.src = allImages[idx];
            document.querySelectorAll('.gallery-thumb').forEach(t => {
                t.classList.remove('border-safety');
                t.classList.add('border-stable-100', 'hover:border-safety');
            });
            this.classList.remove('border-stable-100', 'hover:border-safety');
            this.classList.add('border-safety');
        });
    });

    // Main image click - open lightbox
    document.getElementById('gallery-trigger')?.addEventListener('click', function(e) {
        if (e.target.closest('span')) return;
        openLightbox(currentIndex);
    });

    function openLightbox(index) {
        currentIndex = index;
        const img = document.getElementById('lightbox-img');
        img.src = allImages[index];
        document.getElementById('lightbox').classList.remove('hidden');
        document.getElementById('lightbox').classList.add('flex');
        updateCounter();
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
        document.getElementById('lightbox').classList.remove('flex');
        document.body.style.overflow = '';
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + allImages.length) % allImages.length;
        document.getElementById('lightbox-img').src = allImages[currentIndex];
        updateCounter();
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % allImages.length;
        document.getElementById('lightbox-img').src = allImages[currentIndex];
        updateCounter();
    }

    function updateCounter() {
        document.getElementById('lightbox-counter').textContent = (currentIndex + 1) + ' / ' + allImages.length;
    }

    // Lightbox controls
    document.getElementById('lightbox-close')?.addEventListener('click', closeLightbox);
    document.getElementById('lightbox-prev')?.addEventListener('click', prevImage);
    document.getElementById('lightbox-next')?.addEventListener('click', nextImage);
    document.getElementById('lightbox')?.addEventListener('click', function(e) {
        if (e.target === this) closeLightbox();
    });
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('lightbox').classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
        }
    });

    // Tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('text-stable-900', 'font-semibold', 'border-b-2', 'border-safety'); b.classList.add('text-stable-400', 'font-medium'); });
            this.classList.add('text-stable-900', 'font-semibold', 'border-b-2', 'border-safety');
            this.classList.remove('text-stable-400', 'font-medium');
            document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
            document.getElementById('tab-' + this.dataset.tab)?.classList.remove('hidden');
        });
    });

    // Quantité selector
    const qtyInput = document.querySelector('input[type="number"]');
    const buyNowQty = document.getElementById('buyNowQty');
    document.querySelector('.flex.items-center.border .px-3.py-2:first-child')?.addEventListener('click', function() {
        let v = parseInt(qtyInput.value) - 1;
        if (v < 1) v = 1;
        qtyInput.value = v;
        buyNowQty.value = v;
    });
    document.querySelector('.flex.items-center.border .px-3.py-2:last-child')?.addEventListener('click', function() {
        let v = parseInt(qtyInput.value) + 1;
        const max = parseInt(qtyInput.max);
        if (v > max) v = max;
        qtyInput.value = v;
        buyNowQty.value = v;
    });
    qtyInput?.addEventListener('change', function() {
        buyNowQty.value = this.value;
    });

    // WhatsApp order button (builds link with selected quantity)
    const waCommanderBtn = document.getElementById('waCommanderBtn');
    if (waCommanderBtn) {
        const waNumber = '{{ \App\Support\OrderMode::whatsappNumber() }}';
        waCommanderBtn.addEventListener('click', function(e) {
            if (!waNumber) {
                e.preventDefault();
                window.location.href = '{{ route('contact') }}';
                return;
            }
            const qty = parseInt(qtyInput.value) || 1;
            const price = ({{ $product->price }} * qty).toFixed(2);
            const msg = 'Bonjour Sellerie Epycuir, je souhaite commander cet article :\n\n'
                + '- Produit : {{ $product->name }}\n'
                + '- Référence : {{ $product->sku ?? $product->slug }}\n'
                + '- Quantité : ' + qty + '\n'
                + '- Prix : €' + price + '\n\n'
                + 'Pouvez-vous confirmer la disponibilité et l\'expédition ?';
            this.href = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(msg);
        });
    }

    // Ajouter au panier
    document.getElementById('addToCartBtn')?.addEventListener('click', function() {
        const qty = parseInt(qtyInput.value) || 1;
        this.disabled = true;
        this.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Ajout en cours...';
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({ product_id: {{ $product->id }}, quantity: qty })
        })
        .then(r => r.json())
        .then(d => {
            document.querySelectorAll('.cart-count').forEach(el => el.textContent = d.count);
            this.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Ajouté !';
            setTimeout(() => {
                this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg> Ajouter au panier';
                this.disabled = false;
            }, 1500);
        })
        .catch(() => {
            this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg> Ajouter au panier';
            this.disabled = false;
        });
    });
</script>
@endpush

@endsection
