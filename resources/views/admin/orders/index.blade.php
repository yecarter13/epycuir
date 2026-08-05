@php
    $statusIcon = fn($s) => match($s) {
        'paid' => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'pending' => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'cancelled' => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        default => '',
    };
    $statusClass = fn($s) => match($s) {
        'paid' => 'bg-green-100 text-green-700 border-green-200',
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

@extends('admin.layouts.master')

@section('title', 'Commandes — Admin')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border border-stable-100 p-4">
            <p class="text-xs text-stable-400 uppercase tracking-wider mb-1">Total commandes</p>
            <p class="text-2xl font-bold text-stable-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-green-100 p-4">
            <p class="text-xs text-green-600 uppercase tracking-wider mb-1 flex items-center gap-1">{!! $statusIcon('paid') !!} Payées</p>
            <p class="text-2xl font-bold text-green-700">{{ $stats['paid'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-amber-100 p-4">
            <p class="text-xs text-amber-600 uppercase tracking-wider mb-1 flex items-center gap-1">{!! $statusIcon('pending') !!} En attente</p>
            <p class="text-2xl font-bold text-amber-700">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-red-100 p-4">
            <p class="text-xs text-red-600 uppercase tracking-wider mb-1 flex items-center gap-1">{!! $statusIcon('cancelled') !!} Annulées</p>
            <p class="text-2xl font-bold text-red-700">{{ $stats['cancelled'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-stable-100 p-4">
            <p class="text-xs text-stable-400 uppercase tracking-wider mb-1">Chiffre d'affaires (payé)</p>
            <p class="text-2xl font-bold text-stable-900">&euro;{{ number_format($stats['revenue'], 2) }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-xl border border-stable-100 p-4">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs text-stable-500 mb-1 font-medium">Statut</label>
                <select name="status" class="px-3 py-2 border border-stable-200 rounded-lg text-sm focus:outline-none focus:border-safety">
                    <option value="">Tous</option>
                    <option value="paid" @selected(request('status') === 'paid')>Payées</option>
                    <option value="pending" @selected(request('status') === 'pending')>En attente</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Annulées</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-stable-500 mb-1 font-medium">Du</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 border border-stable-200 rounded-lg text-sm focus:outline-none focus:border-safety">
            </div>
            <div>
                <label class="block text-xs text-stable-500 mb-1 font-medium">Au</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2 border border-stable-200 rounded-lg text-sm focus:outline-none focus:border-safety">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-stable-500 mb-1 font-medium">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="N° commande, nom, email..." class="w-full px-3 py-2 border border-stable-200 rounded-lg text-sm focus:outline-none focus:border-safety">
            </div>
            <button type="submit" class="px-4 py-2 bg-safety hover:bg-safety-dark text-white text-sm font-medium rounded-lg transition-colors">Filtrer</button>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-stable-100 hover:bg-stable-200 text-stable-600 text-sm font-medium rounded-lg transition-colors">Réinitialiser</a>
        </form>
    </div>

    {{-- Commanders Table --}}
    <div class="bg-white rounded-xl border border-stable-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-stable-50 text-stable-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium">N° commande</th>
                        <th class="text-left px-4 py-3 font-medium">Client</th>
                        <th class="text-left px-4 py-3 font-medium">Articles</th>
                        <th class="text-left px-4 py-3 font-medium">Total</th>
                        <th class="text-left px-4 py-3 font-medium">Statut</th>
                        <th class="text-left px-4 py-3 font-medium">Date</th>
                        <th class="text-right px-4 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stable-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-stable-50/50 transition-colors">
                        <td class="px-4 py-3 font-mono text-xs font-medium text-stable-900">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-stable-900">{{ $order->customer_name }}</p>
                            <p class="text-xs text-stable-400">{{ $order->customer_email }}</p>
                        </td>
                        <td class="px-4 py-3 text-stable-500">{{ $order->items->count() }}</td>
                        <td class="px-4 py-3 font-semibold text-stable-900">&euro;{{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full border {{ $statusClass($order->status) }}">
                                {!! $statusIcon($order->status) !!}
                                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-stable-500 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-safety hover:text-safety-dark text-sm font-medium transition-colors">Voir</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-stable-400">Aucune commande trouvée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-stable-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
