<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Transaksi Barang Masuk') }}
        </h2>
    </x-slot>

    {{-- Data dari PHP akan dimasukkan ke dalam elemen data tersembunyi, ini lebih bersih --}}
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

    {{-- x-data sekarang memanggil fungsi yang lebih bersih --}}
    <div class="py-12" x-data="incomingTransactionForm()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('transactions.store.incoming') }}">
                @csrf
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @include('partials.alert')

                        <!-- Info Utama Transaksi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="supplier_id" :value="__('Pilih Supplier')" />
                                {{-- x-model tetap di sini untuk mengikat nilai --}}
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

                        {{-- Panel Tambah Produk Manual disembunyikan jika form terisi otomatis --}}
                        <template x-if="!isPrefilled">
                            <div class="border-y py-6 my-6">
                                <h3 class="text-lg font-medium mb-2">Tambah Produk Manual</h3>
                                <!-- ... form tambah produk manual ... -->
                            </div>
                        </template>

                        <!-- Daftar Produk Transaksi -->
                        <h3 class="text-lg font-medium mb-2 mt-6">Daftar Produk Transaksi</h3>
                        <div class="relative overflow-x-auto rounded-lg border">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr><th class="px-6 py-3">Nama Produk</th><th class="px-6 py-3 text-right">Jumlah</th></tr>
                                </thead>
                                <tbody>
                                    {{-- Loop ini akan membuat input hidden secara otomatis --}}
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td class="px-6 py-4 border-b">
                                                <input type="hidden" :name="'products[' + index + '][id]'" :value="item.id">
                                                <span x-text="item.name"></span>
                                            </td>
                                            <td class="px-6 py-4 border-b text-right">
                                                <input type="hidden" :name="'products[' + index + '][quantity]'" :value="item.quantity">
                                                <span x-text="item.quantity"></span>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="items.length === 0">
                                        <tr><td colspan="2" class="text-center py-4 border-b">Belum ada produk.</td></tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('Catatan (Opsional)')" />
                            {{-- PERBAIKAN: Menggunakan x-text untuk mengisi, bukan x-model --}}
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" x-text="notes"></textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('transactions.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button type="submit" x-bind:disabled="items.length === 0">
                                Simpan Transaksi (Pending)
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    {{-- ========================================================== --}}
    {{-- BLOK SCRIPT YANG SEPENUHNYA DIPERBARUI --}}
    {{-- ========================================================== --}}
    <script>
        function incomingTransactionForm() {
            return {
                // Mendefinisikan semua properti state di awal
                selectedSupplier: '',
                items: [],
                notes: '',
                isPrefilled: false,

                // Fungsi init dipanggil secara otomatis oleh Alpine.js
                init() {
                    // 1. Ambil data JSON dari elemen script
                    const dataElement = document.getElementById('prefilled-data');
                    if (!dataElement || !dataElement.textContent.trim()) {
                        return;
                    }
                    
                    try {
                        const prefilledData = JSON.parse(dataElement.textContent);

                        // 2. Cek apakah data valid
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
            }
        }
    </script>
</x-app-layout>