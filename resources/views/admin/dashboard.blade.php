@extends('admin.layouts.master')

@section('title', 'Tableau de bord')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl border border-stable-100 p-5">
        <p class="text-stable-400 text-sm">Total produits</p>
        <p class="text-3xl font-bold text-stable-900 mt-1">{{ $stats['products'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-stable-100 p-5">
        <p class="text-stable-400 text-sm">Catégories</p>
        <p class="text-3xl font-bold text-stable-900 mt-1">{{ $stats['categories'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-stable-100 p-5">
        <p class="text-stable-400 text-sm">Clients inscrits</p>
        <p class="text-3xl font-bold text-stable-900 mt-1">{{ $stats['users'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-stable-100 p-5">
        <p class="text-stable-400 text-sm">Articles en stock faible</p>
        <p class="text-3xl font-bold {{ $stats['low_stock'] > 0 ? 'text-cta' : 'text-stable-900' }} mt-1">{{ $stats['low_stock'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
    <div class="bg-white rounded-xl border border-green-100 p-5">
        <p class="text-green-600 text-sm font-medium">Chiffre d'affaires du jour</p>
        <p class="text-3xl font-bold text-green-700 mt-1">&euro;{{ number_format($stats['revenue_today'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-blue-100 p-5">
        <p class="text-blue-600 text-sm font-medium">Chiffre d'affaires du mois</p>
        <p class="text-3xl font-bold text-blue-700 mt-1">&euro;{{ number_format($stats['revenue_month'], 2) }}</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-stable-100 p-5">
        <h2 class="font-semibold text-stable-900 mb-4">Produits récents</h2>
        <div class="space-y-3">
            @forelse($recentProducts as $p)
            <div class="flex items-center justify-between py-2 border-b border-stable-50 last:border-0">
                <div>
                    <p class="text-sm font-medium text-stable-900">{{ $p->name }}</p>
                    <p class="text-xs text-stable-400">SKU: {{ $p->sku }}</p>
                </div>
                <span class="text-sm font-bold text-stable-900">&euro;{{ number_format($p->price, 2) }}</span>
            </div>
            @empty
            <p class="text-sm text-stable-400">Aucun produit pour le moment.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-xl border border-stable-100 p-5">
        <h2 class="font-semibold text-stable-900 mb-4">Aperçu des catégories</h2>
        <div class="space-y-3">
            @forelse($categories as $cat)
            <div class="flex items-center justify-between py-2 border-b border-stable-50 last:border-0">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full {{ $cat->is_active ? 'bg-green-500' : 'bg-stable-300' }}"></span>
                    <p class="text-sm font-medium text-stable-900">{{ $cat->name }}</p>
                </div>
                <span class="text-xs text-stable-500">{{ $cat->products_count }} produits</span>
            </div>
            @empty
            <p class="text-sm text-stable-400">Aucune catégorie pour le moment.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
