<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin — Sellerie Epycuir')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #admin-sidebar {
            display: none;
        }
        #admin-sidebar.open {
            display: flex;
            transform: translateX(0);
        }
        @media (min-width: 1024px) {
            #admin-sidebar {
                display: flex !important;
                position: relative !important;
                transform: none !important;
            }
            #sidebar-overlay {
                display: none !important;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-stable-50">
    <div class="min-h-screen flex">
        <aside id="admin-sidebar" class="w-64 bg-stable-900 min-h-screen flex-shrink-0 fixed inset-y-0 left-0 z-50 flex-col transition-transform duration-300">
            <div class="p-5 border-b border-stable-700">
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-safety rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </div>
                        <span class="text-lg font-bold text-white whitespace-nowrap">Sellerie <span class="text-safety">Epycuir</span> <span class="text-xs text-stable-400 font-normal">Admin</span></span>
                    </a>
                    <button id="close-sidebar" class="lg:hidden text-stable-400 hover:text-white flex-shrink-0 ml-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <nav class="p-4 space-y-1 flex-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'text-white bg-stable-800' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Tableau de bord
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('admin.categories.*') ? 'text-white bg-stable-800' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Catégories
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('admin.products.*') ? 'text-white bg-stable-800' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Produits
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('admin.orders.*') ? 'text-white bg-stable-800' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Commandes
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('admin.users.*') ? 'text-white bg-stable-800' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Utilisateurs
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('admin.settings.*') ? 'text-white bg-stable-800' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Paramètres
                </a>
                <a href="{{ route('admin.mail.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-stable-200 hover:text-white hover:bg-stable-800 rounded-lg transition-all {{ request()->routeIs('admin.mail.*') ? 'text-white bg-stable-800' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Emails
                </a>
                <div class="border-t border-stable-700 my-3"></div>
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-stable-400 hover:text-white hover:bg-stable-800 rounded-lg transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Retour à la boutique
                </a>
            </nav>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden" onclick="closeSidebar()" style="display:none"></div>

        <main class="flex-1 min-w-0 lg:ml-0">
            <header class="bg-white border-b border-stable-100 px-4 sm:px-6 py-4 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-3 min-w-0">
                    <button id="open-sidebar" class="lg:hidden text-stable-600 hover:text-stable-900 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-lg sm:text-xl font-bold text-stable-900 truncate">@yield('title', 'Tableau de bord')</h1>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <span class="text-sm text-stable-500 hidden sm:inline">{{ auth()->user()?->name ?? 'Admin' }}</span>
                    @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-stable-400 hover:text-cta transition-colors">Déconnexion</button>
                    </form>
                    @endauth
                </div>
            </header>
            <div class="p-4 sm:p-6">
                @if(session('success'))
                <div class="mb-4 p-4 bg-stable-50 border border-stable-200 rounded-xl text-safety-dark text-sm">{{ session('success') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        document.getElementById('open-sidebar')?.addEventListener('click', function() {
            sidebar.classList.add('open');
            overlay.style.display = 'block';
        });

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.style.display = 'none';
        }

        document.getElementById('close-sidebar')?.addEventListener('click', closeSidebar);
    </script>
    @stack('scripts')
</body>
</html>
