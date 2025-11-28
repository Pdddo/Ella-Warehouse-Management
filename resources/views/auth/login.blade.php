<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white">Selamat Datang Kembali</h2>
        <p class="text-slate-400 text-sm mt-2">Masuk untuk mengelola gudang persenjataan</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-300" />
            <x-text-input id="email" class="block mt-1 w-full bg-[#0a0a0f]/50 border border-white/10 text-white focus:border-[#56a09f] focus:ring-[#56a09f] rounded-xl py-2.5 px-4 placeholder-slate-600" 
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-slate-300" />

            <x-text-input id="password" class="block mt-1 w-full bg-[#0a0a0f]/50 border border-white/10 text-white focus:border-[#56a09f] focus:ring-[#56a09f] rounded-xl py-2.5 px-4 placeholder-slate-600"
                            type="password"
                            name="password"
                            required autocomplete="current-password" 
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-white/10 bg-[#0a0a0f] text-emerald-600 shadow-sm focus:ring-[#56a09f]" name="remember">
                <span class="ms-2 text-sm text-slate-400">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-[#56a09f] hover:text-emerald-300 hover:underline transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full py-3 px-4 bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-300transition-all hover:scale-[1.02] active:scale-95">
                {{ __('Log in') }}
            </button>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-[#56a09f] hover:text-emerald-300 font-medium transition-colors">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>