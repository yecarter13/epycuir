@extends('layouts.master')

@section('title', 'Paiement annulé — Sellerie Super Confort')

@section('content')

<section class="py-20 bg-stable-900 text-center">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-20 h-20 bg-amber-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <h1 class="text-3xl lg:text-4xl font-bold text-white mb-3">Paiement annulé</h1>
        <p class="text-stable-300 text-lg mb-2">Votre paiement n'a pas été traité et aucun débit n'a été effectué.</p>
        <p class="text-stable-400">Vos articles sont conservés dans votre panier — vous pouvez réessayer à tout moment.</p>
    </div>
</section>

<section class="py-14 bg-stable-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('checkout') }}" class="w-full sm:w-auto px-8 py-3.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-safety/25 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                Réessayer
            </a>
            <a href="{{ route('cart') }}" class="w-full sm:w-auto px-8 py-3.5 bg-stable-100 hover:bg-stable-200 text-stable-700 font-semibold rounded-xl transition-all flex items-center justify-center gap-2">
                Voir le panier
            </a>
            <a href="{{ route('shop') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white border border-stable-200 hover:bg-stable-50 text-stable-700 font-semibold rounded-xl transition-all flex items-center justify-center gap-2">
                Continue mes achats
            </a>
        </div>
    </div>
</section>

@endsection
