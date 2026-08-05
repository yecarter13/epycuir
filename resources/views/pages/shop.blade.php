@extends('layouts.master')

@section('title', $categoryTitle ?? 'Boutique d\'équipement équestre — Sellerie Super Confort')
@section('meta_description', $metaDescription ?? 'Parcourez notre large catalogue d\'équipement équestre. Livraison rapide en France disponible sur des centaines d\'articles.')

@section('og_title', $categoryTitle ?? 'Boutique d\'équipement équestre — Sellerie Super Confort')
@section('og_description', $metaDescription ?? 'Parcourez notre large catalogue d\'équipement équestre. Livraison rapide en France disponible sur des centaines d\'articles.')
@section('og_url', url()->current())
@section('og_image', asset('favicon.svg'))
@section('og_type', 'website')

@section('content')

<section class="bg-stable-900 py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                @if($currentCategory)
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-white">Équipement pour {{ $currentCategory->name }}</h1>
                    <p class="text-stable-300 mt-1">{{ $total }} article{{ $total !== 1 ? 's' : '' }} trouvés dans {{ $currentCategory->name }}</p>
                </div>
                @else
                <h1 class="text-3xl lg:text-4xl font-bold text-white">Parcourez notre catalogue</h1>
                <p class="text-stable-300 mt-2">Des centaines d'articles prêts à être expédiés en France</p>
                @endif
            </div>
            <div class="flex items-center gap-3 text-sm">
                <a href="{{ route('home') }}" class="text-stable-400 hover:text-white transition-colors">Accueil</a>
                <svg class="w-4 h-4 text-stable-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white font-medium">@if($currentCategory){{ $currentCategory->name }}@else Boutique @endif</span>
            </div>
        </div>
    </div>
</section>

<section class="py-10 lg:py-14 bg-stable-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-4 lg:gap-8">

            <aside class="lg:col-span-1 mb-8 lg:mb-0">
                <div class="bg-white rounded-xl border border-stable-100 p-5 lg:p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-semibold text-stable-900">Filtres</h2>
                        <a href="{{ route('shop') }}" class="text-xs text-safety hover:text-safety-dark font-medium transition-colors">Tout effacer</a>
                    </div>

                    <form action="{{ route('shop') }}" method="GET" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-stable-900 mb-2">Recherche</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom de l'article ou référence..." class="w-full pl-9 pr-3 py-2 border border-stable-200 rounded-lg text-sm focus:outline-none focus:border-safety focus:ring-1 focus:ring-safety transition-all">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-stable-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stable-900 mb-2">Catégorie</label>
                            <select name="category" class="w-full px-3 py-2 border border-stable-200 rounded-lg text-sm text-stable-600 focus:outline-none focus:border-safety focus:ring-1 focus:ring-safety transition-all bg-white">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-stable-900 mb-2">Fourchette de prix</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" min="0" class="w-full px-3 py-2 border border-stable-200 rounded-lg text-sm focus:outline-none focus:border-safety transition-all">
                                <span class="text-stable-400 text-sm">-</span>
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" min="0" class="w-full px-3 py-2 border border-stable-200 rounded-lg text-sm focus:outline-none focus:border-safety transition-all">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-lg transition-all duration-200 text-sm">
                            Appliquer les filtres
                        </button>
                    </form>
                </div>
            </aside>

            <div class="lg:col-span-3">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm text-stable-500">Affichage <span class="font-semibold text-stable-900">{{ $products->firstItem() ?? 0 }}</span>-<span class="font-semibold text-stable-900">{{ $products->lastItem() ?? 0 }}</span> sur <span class="font-semibold text-stable-900">{{ $total }}</span> résultats</p>
                    <div class="flex items-center gap-3">
                        <label class="text-sm text-stable-500 hidden sm:block">Trier par :</label>
                        <form action="{{ route('shop') }}" method="GET">
                            @foreach(request()->except('sort', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="sort" onchange="this.form.submit()" class="px-3 py-2 border border-stable-200 rounded-lg text-sm text-stable-600 focus:outline-none focus:border-safety bg-white">
                                <option value="popularity" {{ request('sort', 'popularity') == 'popularity' ? 'selected' : '' }}>Popularité</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Mieux notés</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-5">
                    @forelse($products as $product)
                    <div class="group bg-white rounded-xl border border-stable-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer" onclick="window.location='{{ route('product.show', $product->slug) }}'">
                        <div class="relative overflow-hidden bg-stable-50 aspect-square">
                            <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400&q=80' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
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
                            <span class="text-[10px] text-stable-400 font-medium">{{ $product->category?->name ?? 'Général' }}</span>
                            <h3 class="font-semibold text-stable-900 text-xs leading-snug my-1 line-clamp-2">{{ $product->name }}</h3>
                            <p class="text-[10px] text-stable-400 mb-1 truncate">{{ $product->compatibility }}</p>
                            <div class="flex items-center gap-1 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-2.5 h-2.5 {{ $i <= round($product->rating) ? 'text-yellow-400' : 'text-stable-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
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
                    @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 text-stable-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <p class="text-stable-500 font-medium">Aucun produit ne correspond à vos critères.</p>
                        <a href="{{ route('shop') }}" class="text-safety hover:text-safety-dark text-sm mt-2 inline-block">Effacer tous les filtres</a>
                    </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</section>

@include('partials.floating-chatbot')

@endsection
