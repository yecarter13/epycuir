<header class="sticky top-0 z-50 bg-stable-900 shadow-xl border-b border-stable-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14 lg:h-20">

            <div class="flex items-center gap-1 sm:gap-2 shrink-0 min-w-0">
                <a href="{{ route('home') }}" class="flex items-center gap-1.5 sm:gap-2.5 group">
                    <div class="w-8 h-8 bg-safety rounded-lg hidden sm:flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-300">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <span class="text-sm sm:text-lg md:text-xl font-bold text-white tracking-tight whitespace-nowrap">Sellerie <span class="text-safety">Epycuir</span></span>
                </a>

                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-3 py-2 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all duration-200 {{ request()->routeIs('home') ? 'text-white bg-stable-800' : '' }}">Accueil</a>
                    <a href="{{ route('shop') }}" class="px-3 py-2 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all duration-200 {{ request()->routeIs('shop') ? 'text-white bg-stable-800' : '' }}">Boutique</a>
                    <a href="{{ route('about') }}" class="px-3 py-2 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all duration-200 {{ request()->routeIs('about') ? 'text-white bg-stable-800' : '' }}">À propos</a>
                    <a href="{{ route('contact') }}" class="px-3 py-2 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all duration-200 {{ request()->routeIs('contact') ? 'text-white bg-stable-800' : '' }}">Contact</a>
                </nav>
            </div>

            <div class="hidden md:flex items-center flex-1 max-w-md mx-4 lg:mx-10">
                <form action="{{ route('shop') }}" method="GET" class="relative w-full" autocomplete="off">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Recherchez une selle, une bride, un tapis..."
                           class="w-full pl-10 pr-4 py-2 bg-stable-800 border border-stable-600 rounded-lg text-sm text-white placeholder-stable-400 focus:outline-none focus:border-safety focus:ring-1 focus:ring-safety transition-all duration-200" id="search-desktop">
                    <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2">
                        <svg class="w-4 h-4 text-stable-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    <div id="suggest-desktop" class="absolute top-full left-0 right-0 mt-1 bg-white rounded-xl shadow-xl border border-stable-100 overflow-hidden hidden z-50"></div>
                </form>
            </div>

            <div class="flex md:hidden flex-1 min-w-0 mx-1">
                <form action="{{ route('shop') }}" method="GET" class="relative w-full" autocomplete="off">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
                           class="w-full pl-6 pr-1 py-1 bg-stable-800 border border-stable-600 rounded-lg text-[10px] leading-tight text-white placeholder-stable-400 focus:outline-none focus:border-safety transition-all" id="search-navbar-mobile">
                    <button type="submit" class="absolute left-1 top-1/2 -translate-y-1/2">
                        <svg class="w-3 h-3 text-stable-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    <div id="suggest-navbar-mobile" class="fixed top-14 left-0 right-0 bg-white rounded-none shadow-xl border-b border-stable-100 hidden z-50"></div>
                </form>
            </div>

            <div class="flex items-center gap-0.5 sm:gap-2">
                <a href="{{ route('cart') }}" class="relative p-2 text-stable-300 hover:text-white hover:bg-stable-800 rounded-lg transition-all duration-200" title="Panier">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-safety rounded-full text-[10px] font-bold text-white flex items-center justify-center cart-count">{{ $globalCartCount }}</span>
                </a>

                @auth
                <a href="{{ route('admin.dashboard') }}" class="hidden sm:flex p-2 text-stable-300 hover:text-white hover:bg-stable-800 rounded-lg transition-all duration-200" title="Administration">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hidden sm:flex p-2 text-stable-300 hover:text-white hover:bg-stable-800 rounded-lg transition-all duration-200" title="Déconnexion">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                @else
                <a href="{{ route('login') }}" class="hidden sm:flex p-2 text-stable-300 hover:text-white hover:bg-stable-800 rounded-lg transition-all duration-200" title="Connexion">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                </a>
                @endauth

                <button type="button" id="mobile-menu-toggle" class="lg:hidden p-2 text-stable-300 hover:text-white hover:bg-stable-800 rounded-lg transition-all duration-200" title="Menu" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="lg:hidden hidden pb-4 border-t border-stable-700 mt-2 pt-4">
            <nav class="flex flex-col gap-1">
                <a href="{{ route('home') }}" class="px-4 py-3 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('home') ? 'text-white bg-stable-800' : '' }}">Accueil</a>
                <a href="{{ route('shop') }}" class="px-4 py-3 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('shop') ? 'text-white bg-stable-800' : '' }}">Boutique</a>
                <a href="{{ route('about') }}" class="px-4 py-3 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('about') ? 'text-white bg-stable-800' : '' }}">À propos</a>
                <a href="{{ route('contact') }}" class="px-4 py-3 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('contact') ? 'text-white bg-stable-800' : '' }}">Contact</a>
                @auth
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-3 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all">Tableau de bord</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="px-4 py-3 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all">Déconnexion</a>
                @else
                <a href="{{ route('login') }}" class="px-4 py-3 text-sm font-medium text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all">Connexion</a>
                @endauth
            </nav>
        </div>
    </div>
</header>
