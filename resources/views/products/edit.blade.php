<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk: ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Method spoofing untuk request UPDATE -->

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kolom Kiri -->
                            <div>
                                <!-- Nama Produk -->
                                <div>
                                    <x-input-label for="name" :value="__('Nama Produk')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <!-- Kategori -->
                                <div class="mt-4">
                                    <x-input-label for="category_id" :value="__('Kategori')" />
                                    <select name="category_id" id="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                </div>
                                <!-- Harga Beli & Harga Jual -->
                                <div class="mt-4 grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="buy_price" :value="__('Harga Beli (Rp)')" />
                                        <x-text-input id="buy_price" class="block mt-1 w-full" type="number" step="0.01" name="buy_price" :value="old('buy_price', $product->buy_price)" required />
                                        <x-input-error :messages="$errors->get('buy_price')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="sell_price" :value="__('Harga Jual (Rp)')" />
                                        <x-text-input id="sell_price" class="block mt-1 w-full" type="number" step="0.01" name="sell_price" :value="old('sell_price', $product->sell_price)" required />
                                        <x-input-error :messages="$errors->get('sell_price')" class="mt-2" />
                                    </div>
                                </div>
                                <!-- Deskripsi -->
                                <div class="mt-4">
                                    <x-input-label for="description" :value="__('Deskripsi')" />
                                    <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>
                            <!-- Kolom Kanan -->
                            <div>
                                <!-- SKU (Read-only) -->
                                <div>
                                    <x-input-label for="sku" :value="__('SKU (Tidak dapat diubah)')" />
                                    <x-text-input id="sku" class="block mt-1 w-full bg-gray-100" type="text" name="sku" :value="$product->sku" disabled />
                                </div>
                                <!-- Stok & Stok Minimum -->
                                <div class="mt-4 grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="stock" :value="__('Stok Saat Ini')" />
                                        <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock', $product->stock)" required />
                                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="min_stock" :value="__('Stok Minimum')" />
                                        <x-text-input id="min_stock" class="block mt-1 w-full" type="number" name="min_stock" :value="old('min_stock', $product->min_stock)" required />
                                        <x-input-error :messages="$errors->get('min_stock')" class="mt-2" />
                                    </div>
                                </div>
                                <!-- Unit & Lokasi Rak -->
                                <div class="mt-4 grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="unit" :value="__('Unit (pcs, box, dll)')" />
                                        <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit" :value="old('unit', $product->unit)" required />
                                        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="rack_location" :value="__('Lokasi Rak')" />
                                        <x-text-input id="rack_location" class="block mt-1 w-full" type="text" name="rack_location" :value="old('rack_location', $product->rack_location)" />
                                        <x-input-error :messages="$errors->get('rack_location')" class="mt-2" />
                                    </div>
                                </div>
                                <!-- Gambar Produk -->
                                <div class="mt-4">
                                    <x-input-label for="image" :value="__('Ganti Gambar Produk (Opsional)')" />
                                    <input type="file" name="image" id="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                    <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengganti gambar.</p>
                                    @if($product->image)
                                        <p class="mt-2 text-sm text-gray-600">Gambar saat ini: <a href="{{ asset('storage/' . $product->image) }}" target="_blank" class="text-blue-600 hover:underline">Lihat</a></p>
                                    @endif
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center justify-end mt-6 border-t pt-6">
                            <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>
                                {{ __('Update Produk') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>