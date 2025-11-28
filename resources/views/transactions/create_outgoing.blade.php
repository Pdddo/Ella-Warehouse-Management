<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Catat Barang Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifikasi Error --}}
            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl flex items-center gap-3 shadow-lg shadow-rose-500/10" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
                    <div class="p-2 bg-rose-500/20 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm">Gagal Menyimpan!</p>
                        <p class="text-xs opacity-90">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            {{-- Validasi Error --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl shadow-lg shadow-rose-500/10">
                    <div class="font-bold text-sm mb-2">Harap periksa inputan berikut:</div>
                    <ul class="list-disc list-inside text-xs opacity-90 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-8 relative overflow-hidden">
                
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rose-500 to-red-500"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

                <header class="mb-8 pb-4 border-b border-white/5 flex justify-between items-end">
                    <div>
                        <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            Outgoing Transaction
                        </h3>
                        <p class="text-slate-400 text-sm mt-1">Mengurangi stok barang (Penjualan/Pemakaian)</p>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="text-slate-400 hover:text-white text-sm">Batal</a>
                </header>

                <form method="POST" action="{{ route('transactions.store.outgoing') }}">
                    @csrf
                    <input type="hidden" name="type" value="outgoing">

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Customer / Tujuan</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-rose-500 focus:border-rose-500 placeholder-slate-600" placeholder="Nama penerima barang" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal</label>
                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-rose-500 focus:border-rose-500" required>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-rose-400 font-semibold mb-4 text-sm uppercase tracking-wider">Daftar Produk</h4>
                        
                        <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                            <div id="product-rows">
                                {{-- Default Row --}}
                                <div class="grid grid-cols-12 gap-4 mb-4 items-end row-item">
                                    <div class="col-span-8 md:col-span-6">
                                        <label class="block text-xs text-slate-400 mb-1">Produk</label>
                                        <select name="products[0][id]" class="w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-rose-500 focus:border-rose-500" required>
                                            <option value="">Pilih Produk</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} (Sisa: {{ $product->stock }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-4 md:col-span-3">
                                        <label class="block text-xs text-slate-400 mb-1">Jumlah</label>
                                        <input type="number" name="products[0][quantity]" min="1" class="w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-rose-500 focus:border-rose-500" placeholder="Qty" required>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" onclick="addProductRow()" class="mt-2 text-xs flex items-center gap-1 text-rose-400 hover:text-rose-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Baris Produk
                            </button>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-rose-500 focus:border-rose-500 placeholder-slate-600" placeholder="Alasan pengeluaran barang..."></textarea>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-white/5">
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-rose-600 to-red-600 text-white font-bold rounded-xl shadow-lg shadow-rose-500/20 hover:scale-[1.02] transition-all">
                            Simpan Transaksi Keluar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let rowCount = 1;

        function addProductRow() {
            const container = document.getElementById('product-rows');
            const firstRow = container.querySelector('.row-item');
            const newRow = firstRow.cloneNode(true);
            
            const select = newRow.querySelector('select');
            const input = newRow.querySelector('input');
            
            select.name = `products[${rowCount}][id]`;
            select.value = ''; 
            
            input.name = `products[${rowCount}][quantity]`;
            input.value = ''; 
            
            container.appendChild(newRow);
            rowCount++;
        }
    </script>
</x-app-layout>