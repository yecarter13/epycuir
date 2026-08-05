<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="format-detection" content="telephone=no">
    <title>@yield('title', 'Sellerie Super Confort — Équipement équestre de qualité, livré partout en France')</title>
    <meta name="description" content="@yield('meta_description', 'Sellerie Super Confort, votre sellerie en ligne : selles, brides, tapis, licols et équipement équestre. Livraison rapide en France.')">
    @hasSection('robots')<meta name="robots" content="@yield('robots')">@else<meta name="robots" content="index, follow">@endif
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    @yield('seo_head')

    @hasSection('og_title')
    <meta property="og:title" content="@yield('og_title')">
    <meta property="og:description" content="@yield('og_description')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('favicon.svg'))">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Sellerie Super Confort">
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('og_title')">
    <meta name="twitter:description" content="@yield('og_description')">
    <meta name="twitter:image" content="@yield('og_image', asset('favicon.svg'))">
    @endif

    @if(config('services.google.analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('services.google.analytics_id') }}');
    </script>
    @endif

    @if(config('services.google.search_console'))
    <meta name="google-site-verification" content="{{ config('services.google.search_console') }}">
    @endif

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Sellerie Super Confort",
        "url": "{{ url('/') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/shop') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Organization",
        "name": "Sellerie Super Confort",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('favicon.svg') }}",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+33-7-56-96-57-89",
            "contactType": "customer service",
            "availableLanguage": "French"
        },
        "sameAs": [
            "#",
            "#"
        ]
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-stable-900">

    @include('partials.header')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.floating-whatsapp')

    <div id="cart-toast" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 px-6 py-4 bg-stable-900 text-white text-base font-semibold rounded-xl shadow-2xl flex items-center gap-3 transition-all duration-300 opacity-0 -translate-y-4 pointer-events-none border border-green-500/30">
        <svg class="w-6 h-6 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span id="cart-toast-msg">Ajouté au panier</span>
    </div>

    <script>
    function addToCart(productId, qty, btn) {
        const toast = document.getElementById('cart-toast');
        const msg = document.getElementById('cart-toast-msg');
        btn = btn || { disabled: false, innerHTML: '' };
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>';
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: JSON.stringify({ product_id: productId, quantity: qty || 1 })
        })
        .then(r => r.json())
        .then(d => {
            document.querySelectorAll('.cart-count').forEach(el => el.textContent = d.count);
            msg.textContent = '✓ Ajouté au panier!';
            toast.classList.remove('opacity-0', '-translate-y-4', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                toast.classList.add('opacity-0', '-translate-y-4', 'pointer-events-none');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 2500);
        })
        .catch(() => {
            msg.textContent = "✕ Échec de l'ajout";
            toast.classList.remove('opacity-0', '-translate-y-4', 'pointer-events-none');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                toast.classList.add('opacity-0', '-translate-y-4', 'pointer-events-none');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 2500);
        })
        .finally(() => {
            if (btn) { btn.disabled = false; btn.innerHTML = 'Ajouter au panier'; }
        });
    }

    function initSearchSuggest(inputId, suggestId) {
        const input = document.getElementById(inputId);
        const box = document.getElementById(suggestId);
        if (!input || !box) return;
        let timer;

        input.addEventListener('input', function() {
            clearTimeout(timer);
            const q = this.value.trim();
            if (q.length < 2) { box.classList.add('hidden'); return; }
            box.innerHTML = '<div class="flex items-center justify-center gap-2 px-4 py-3 text-stable-400 text-sm"><svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Recherche...</div>';
            box.classList.remove('hidden');
            timer = setTimeout(() => {
                fetch('/shop/suggest?q=' + encodeURIComponent(q))
                    .then(r => r.json())
                    .then(d => {
                        if (!d.products?.length) {
                            box.innerHTML = '<div class="px-4 py-3 text-sm text-stable-400 text-center">Aucun produit trouvé — essayez une autre orthographe</div><a href="/shop?search=' + encodeURIComponent(q) + '" onmousedown="window.location=this.href" class="block px-3 py-2 text-center text-xs font-medium text-safety hover:bg-stable-50 transition-colors border-t border-stable-100">Voir tous les résultats →</a>';
                            box.classList.remove('hidden');
                            return;
                        }
                        let html = '';
                        if (d.products?.length) {
                            html += '<div class="px-3 py-2 text-[11px] font-semibold text-stable-400 uppercase tracking-wider bg-stable-50 border-t border-stable-100">Produits</div>';
                            d.products.forEach(p => {
                                html += '<a href="/product/' + p.slug + '" onmousedown="window.location=this.href" class="flex items-center gap-3 px-3 py-2 hover:bg-stable-50 transition-colors"><div class="w-8 h-8 bg-stable-100 rounded-lg flex-shrink-0 overflow-hidden"><img src="' + (p.image || 'https://images.unsplash.com/photo-1578643463396-0997cb5328c1?w=48&q=80') + '" alt="" class="w-full h-full object-cover"></div><div class="flex-1 min-w-0"><p class="text-sm font-medium text-stable-900 truncate">' + p.name + '</p><p class="text-xs text-stable-400">' + p.price + '</p></div></a>';
                            });
                        }
                        html += '<a href="/shop?search=' + encodeURIComponent(q) + '" class="block px-3 py-2 text-center text-xs font-medium text-safety hover:bg-stable-50 transition-colors border-t border-stable-100" onmousedown="window.location=this.href">Voir tous les résultats →</a>';
                        box.innerHTML = html;
                        box.classList.remove('hidden');
                    });
            }, 300);
        });
        input.addEventListener('blur', function() {
            setTimeout(() => box.classList.add('hidden'), 200);
        });
        input.addEventListener('focus', function() {
            if (box.children.length) box.classList.remove('hidden');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initSearchSuggest('search-desktop', 'suggest-desktop');
        initSearchSuggest('search-navbar-mobile', 'suggest-navbar-mobile');
        initSearchSuggest('search-hero', 'suggest-hero');
    });
    </script>

    @stack('scripts')
</body>
</html>
