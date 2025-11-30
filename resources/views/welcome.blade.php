<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Ella WMS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&family=jetbrains-mono:400,500&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-[#08080c] text-slate-300 h-screen overflow-hidden flex flex-col">

    <div class="fixed inset-0 pointer-events-none z-0 flex items-center justify-center">
        <div class="w-[800px] h-[800px] bg-[#56a09f]/5 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 flex-grow flex flex-col justify-center items-center px-6">

        <div class="mb-12 animate-fade-in-down">
            <div class="w-16 h-16 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center mx-auto shadow-[0_0_30px_-10px_rgba(86,160,159,0.3)]">
                 <img src="{{ asset('storage/images/ela.png') }}" alt="Logo" class="w-10 h-10 object-contain" />
            </div>
        </div>

        <div class="text-center max-w-2xl mx-auto space-y-8">


            <h1 class="text-5xl md:text-7xl font-bold text-white tracking-tight leading-tight">
                Ella<br>
                <span class="text-slate-500">Armory Management</span>
            </h1>

            <p class="text-slate-400 text-lg md:text-xl font-light leading-relaxed max-w-lg mx-auto">
                Atur logistik senjata anda. <br class="hidden md:block">
                Sederhana, aman, dan mudah digunakan.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="group relative inline-flex items-center gap-2 text-white font-medium hover:text-[#56a09f] transition-colors">
                            <span>Access Dashboard</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="min-w-[160px] px-6 py-3 bg-[#56a09f] hover:bg-[#4a8f8e] text-white font-semibold rounded-lg transition-all shadow-lg shadow-[#56a09f]/20 hover:scale-105">
                            Log In
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="min-w-[160px] px-6 py-3 bg-transparent border border-white/10 hover:border-[#56a09f]/50 text-slate-300 hover:text-white rounded-lg transition-all">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>

    <footer class="relative z-10 py-6 text-center">
        <p class="text-xs text-slate-600 font-mono tracking-wide uppercase">
            &copy; {{ date('Y') }} Ella Systems. Restricted Access.
        </p>
    </footer>

</body>
</html>
