<footer class="bg-stable-950 border-t border-stable-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 mb-12">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 bg-safety rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight">Sellerie<span class="text-safety"> Super Confort</span></span>
                </a>
                <p class="text-stable-400 text-sm leading-relaxed mb-4">
                    Votre sellerie de confiance pour le matériel équestre de qualité. Plus de 20 ans de savoir-faire français au service des cavaliers.
                </p>
                @php
                    $socials = [
                        ['key' => 'facebook_url', 'label' => 'Facebook', 'icon' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
                        ['key' => 'twitter_url', 'label' => 'Twitter', 'icon' => 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z'],
                        ['key' => 'instagram_url', 'label' => 'Instagram', 'icon' => 'M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm4.441 16.892c-2.102.144-6.784.144-8.883 0C5.282 16.736 5.017 15.622 5 12c.017-3.629.285-4.736 2.558-4.892 2.099-.144 6.782-.144 8.883 0C18.718 7.264 18.983 8.378 19 12c-.017 3.629-.285 4.736-2.559 4.892zM10 9.658l4.917 2.338L10 14.342V9.658z'],
                        ['key' => 'tiktok_url', 'label' => 'TikTok', 'icon' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z'],
                    ];
                @endphp
                <div class="flex items-center gap-3">
                    @foreach($socials as $s)
                        @php $url = App\Models\SiteSetting::getValue($s['key']); @endphp
                        @if($url && $url !== '#')
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="w-9 h-9 bg-stable-800 hover:bg-safety rounded-lg flex items-center justify-center text-stable-400 hover:text-white transition-all duration-200" title="{{ $s['label'] }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $s['icon'] }}"/></svg>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Liens rapides</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">Accueil</a></li>
                    <li><a href="{{ route('shop') }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">Boutique</a></li>
                    <li><a href="{{ route('about') }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">À propos</a></li>
                    <li><a href="{{ route('contact') }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">Contact</a></li>
                    <li><a href="{{ route('categories.all') }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">Catégories</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Service client</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('delivery') }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">Livraison</a></li>
                    <li><a href="{{ route('returns') }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">Retours & remboursements</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">Confidentialité</a></li>
                    <li><a href="{{ route('terms') }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">Conditions générales</a></li>
                    <li><a href="{{ route('warranty') }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">Garantie</a></li>
                </ul>
            </div>

            @php
                $cAddress = App\Models\SiteSetting::getValue('address');
                $cPhone = App\Models\SiteSetting::getValue('phone');
                $cEmail = App\Models\SiteSetting::getValue('email');
                $cHours = App\Models\SiteSetting::getValue('opening_hours');
            @endphp
            @if($cAddress || $cPhone || $cEmail || $cHours)
            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Coordonnées</h3>
                <ul class="space-y-3">
                    @if($cAddress)
                    <li class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-safety mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-stable-400 text-sm">{{ $cAddress }}</span>
                    </li>
                    @endif
                    @if($cEmail)
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-safety flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:{{ $cEmail }}" class="text-stable-400 hover:text-safety text-sm transition-colors duration-200">{{ $cEmail }}</a>
                    </li>
                    @endif
                    @if($cPhone)
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-safety flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-stable-400 text-sm">{{ $cPhone }}</span>
                    </li>
                    @endif
                    @if($cHours)
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-safety flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-stable-400 text-sm">{{ $cHours }}</span>
                    </li>
                    @endif
                </ul>
            </div>
            @endif
        </div>

        {{-- Footer mobile simplifié : localisation + numéro uniquement --}}
        <div class="md:hidden mb-10">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-4">
                <div class="w-8 h-8 bg-safety rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-white tracking-tight">Sellerie<span class="text-safety"> Super Confort</span></span>
            </a>
            @if($cAddress)
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($cAddress) }}" target="_blank" rel="noopener" class="flex items-start gap-3 mb-3">
                <svg class="w-4 h-4 text-safety mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-stable-400 text-sm">{{ $cAddress }}</span>
            </a>
            @endif
            @if($cPhone)
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $cPhone) }}" class="flex items-center gap-3">
                <svg class="w-4 h-4 text-safety flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span class="text-stable-400 text-sm font-medium">{{ $cPhone }}</span>
            </a>
            @endif
        </div>

        <div class="hidden md:block border-t border-stable-800 pt-8 pb-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="max-w-xl">
                    <h3 class="text-white font-semibold text-lg mb-1">Restez informé</h3>
                    <p class="text-stable-400 text-sm">Recevez nos nouveautés, offres exclusives et conseils d'équitation directement dans votre boîte mail.</p>
                </div>
                <form action="#" method="GET" class="flex w-full max-w-md gap-2" autocomplete="off">
                    <input type="email" name="newsletter_email" placeholder="Votre adresse e-mail"
                           class="flex-1 px-4 py-2.5 bg-stable-900 border border-stable-700 rounded-lg text-sm text-white placeholder-stable-500 focus:outline-none focus:border-safety focus:ring-1 focus:ring-safety transition-all duration-200">
                    <button type="submit" class="px-5 py-2.5 bg-safety hover:bg-safety-dark text-white text-sm font-semibold rounded-lg transition-all duration-200 whitespace-nowrap">S'abonner</button>
                </form>
            </div>
        </div>

        <div class="border-t border-stable-800 pt-8">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="hidden md:flex flex-col items-center gap-4">
                    <span class="text-stable-500 text-xs uppercase tracking-wider font-medium">Nous acceptons</span>
                    <div class="flex items-center gap-3 flex-wrap justify-center">
                        <div class="h-9 px-3 bg-white rounded-lg flex items-center justify-center border border-stable-700 hover:border-safety/30 transition-all">
                            <img src="{{ asset('images/s-visa.2285dc84.svg') }}" alt="Visa" class="h-5" loading="lazy">
                        </div>
                        <div class="h-9 px-3 bg-white rounded-lg flex items-center justify-center border border-stable-700 hover:border-safety/30 transition-all">
                            <img src="{{ asset('images/s-mastercard.ebdcfa0e.svg') }}" alt="Mastercard" class="h-5" loading="lazy">
                        </div>
                        <div class="h-9 px-3 bg-white rounded-lg flex items-center justify-center border border-stable-700 hover:border-safety/30 transition-all">
                            <img src="{{ asset('images/s-amex.c961722f.svg') }}" alt="American Express" class="h-5" loading="lazy">
                        </div>
                        <div class="h-9 px-3 bg-white rounded-lg flex items-center justify-center border border-stable-700 hover:border-safety/30 transition-all">
                            <img src="{{ asset('images/s-paypal.2843dda3.svg') }}" alt="PayPal" class="h-5" loading="lazy">
                        </div>
                        <div class="h-9 px-3 bg-white rounded-lg flex items-center justify-center border border-stable-700 hover:border-safety/30 transition-all">
                            <img src="{{ asset('images/s-applepay.18b5e830.svg') }}" alt="Apple Pay" class="h-5" loading="lazy">
                        </div>
                        <div class="h-9 px-3 bg-white rounded-lg flex items-center justify-center border border-stable-700 hover:border-safety/30 transition-all">
                            <span class="text-[10px] font-bold text-stable-500 tracking-wider">KLARNA</span>
                        </div>
                    </div>
                </div>
                <p class="text-stable-600 text-xs">
                    &copy; {{ date('Y') }} Sellerie Super Confort. Tous droits réservés.
                </p>
            </div>
        </div>
    </div>
</footer>
