@extends('layouts.master')

@section('title', 'Toutes les catégories — Sellerie Super Confort')
@section('meta_description', 'Parcourez toutes les catégories d\'équipement équestre chez Sellerie Super Confort. Des selles aux tapis, trouvez l\'équipement idéal pour votre cheval avec une livraison rapide en France.')

@section('og_title', 'Toutes les catégories — Sellerie Super Confort')
@section('og_description', 'Parcourez toutes les catégories d\'équipement équestre chez Sellerie Super Confort. Des selles aux tapis, trouvez l\'équipement idéal pour votre cheval avec une livraison rapide en France.')
@section('og_url', url()->current())
@section('og_image', asset('favicon.svg'))
@section('og_type', 'website')

@section('content')

<section class="bg-stable-900 py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-white">Toutes les catégories</h1>
                <p class="text-stable-300 mt-2">{{ $categories->sum('count') }} articles répartis dans {{ $categories->count() }} catégories</p>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <a href="{{ route('home') }}" class="text-stable-400 hover:text-white transition-colors">Accueil</a>
                <svg class="w-4 h-4 text-stable-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white font-medium">Catégories</span>
            </div>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16 bg-stable-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 lg:gap-6">
            @foreach($categories as $cat)
            <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="group bg-white rounded-xl border border-stable-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
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
    </div>
</section>

@endsection
