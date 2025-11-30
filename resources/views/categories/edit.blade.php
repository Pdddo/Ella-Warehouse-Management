<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-8 relative overflow-hidden">

                <header class="mb-8 border-b border-white/5 pb-4">
                    <h3 class="text-xl font-bold text-white">Edit: {{ $category->name }}</h3>
                </header>

                <form method="POST" action="{{ route('categories.update', $category) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- input nama kategori --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama Kategori')" class="text-slate-300" />
                        <x-text-input id="name" class="block mt-1 w-full bg-[#0a0a0f]/50 border-white/10 text-white focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="text" name="name" :value="old('name', $category->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- input gambar --}}
                    <div>
                        <x-input-label for="image" :value="__('Gambar Kategori (Opsional)')" class="text-slate-300" />
                        <input type="file" id="image" name="image"
                            class="block mt-1 w-full text-slate-400 bg-[#0a0a0f]/50 border border-white/10 rounded-xl cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-500/10 file:text-emerald-400 hover:file:bg-emerald-500/20 transition-all">

                        @if($category->image)
                            <p class="text-xs text-slate-500 mt-2">Saat ini: {{ basename($category->image) }}</p>
                        @endif
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    {{-- input deskripsi kategori --}}
                    <div>
                        <x-input-label for="description" :value="__('Deskripsi (Opsional)')" class="text-slate-300" />
                        <textarea id="description" name="description" rows="4"
                            class="block mt-1 w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-white/5">
                        <a href="{{ route('categories.index') }}" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 font-medium rounded-xl transition-all shadow-lg shadow-emerald-500/10">
                            {{ __('Perbarui Kategori') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
