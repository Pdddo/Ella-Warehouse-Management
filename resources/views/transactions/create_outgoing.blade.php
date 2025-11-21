<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catat Barang Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="outgoingTransactionForm()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('transactions.store.outgoing') }}">
                @csrf

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        
                        {{-- Tampilkan Error Validasi --}}
                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                                <p class="font-bold">Gagal Menyimpan Transaksi!</p>
                                <ul class="mt-1 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="customer_name" :value="__('Nama Customer / Tujuan')" />
                                <x-text-input id="customer_name" class="block mt-1 w-full" type="text" name="customer_name" :value="old('customer_name')" placeholder="Contoh: Toko Sejahtera Abadi" required />
                                <p class="mt-1 text-xs text-gray-500">Nama penerima barang atau tujuan pengiriman.</p>
                            </div>
                            <div>
                                <x-input-label for="transaction_date" :value="__('Tanggal Pengiriman')" />
                                <input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>

                        {{-- Panel Tambah Produk --}}
                        <div class="border-y py-6 my-6 bg-red-50 p-4 rounded-md border-red-100">
                            <h3 class="text-lg font-medium mb-4 text-red-800">Pilih Barang Keluar</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                {{-- Select Produk --}}
                                <div class="md:col-span-7">
                                    <x-input-label :value="__('Produk (Stok Tersedia)')" />
                                    <select x-model="newProduct.id" @change="updateMaxStock()" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-500">
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" 
                                                    data-name="{{ $product->name }}" 
                                                    data-stock="{{ $product->stock }}">
                                                {{ $product->name }} (Stok: {{ $product->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                {{-- Input Qty --}}
                                <div class="md:col-span-3">
                                    <x-input-label :value="__('Jumlah')" />
                                    <input type="number" x-model="newProduct.quantity" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-500" placeholder="Qty">
                                    {{-- Info Stok Realtime --}}
                                    <p x-show="selectedStock > 0" class="text-xs mt-1 text-gray-600">
                                        Maks: <span x-text="selectedStock" class="font-bold"></span>
                                    </p>
                                </div>

                                {{-- Tombol Tambah --}}
                                <div class="md:col-span-2">
                                    <button type="button" @click="addProduct()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-lg font-medium mb-2 mt-6 text-gray-800">Daftar Barang yang Akan Dikirim</h3>
                        <div class="relative overflow-x-auto rounded-lg border border-gray-200">
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
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <input type="hidden" :name="'products[' + index + '][id]'" :value="item.id">
                                                <span x-text="item.name" class="font-medium text-gray-900"></span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <input type="hidden" :name="'products[' + index + '][quantity]'" :value="item.quantity">
                                                <span x-text="item.quantity" class="font-mono text-gray-700"></span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <button type="button" @click="removeProduct(index)" class="font-medium text-red-600 hover:underline">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="items.length === 0">
                                        <tr>
                                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 italic bg-gray-50">
                                                Belum ada barang yang ditambahkan. Silakan pilih produk di atas.
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-100">
                            <a href="{{ route('transactions.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <x-primary-button type="submit" x-bind:disabled="items.length === 0" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                                {{ __('Simpan Transaksi Keluar') }}
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function outgoingTransactionForm() {
            return {
                items: [],
                newProduct: { id: '', quantity: '' },
                selectedStock: 0,

                // Fungsi untuk mengupdate info stok saat produk dipilih
                updateMaxStock() {
                    let select = document.querySelector('select[x-model="newProduct.id"]');
                    if (select.selectedIndex > 0) {
                        this.selectedStock = parseInt(select.options[select.selectedIndex].getAttribute('data-stock'));
                        // Reset quantity jika melebihi stok baru
                        if (this.newProduct.quantity > this.selectedStock) {
                            this.newProduct.quantity = this.selectedStock;
                        }
                    } else {
                        this.selectedStock = 0;
                        this.newProduct.quantity = '';
                    }
                },

                addProduct() {
                    // Validasi Input Dasar
                    if (!this.newProduct.id || this.newProduct.quantity < 1) {
                        alert('Mohon pilih produk dan masukkan jumlah yang valid.');
                        return;
                    }

                    // Validasi Stok (Client Side)
                    if (parseInt(this.newProduct.quantity) > this.selectedStock) {
                        alert('Jumlah melebihi stok yang tersedia! (Maks: ' + this.selectedStock + ')');
                        return;
                    }

                    // Ambil nama produk dari atribut data-name
                    let select = document.querySelector('select[x-model="newProduct.id"]');
                    let name = select.options[select.selectedIndex].getAttribute('data-name');

                    // Cek apakah produk sudah ada di list?
                    let existingItem = this.items.find(i => i.id == this.newProduct.id);
                    
                    if (existingItem) {
                        // Jika sudah ada, kita cek apakah totalnya nanti melebihi stok
                        let totalQty = parseInt(existingItem.quantity) + parseInt(this.newProduct.quantity);
                        if (totalQty > this.selectedStock) {
                            alert('Total jumlah barang ini di keranjang akan melebihi stok tersedia!');
                            return;
                        }
                        existingItem.quantity = totalQty;
                    } else {
                        // Jika belum ada, tambahkan baris baru
                        this.items.push({
                            id: this.newProduct.id,
                            name: name,
                            quantity: parseInt(this.newProduct.quantity)
                        });
                    }

                    // Reset Form Input
                    this.newProduct.id = '';
                    this.newProduct.quantity = '';
                    this.selectedStock = 0;
                },

                removeProduct(index) {
                    this.items.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout>