@extends('layouts.master')

@section('title', 'À propos — Sellerie Super Confort')

@section('content')

{{-- Hero --}}
<section class="relative bg-stable-900 py-20 lg:py-28 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1553284965-83fd3e82fa5a?w=1920&q=80" alt="Atelier Sellerie Super Confort" class="w-full h-full object-cover" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-r from-stable-900/95 via-stable-900/80 to-stable-900/60"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 bg-safety/90 text-white text-xs font-semibold uppercase tracking-widest rounded-full mb-4">À propos</span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4">Conçu par des selliers,<br>Fait pour les cavaliers</h1>
        <p class="text-lg text-stable-200 max-w-2xl mx-auto">Depuis plus de 15 ans, Sellerie Super Confort est la référence française du matériel équestre : selles, brides, tapis, licols et accessoires d'écurie.</p>
    </div>
</section>

{{-- Story --}}
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
            <div>
                <span class="text-safety font-semibold text-sm uppercase tracking-widest">Notre histoire</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-stable-900 mt-3 mb-6">De l'atelier familial à la référence française du matériel équestre</h2>
                <div class="space-y-4 text-stable-600 leading-relaxed">
                    <p>Sellerie Super Confort a été fondée en 2010 par Gilles Martin, sellier-harnacheur de métier, installé à Courteranges, dans l'Aube. Lassé des matériaux de mauvaise qualité et des conseils trop peu sincères, il décide de créer sa propre maison avec une conviction : proposer à chaque cavalier un équipement durable, vérifié et adapté à son cheval.</p>
                    <p>Aujourd'hui, notre atelier et notre boutique accueillent cavaliers, éleveurs et clubs équestres de toute la France. Chaque selle, bride, tapis ou licol est rigoureusement contrôlé avant de rejoindre nos rayons, et nous sélectionnons nos fournisseurs avec le plus grand soin.</p>
                    <p>Nous servons aussi bien les cavaliers de loisir que les professionnels du concours et les centres équestres. Notre engagement reste le même depuis le premier jour : le bon matériel, au bon prix, livré dans les meilleurs délais.</p>
                </div>
            </div>
            <div class="mt-8 lg:mt-0">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600&q=80" alt="Notre atelier" class="rounded-2xl shadow-xl" loading="lazy">
                    <div class="absolute -bottom-6 -left-6 bg-safety rounded-2xl p-6 shadow-xl hidden lg:block">
                        <p class="text-3xl font-bold text-white">15+</p>
                        <p class="text-white/80 text-sm">Années d'excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="py-16 lg:py-20 bg-stable-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            @foreach($stats as $stat)
            <div class="text-center p-6 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl">
                <div class="w-12 h-12 mx-auto mb-4 bg-safety/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat->icon }}"/></svg>
                </div>
                <p class="text-3xl lg:text-4xl font-bold text-white mb-1">{{ $stat->value }}</p>
                <p class="text-stable-400 text-sm">{{ $stat->label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Values --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 lg:mb-16">
            <span class="text-safety font-semibold text-sm uppercase tracking-widest">Nos valeurs</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-stable-900 mt-2">Ce qui nous anime</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8 rounded-xl border border-stable-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-5 bg-stable-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-stable-900 mb-3">Une qualité de confiance</h3>
                <p class="text-stable-500 leading-relaxed">Nous ne sélectionnons que des cuirs et matériaux issus de marques reconnues. Chaque article est contrôlé avant d'être proposé à nos clients.</p>
            </div>
            <div class="text-center p-8 rounded-xl border border-stable-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-5 bg-stable-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-8h.01"/></svg>
                </div>
                <h3 class="text-xl font-bold text-stable-900 mb-3">Une disponibilité inégalée</h3>
                <p class="text-stable-500 leading-relaxed">Avec plus de 5 000 articles en stock, selles, brides et accessoires confondus, nous livrons partout en France sous 48 heures.</p>
            </div>
            <div class="text-center p-8 rounded-xl border border-stable-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-16 h-16 mx-auto mb-5 bg-stable-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-safety" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-stable-900 mb-3">Des conseils d'experts</h3>
                <p class="text-stable-500 leading-relaxed">Notre équipe de selliers qualifiés est à votre écoute. Que vous ayez besoin d'un ajustement de selle ou d'un conseil technique, nous vous accompagnons.</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 lg:py-20 bg-stable-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-safety rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Prêt à trouver votre équipement ?</h2>
        <p class="text-stable-300 mb-8 max-w-xl mx-auto">Parcourez notre catalogue de matériel équestre et recevez vos articles sous 48 h partout en France.</p>
        <a href="{{ route('shop') }}" class="inline-flex items-center px-8 py-3.5 bg-safety hover:bg-safety-dark text-white font-semibold rounded-xl transition-all duration-300 shadow-lg shadow-safety/25 hover:shadow-safety/40 hover:-translate-y-0.5">
            Parcourez notre catalogue
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </div>
</section>

@endsection

