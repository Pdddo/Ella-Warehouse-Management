<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-300 antialiased bg-[#08080c] selection:bg-emerald-500/30 overflow-x-hidden">
        
        <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] bg-emerald-600/20 rounded-full blur-[120px] pointer-events-none z-0"></div>
        <div class="fixed bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-emerald-600/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
            <div>
                <a href="/" class="flex flex-col items-center gap-2 group">
                    <div class="flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300 mt-5">
                        <img src="{{ asset('storage/images/ela.png') }}" alt="Ella WMS Logo" class="w-20 h-20" />
                    </div>
                    <span class="text-xl font-bold text-white tracking-tight mt-2">Ella Warehouse Management System</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/10 shadow-2xl sm:rounded-2xl overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent opacity-50"></div>
                
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} Ella Warehouse Management System.
            </div>
        </div>
    </body>
</html>