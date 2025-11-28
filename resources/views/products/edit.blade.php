<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-8 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/20 rounded-full blur-[50px] pointer-events-none"></div>

                <header class="mb-8 border-b border-white/5 pb-4">
                    <h3 class="text-xl font-bold text-white">Edit Produk: {{ $product->name }}</h3>
                    <p class="text-slate-400 text-sm">Update informasi untuk produk ini.</p>
                </header>

                <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        

                        <div class="col-span-2">
                            <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nama Produk</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" 
                                class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-slate-600 px-4 py-2.5 transition-all"
                                required>
                            @error('name') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-slate-300 mb-2">Kategori</label>
                            <select id="category_id" name="category_id" 
                                class="w-full bg-[#0a0a0f]/50 border border-white/10 text-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent px-4 py-2.5 transition-all">
                                <option value="" class="bg-[#0a0a0f] text-slate-500">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }} class="bg-[#0a0a0f]">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Input Harga Beli --}}
                        <div>
                            <label for="buy_price" class="block text-sm font-medium text-slate-300 mb-2">Harga Beli (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-slate-500">Rp</span>
                                <input type="number" id="buy_price" name="buy_price" value="{{ old('buy_price', $product->buy_price) }}" 
                                    class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent pl-10 px-4 py-2.5 transition-all"
                                    required>
                            </div>
                            @error('buy_price') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Input Harga Jual --}}
                        <div>
                            <label for="sell_price" class="block text-sm font-medium text-slate-300 mb-2">Harga Jual (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-2.5 text-slate-500">Rp</span>
                                <input type="number" id="sell_price" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}" 
                                    class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent pl-10 px-4 py-2.5 transition-all"
                                    required>
                            </div>
                            @error('sell_price') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-slate-300 mb-2">Stok Saat Ini</label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" 
                                class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent px-4 py-2.5 transition-all"
                                required>
                            @error('stock') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Input Min Stock --}}
                        <div>
                            <label for="min_stock" class="block text-sm font-medium text-slate-300 mb-2">Min. Stock Alert</label>
                            <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" 
                                class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent px-4 py-2.5 transition-all"
                                required>
                            @error('min_stock') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Input Unit --}}
                        <div>
                            <label for="unit" class="block text-sm font-medium text-slate-300 mb-2">Unit</label>
                            <input type="text" id="unit" name="unit" value="{{ old('unit', $product->unit) }}" 
                                class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent px-4 py-2.5 transition-all"
                                placeholder="e.g. Pcs, Box, Kg" required>
                            @error('unit') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Input Lokasi Rak --}}
                        <div>
                            <label for="rack_location" class="block text-sm font-medium text-slate-300 mb-2">Lokasi di Rak</label>
                            <input type="text" id="rack_location" name="rack_location" value="{{ old('rack_location', $product->rack_location) }}" 
                                class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent px-4 py-2.5 transition-all"
                                placeholder="e.g. Rak A-05" required>
                            @error('rack_location') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="image" class="block text-sm font-medium text-slate-300 mb-2">Update Gambar</label>
                            <input type="file" id="image" name="image" 
                                class="w-full text-slate-400 bg-[#0a0a0f]/50 border border-white/10 rounded-xl cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-500/10 file:text-emerald-400 hover:file:bg-emerald-500/20">
                            @if($product->image)
                                <p class="text-xs text-slate-500 mt-2">Current: {{ basename($product->image) }}</p>
                            @endif
                            @error('image') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium text-slate-300 mb-2">Deskripsi</label>
                            <textarea id="description" name="description" rows="4" 
                                class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent px-4 py-2.5 transition-all">{{ old('description', $product->description) }}</textarea>
                        </div>

                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-white/5">
                        <a href="{{ route('products.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-400 hover:text-white transition-colors">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-500/20 text-emerald-500 font-medium rounded-xl shadow-lg hover:shadow-emerald-500/40 hover:scale-[1.02] transition-all">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>