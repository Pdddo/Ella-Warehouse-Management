<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Ella WMS') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-[#08080c] text-slate-300 selection:bg-violet-500/30 overflow-x-hidden">

    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-[#56a09f]/10 rounded-full blur-[120px]"></div>
        <div class="absolute top-[40%] right-[-10%] w-[500px] h-[500px] bg-[#56a09f]/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[600px] h-[600px] bg-[#56a09f]/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col">
        
        <nav class="w-full px-6 py-6 flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <img src="{{ asset('storage/images/ela.png') }}" alt="Ella WMS Logo" class="w-12 h-12" />
                <span class="text-xl font-bold text-white tracking-tight">Ella</span>
            </div>

            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-white/10 hover:bg-white/15 border border-white/10 rounded-xl transition-all">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors px-4">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-[#56a09f] hover:bg-emerald-500 rounded-xl shadow-lg shadow-emerald-500/25 transition-all hover:scale-105">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <main class="flex-grow flex items-center justify-center px-6">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[#56a09f] text-sm font-medium mb-6 animate-fade-in">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Armory Management System
                </div>
                
                <h1 class="text-5xl md:text-7xl font-bold text-white tracking-tight mb-6 leading-tight">
                    Modern Armory <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#56a09f] via-[#56a09f] to-emerald-200">Management System</span>
                </h1>
                
                <p class="text-lg text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Pantau stok senjata, kelola transaksi masuk & keluar, serta atur pemesanan ulang ke supplier dalam satu platform yang cepat, aman, dan efisien.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-[#08080c] font-bold rounded-xl hover:bg-slate-200 transition-all shadow-[0_0_40px_-10px_rgba(255,255,255,0.3)] flex items-center justify-center gap-2">
                        Try it Now
                    </a>
                </div>
            </div>
        </main>

        <footer class="py-8 text-center text-slate-500 text-sm">
            <p>&copy; {{ date('Y') }} Ella Armory Management System. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>