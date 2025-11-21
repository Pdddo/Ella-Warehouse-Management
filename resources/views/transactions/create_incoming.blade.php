<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Transaksi Barang Masuk') }}
        </h2>
    </x-slot>

    {{-- Data JSON untuk Pre-fill --}}
    <script id="prefilled-data" type="application/json">
        @if(isset($prefilledOrder))
            {!! json_encode([
                'supplier_id' => $prefilledOrder->supplier_id,
                'notes' => 'Berdasarkan Restock Order ' . $prefilledOrder->po_number,
                'products' => $prefilledOrder->details->map(fn($detail) => [
                    'id' => $detail->product_id,
                    'name' => $detail->product->name,
                    'quantity' => $detail->quantity
                ])
            ]) !!}
        @else
            null
        @endif
    </script>

    <div class="py-12" x-data="incomingTransactionForm()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('transactions.store.incoming') }}">
                @csrf
                
                {{-- Input Hidden untuk menandai Restock Order sebagai selesai (Poin 2) --}}
                @if(isset($prefilledOrder))
                    <input type="hidden" name="restock_order_id" value="{{ $prefilledOrder->id }}">
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @include('partials.alert')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="supplier_id" :value="__('Pilih Supplier')" />
                                <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" x-model="selectedSupplier" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="transaction_date" :value="__('Tanggal Transaksi')" />
                                <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                        </div>

                        <div class="border-y py-6 my-6 bg-gray-50 p-4 rounded-md">
                            <h3 class="text-lg font-medium mb-4 text-gray-800">Tambah Produk ke Daftar</h3>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                {{-- Select Produk --}}
                                <div class="md:col-span-7">
                                    <x-input-label :value="__('Produk')" />
                                    <select x-model="newProduct.id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-name="{{ $product->name }}">
                                                {{ $product->name }} (Stok: {{ $product->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                {{-- Input Qty --}}
                                <div class="md:col-span-3">
                                    <x-input-label :value="__('Jumlah')" />
                                    <input type="number" x-model="newProduct.quantity" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                                </div>

                                {{-- Tombol Tambah --}}
                                <div class="md:col-span-2">
                                    <button type="button" @click="addProduct()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium mb-2 mt-6">Daftar Produk Transaksi</h3>
                        <div class="relative overflow-x-auto rounded-lg border">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">Nama Produk</th>
                                        <th class="px-6 py-3 text-right">Jumlah</th>
                                        <th class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td class="px-6 py-4 border-b">
                                                <input type="hidden" :name="'products[' + index + '][id]'" :value="item.id">
                                                <span x-text="item.name" class="font-medium text-gray-900"></span>
                                            </td>
                                            <td class="px-6 py-4 border-b text-right">
                                                <input type="hidden" :name="'products[' + index + '][quantity]'" :value="item.quantity">
                                                <span x-text="item.quantity"></span>
                                            </td>
                                            <td class="px-6 py-4 border-b text-center">
                                                <button type="button" @click="removeProduct(index)" class="text-red-600 hover:text-red-900 font-bold text-xs uppercase tracking-wider">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="items.length === 0">
                                        <tr><td colspan="3" class="text-center py-4 border-b bg-gray-50 italic">Belum ada produk dalam daftar.</td></tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('Catatan (Opsional)')" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" x-text="notes"></textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('transactions.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button type="submit" x-bind:disabled="items.length === 0">
                                Simpan Transaksi
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function incomingTransactionForm() {
            return {
                selectedSupplier: '',
                items: [],
                notes: '',
                isPrefilled: false,
                // State untuk input manual
                newProduct: { id: '', quantity: 1 },

                init() {
                    const dataElement = document.getElementById('prefilled-data');
                    if (!dataElement || !dataElement.textContent.trim()) {
                        return;
                    }
                    
                    try {
                        const prefilledData = JSON.parse(dataElement.textContent);
                        if (prefilledData && prefilledData.products) {
                            this.isPrefilled = true;
                            this.selectedSupplier = prefilledData.supplier_id;
                            this.items = prefilledData.products;
                            this.notes = prefilledData.notes;
                        }
                    } catch (e) {
                        console.error('Gagal mem-parsing data pre-fill:', e);
                    }
                },

                addProduct() {
                    if (!this.newProduct.id || this.newProduct.quantity < 1) {
                        alert('Pilih produk dan masukkan jumlah yang valid.');
                        return;
                    }

                    // Cari nama produk dari elemen select (agar bisa ditampilkan di tabel)
                    let select = document.querySelector('select[x-model="newProduct.id"]');
                    let name = select.options[select.selectedIndex].getAttribute('data-name') || 'Produk';

                    // Cek duplikasi: jika produk sudah ada, tambahkan quantity-nya
                    let existing = this.items.find(i => i.id == this.newProduct.id);
                    if (existing) {
                        existing.quantity = parseInt(existing.quantity) + parseInt(this.newProduct.quantity);
                    } else {
                        this.items.push({
                            id: this.newProduct.id,
                            name: name,
                            quantity: this.newProduct.quantity
                        });
                    }

                    // Reset input manual
                    this.newProduct.id = '';
                    this.newProduct.quantity = 1;
                },

                removeProduct(index) {
                    this.items.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout>