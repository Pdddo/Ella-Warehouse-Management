<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ella WMS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .text-teal-custom { color: #56a09f; }
        .bg-teal-custom { background-color: #56a09f; }
        .border-teal-custom { border-color: #56a09f; }
        .text-sage-custom { color: #9fb9a6; }
        .bg-sage-custom { background-color: #9fb9a6; }
        .border-sage-custom { border-color: #9fb9a6; }

        /* Gradients */
        .bg-gradient-custom { background: linear-gradient(135deg, #56a09f 0%, #4a8e8d 100%); }
        .selection-custom::selection { background-color: rgba(86, 160, 159, 0.3); color: #fff; }
    </style>
</head>
<body class="font-sans antialiased bg-[#050a0a] text-[#e0e8e8] overflow-hidden selection-custom">

    <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#56a09f]/10 rounded-full blur-[120px] pointer-events-none z-0"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-[#9fb9a6]/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <div x-data="{ mobileOpen: false }" class="relative z-10 flex h-screen overflow-hidden">

        <div x-show="mobileOpen"
             @click="mobileOpen = false"
             class="fixed inset-0 z-40 bg-black/80 backdrop-blur-sm lg:hidden"
             style="display: none;">
        </div>

        <aside :class="mobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:static top-0 left-0 z-50 h-full w-72 flex-shrink-0 bg-[#0b1414]/90 backdrop-blur-xl border-r border-[#9fb9a6]/10 flex flex-col p-6 transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none">

            <div class="flex items-center gap-3 mb-10 px-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-custom flex items-center justify-center shadow-lg shadow-[#56a09f]/20 text-white">
                    <img src="{{ asset('storage/images/ela.png') }}" alt="Ella WMS Logo" class="w-20 h-22" />
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Ella</h1>
                    <p class="text-[10px] text-sage-custom uppercase tracking-widest">Armory Management</p>
                </div>
            </div>

            <nav class="flex-grow space-y-1">
                @php
                    // Ambil role user yang sedang login
                    $userRole = Auth::user()->role;

                    $menus = [
                        [
                            'route' => 'dashboard',
                            'label' => 'Dashboard',
                            'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
                            'allowed_roles' => ['admin', 'manager', 'staff', 'supplier'] // Semua boleh lihat dashboard
                        ],
                        [
                            'route' => 'products.index',
                            'label' => 'Products',
                            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                            'allowed_roles' => ['admin', 'manager'] // Hanya Admin & Manager
                        ],
                        [
                            'route' => 'categories.index',
                            'label' => 'Categories',
                            'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
                            'allowed_roles' => ['admin', 'manager'] // Hanya Admin & Manager
                        ],
                        [
                            'route' => 'transactions.index',
                            'label' => 'Transactions',
                            'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
                            'allowed_roles' => ['admin', 'manager', 'staff'] // Admin, Manager, Staff (Supplier tidak boleh)
                        ],
                        [
                            'route' => 'restock-orders.index',
                            'label' => 'Restock Orders',
                            'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
                            'allowed_roles' => ['admin', 'manager'] // Hanya Admin & Manager
                        ],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    {{-- Cek apakah role user ada di dalam allowed_roles --}}
                    @if(in_array($userRole, $menu['allowed_roles']))
                        <a href="{{ route($menu['route']) }}"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group relative overflow-hidden
                        {{ request()->routeIs($menu['route'].'*')
                                ? 'bg-[#56a09f]/10 text-[#56a09f] border border-[#56a09f]/20 shadow-[0_0_20px_-10px_rgba(86,160,159,0.3)]'
                                : 'text-[#9fb9a6] hover:bg-white/[0.03] hover:text-white border border-transparent' }}">

                            <svg class="w-5 h-5 transition-colors {{ request()->routeIs($menu['route'].'*') ? 'text-[#56a09f]' : 'text-[#9fb9a6] group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $menu['icon'] }}"></path>
                            </svg>

                            <span class="font-medium text-sm tracking-wide">{{ $menu['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="mt-auto pt-6 border-t border-[#9fb9a6]/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-white/[0.03] border border-transparent hover:border-white/[0.05] transition-all group text-left">
                        <div class="w-9 h-9 rounded-full border border-[#56a09f]/30 p-[2px]">
                            <div class="w-full h-full rounded-full bg-[#0b1414] flex items-center justify-center font-medium text-xs text-[#56a09f]">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="overflow-hidden flex-1">
                            <p class="font-medium text-sm text-white truncate group-hover:text-[#56a09f] transition-colors">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-[10px] text-sage-custom truncate uppercase tracking-wider">{{ Auth::user()->role }}</p>
                        </div>
                        <svg class="w-4 h-4 text-[#9fb9a6] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden relative">
            <header class="h-16 flex items-center justify-between px-6 lg:px-8 border-b border-[#9fb9a6]/10 bg-[#050a0a]/50 backdrop-blur-md sticky top-0 z-30">
                <button @click="mobileOpen = true" class="lg:hidden p-2 text-[#9fb9a6] hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div class="hidden lg:flex items-center text-xs font-medium text-[#9fb9a6]">
                    <span>App</span>
                    <svg class="w-3 h-3 mx-2 text-[#56a09f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-white">
                        @if(isset($header)) {{ $header }} @else Dashboard @endif
                    </span>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-[#0b1414] border border-[#56a09f]/20 rounded-full">
                         <span class="w-2 h-2 rounded-full bg-[#56a09f] shadow-[0_0_10px_#56a09f]"></span>
                         <span class="text-xs text-[#56a09f] font-mono">Hai, Selamat Datang {{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 lg:p-8 scrollbar-hide">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
