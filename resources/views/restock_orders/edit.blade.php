<x-app-layout>
    {{-- PERBAIKAN 1: Memaksa load Alpine.js agar tombol PASTI berfungsi --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Restock Order') }}: {{ $restockOrder->po_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Form Wrapper dengan x-data --}}
            {{-- Kita inisialisasi data kosong dulu di sini, nanti diisi oleh Script di bawah --}}
            <div x-data="restockEditHandler()" x-init="initData()">
                
                <form method="POST" action="{{ route('restock-orders.update', $restockOrder->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 md:p-8 text-gray-900">
                            
                            {{-- Tampilkan Error Validasi --}}
                            @if ($errors->any())
                                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                    <strong class="font-bold">Periksa input Anda!</strong>
                                    <ul class="mt-1 list-disc list-inside text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div>
                                    <x-input-label for="supplier_id" :value="__('Supplier')" />
                                    <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" 
                                                {{ old('supplier_id', $restockOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="order_date" :value="__('Tanggal Order')" />
                                    <input type="date" id="order_date" name="order_date" 
                                           value="{{ old('order_date', $restockOrder->order_date->format('Y-m-d')) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                                           required x-ref="orderDateInput"> 
                                </div>

                                <div>
                                    <x-input-label for="expected_delivery_date" :value="__('Perkiraan Tiba')" />
                                    <input type="date" id="expected_delivery_date" name="expected_delivery_date"
                                           value="{{ old('expected_delivery_date', optional($restockOrder->expected_delivery_date)->format('Y-m-d')) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                           required :min="$refs.orderDateInput.value">
                                </div>
                            </div>

                            <div class="border-t border-b border-gray-200 py-6 my-6 bg-gray-50 p-4 rounded">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Produk</h3>
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                    
                                    <div class="md:col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Pilih Produk</label>
                                        {{-- x-model menyimpan ID produk yang dipilih sementara --}}
                                        <select x-model="tempProductId" id="productSelector" class="block w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-name="{{ $product->name }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="md:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                        <input type="number" x-model.number="tempQuantity" min="1" class="block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>

                                    <div class="md:col-span-3">
                                        <button type="button" @click="addItem()" 
                                                class="w-full px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                            + Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 mb-2">Daftar Item Saat Ini</h3>
                            <div class="relative overflow-x-auto border rounded-lg mb-6">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-3">Nama Produk</th>
                                            <th class="px-6 py-3 text-center">Qty</th>
                                            <th class="px-6 py-3 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Loop Item menggunakan Alpine --}}
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <td class="px-6 py-4 font-medium text-gray-900">
                                                    {{-- Hidden Input agar data terkirim ke Controller --}}
                                                    <input type="hidden" :name="'products[' + index + '][id]'" :value="item.id">
                                                    <span x-text="item.name"></span>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="hidden" :name="'products[' + index + '][quantity]'" :value="item.quantity">
                                                    <span x-text="item.quantity" class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded"></span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <button type="button" @click="removeItem(index)" class="text-red-600 hover:underline font-bold">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                        
                                        <template x-if="items.length === 0">
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center text-gray-400">
                                                    List kosong. Silakan tambah produk di atas.
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mb-6">
                                <x-input-label for="notes" :value="__('Catatan')" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes', $restockOrder->notes) }}</textarea>
                            </div>
                            
                            <div class="flex items-center justify-end gap-4 border-t pt-6">
                                <a href="{{ route('restock-orders.index') }}" class="text-gray-600 underline">Batal</a>
                                <x-primary-button>Simpan Perubahan</x-primary-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- PERBAIKAN 2: Memisahkan Data PHP dari HTML Attribute agar tidak error --}}
    <script>
        // Kita siapkan data dari PHP di sini
        const existingItems = @json($restockOrder->details->map(fn($detail) => [
            'id' => $detail->product_id,
            'name' => $detail->product->name,
            'quantity' => $detail->quantity
        ]));

        function restockEditHandler() {
            return {
                items: [],
                tempProductId: '',
                tempQuantity: 1,

                // Fungsi inisialisasi dipanggil saat komponen dimuat
                initData() {
                    this.items = existingItems;
                },

                addItem() {
                    if (!this.tempProductId) {
                        alert('Pilih produk dulu!');
                        return;
                    }
                    if (this.tempQuantity < 1) {
                        alert('Jumlah minimal 1');
                        return;
                    }

                    // Cek Duplikasi
                    let exists = this.items.find(i => i.id == this.tempProductId);
                    if (exists) {
                        alert('Produk sudah ada di list. Hapus dulu jika ingin ubah jumlah.');
                        return;
                    }

                    // Ambil Nama Produk dari Select Option
                    let select = document.getElementById('productSelector');
                    let name = select.options[select.selectedIndex].getAttribute('data-name');

                    this.items.push({
                        id: this.tempProductId,
                        name: name,
                        quantity: this.tempQuantity
                    });

                    // Reset input
                    this.tempProductId = '';
                    this.tempQuantity = 1;
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout>