<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Order Restock Baru') }}
        </h2>
    </x-slot>

    {{-- Perhatikan x-data di sini, kita menambahkan orderDate --}}
    <div class="py-12" x-data="restockOrderForm()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('restock-orders.store') }}">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @include('partials.alert')

                        {{-- PERUBAHAN: GRID MENJADI 3 KOLOM --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="supplier_id" :value="__('Pilih Supplier')" />
                                <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- FITUR YANG DIKEMBALIKAN: INPUT TANGGAL ORDER --}}
                            <div>
                                <x-input-label for="order_date" :value="__('Tanggal Order')" />
                                <input type="date" name="order_date" id="order_date" 
                                       x-model="orderDate" {{-- Diikat ke Alpine.js --}}
                                       value="{{ old('order_date', date('Y-m-d')) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <x-input-label for="expected_delivery_date" :value="__('Ekspektasi Tanggal Tiba')" />
                                <input type="date" name="expected_delivery_date" id="expected_delivery_date" 
                                       :min="orderDate" {{-- Atribut 'min' dinamis berdasarkan Tanggal Order --}}
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                        </div>

                        <!-- Form Tambah Produk (Tetap sama seperti sebelumnya) -->
                        <div class="border-y py-6 my-6">
                            <h3 class="text-lg font-medium mb-2">Tambah Produk</h3>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Pilih Produk</label>
                                    <select x-model="selectedProductId" @change="updateUnit()" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-unit="{{ $product->unit }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="number" x-model.number="quantity" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                                    <input type="text" x-model="unit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50" placeholder="e.g., pcs, kg">
                                </div>
                                <button type="button" @click="addProduct()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 h-10">Tambah</button>
                            </div>
                        </div>

                        <!-- Daftar Produk & Sisa Form (Tetap sama) -->
                        <h3 class="text-lg font-medium mb-2">Daftar Produk Order</h3>
                        <table class="w-full text-sm text-left text-gray-500 mb-6">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Nama Produk</th><th class="px-6 py-3">Jumlah</th><th class="px-6 py-3">Unit</th><th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr>
                                        <td class="px-6 py-4 border-b"><input type="hidden" :name="'products[' + index + '][id]'" :value="item.id"><span x-text="item.name"></span></td>
                                        <td class="px-6 py-4 border-b"><input type="hidden" :name="'products[' + index + '][quantity]'" :value="item.quantity"><span x-text="item.quantity"></span></td>
                                        <td class="px-6 py-4 border-b"><input type="hidden" :name="'products[' + index + '][unit]'" :value="item.unit"><span x-text="item.unit"></span></td>
                                        <td class="px-6 py-4 border-b text-right"><button type="button" @click="removeItem(index)" class="text-red-600 hover:underline">Hapus</button></td>
                                    </tr>
                                </template>
                                <template x-if="items.length === 0">
                                    <tr><td colspan="4" class="text-center py-4 border-b">Belum ada produk ditambahkan.</td></tr>
                                </template>
                            </tbody>
                        </table>
                        
                        <div>
                            <x-input-label for="notes" :value="__('Catatan (Opsional)')" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('restock-orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button type="submit" x-bind:disabled="items.length === 0">
                                Simpan Order
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    {{-- PERUBAHAN PADA SCRIPT ALPINE.JS --}}
    <script>
        function restockOrderForm() {
            return {
                // DATA BARU UNTUK TANGGAL ORDER
                orderDate: '{{ old('order_date', date('Y-m-d')) }}',
                selectedProductId: '',
                quantity: 1,
                unit: '',
                items: [],

                updateUnit() {
                    if (!this.selectedProductId) {
                        this.unit = '';
                        return;
                    }
                    const selectedOption = document.querySelector(`select[x-model='selectedProductId'] option[value='${this.selectedProductId}']`);
                    this.unit = selectedOption.dataset.unit;
                },

                addProduct() {
                    if (!this.selectedProductId || this.quantity <= 0 || !this.unit) {
                        alert('Silakan pilih produk, isi jumlah, dan unit.');
                        return;
                    }
                    const selectedOption = document.querySelector(`select[x-model='selectedProductId'] option[value='${this.selectedProductId}']`);
                    this.items.push({
                        id: this.selectedProductId,
                        name: selectedOption.dataset.name,
                        quantity: this.quantity,
                        unit: this.unit,
                    });
                    this.selectedProductId = '';
                    this.quantity = 1;
                    this.unit = '';
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout>