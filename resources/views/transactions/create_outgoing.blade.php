<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Transaksi Barang Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="transactionForm()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('transactions.store.outgoing') }}">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @include('partials.alert')

                        <!-- Nama Pelanggan -->
                         <div>
                            <x-input-label for="customer_name" :value="__('Nama Pelanggan')" />
                            <x-text-input id="customer_name" class="block mt-1 w-full" type="text" name="customer_name" :value="old('customer_name')" required />
                            <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
                        </div>
                        
                        <!-- Form Tambah Produk -->
                        <div class="border-b pb-6 mb-6 mt-6">
                            <h3 class="text-lg font-medium mb-2">Tambah Produk</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div class="col-span-2">
                                    <label for="product_id" class="block text-sm font-medium text-gray-700">Pilih Produk</label>
                                    <select x-model="selectedProduct" id="product_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-unit="{{ $product->unit }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="number" x-model.number="quantity" min="1" id="quantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <button type="button" @click="addProduct()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500">Tambah</button>
                            </div>
                        </div>

                        <!-- Daftar Produk yang Ditambahkan -->
                        <h3 class="text-lg font-medium mb-2">Daftar Barang Keluar</h3>
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">Nama Produk</th>
                                        <th class="px-6 py-3">Jumlah</th>
                                        <th class="px-6 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td class="px-6 py-4">
                                                <input type="hidden" :name="'products[' + index + '][id]'" :value="item.id">
                                                <span x-text="item.name"></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="hidden" :name="'products[' + index + '][quantity]'" :value="item.quantity">
                                                <span x-text="item.quantity + ' ' + item.unit"></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <button type="button" @click="removeItem(index)" class="text-red-600 hover:underline">Hapus</button>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="items.length === 0">
                                        <tr><td colspan="3" class="text-center py-4">Belum ada produk yang ditambahkan.</td></tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Catatan & Tombol Simpan -->
                        <div class="mt-6 border-t pt-6">
                            <div>
                                <x-input-label for="notes" :value="__('Catatan (Opsional)')" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <a href="{{ route('transactions.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                                <x-primary-button type="submit" x-bind:disabled="items.length === 0">
                                    Simpan Transaksi
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function transactionForm() {
            return {
                selectedProduct: '',
                quantity: 1,
                items: [],
                addProduct() {
                    if (!this.selectedProduct || this.quantity <= 0) {
                        alert('Silakan pilih produk dan isi jumlah dengan benar.');
                        return;
                    }

                    const selectElement = document.getElementById('product_id');
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const productName = selectedOption.getAttribute('data-name');
                    const productUnit = selectedOption.getAttribute('data-unit');
                    
                    const existingItem = this.items.find(item => item.id == this.selectedProduct);
                    if(existingItem) {
                        existingItem.quantity += this.quantity;
                    } else {
                        this.items.push({
                            id: this.selectedProduct,
                            name: productName,
                            quantity: this.quantity,
                            unit: productUnit
                        });
                    }

                    this.selectedProduct = '';
                    this.quantity = 1;
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout>