<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Buat Kategori Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-8 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-violet-600/10 rounded-full blur-[40px] pointer-events-none"></div>

                <header class="mb-8 border-b border-white/5 pb-4">
                    <h3 class="text-xl font-bold text-white">Detail Kategori</h3>
                    <p class="text-slate-400 text-sm">Tambahkan kategori baru untuk mengorganisir produk.</p>
                </header>

                {{-- PERUBAHAN 1: Tambahkan enctype="multipart/form-data" --}}
                <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <x-input-label for="name" :value="__('Nama Kategori')" class="text-slate-300" />
                        <x-text-input id="name" class="block mt-1 w-full bg-[#0a0a0f]/50 border-white/10 text-white focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="text" name="name" :value="old('name')" required autofocus placeholder="Contoh: Elektronik" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- PERUBAHAN 2: Input Gambar Kategori --}}
                    <div>
                        <x-input-label for="image" :value="__('Gambar Kategori (Opsional)')" class="text-slate-300" />
                        <input type="file" id="image" name="image" 
                            class="block mt-1 w-full text-slate-400 bg-[#0a0a0f]/50 border border-white/10 rounded-xl cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-500/10 file:text-emerald-400 hover:file:bg-emerald-500/20 transition-all">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Deskripsi (Opsional)')" class="text-slate-300" />
                        <textarea id="description" name="description" rows="4" 
                            class="block mt-1 w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all placeholder-slate-600" 
                            placeholder="Penjelasan singkat tentang kategori ini...">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-white/5">
                        <a href="{{ route('categories.index') }}" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-500/20 text-emerald-400 font-medium rounded-xl shadow-lg shadow-emerald-500/20 hover:scale-[1.02] transition-all">
                            {{ __('Simpan Kategori') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>