<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Catat Barang Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-8 relative overflow-hidden">
                
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-green-500"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

                <header class="mb-8 pb-4 border-b border-white/5 flex justify-between items-end">
                    <div>
                        <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            Incoming Transaction
                        </h3>
                        <p class="text-slate-400 text-sm mt-1">Menambah stok barang ke dalam gudang</p>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="text-slate-400 hover:text-white text-sm">Batal</a>
                </header>

                <form method="POST" action="{{ route('transactions.store.incoming') }}">
                    @csrf
                    <input type="hidden" name="type" value="incoming">

                    {{-- [FIX] Logika Auto-Fill Restock Order --}}
                    @if(isset($prefilledOrder))
                        <input type="hidden" name="restock_order_id" value="{{ $prefilledOrder->id }}">
                        
                        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p class="text-emerald-400 text-sm font-bold">Auto-fill dari PO: {{ $prefilledOrder->po_number }}</p>
                                <p class="text-slate-400 text-xs">Supplier dan daftar produk telah diisi otomatis berdasarkan pesanan restock.</p>
                            </div>
                        </div>
                    @endif

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Supplier</label>
                        <select name="supplier_id" class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500" required>
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" 
                                    {{-- Auto-select supplier --}}
                                    {{ (old('supplier_id') == $supplier->id) || (isset($prefilledOrder) && $prefilledOrder->supplier_id == $supplier->id) ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal</label>
                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-emerald-400 font-semibold mb-4 text-sm uppercase tracking-wider">Daftar Produk</h4>
                        
                        <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                            <div id="product-rows">
                                {{-- [FIX] Loop produk dari prefilledOrder jika ada --}}
                                @if(isset($prefilledOrder) && $prefilledOrder->details->count() > 0)
                                    @foreach($prefilledOrder->details as $index => $detail)
                                        <div class="grid grid-cols-12 gap-4 mb-4 items-end row-item">
                                            <div class="col-span-8 md:col-span-6">
                                                <label class="block text-xs text-slate-400 mb-1">Produk</label>
                                                <select name="products[{{ $index }}][id]" class="w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500" required>
                                                    <option value="">Pilih Produk</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ $product->id == $detail->product_id ? 'selected' : '' }}>
                                                            {{ $product->name }} (Stok: {{ $product->stock }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-span-4 md:col-span-3">
                                                <label class="block text-xs text-slate-400 mb-1">Jumlah</label>
                                                <input type="number" name="products[{{ $index }}][quantity]" value="{{ $detail->quantity }}" min="1" class="w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500" placeholder="Qty" required>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Default Empty Row --}}
                                    <div class="grid grid-cols-12 gap-4 mb-4 items-end row-item">
                                        <div class="col-span-8 md:col-span-6">
                                            <label class="block text-xs text-slate-400 mb-1">Produk</label>
                                            <select name="products[0][id]" class="w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500" required>
                                                <option value="">Pilih Produk</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-4 md:col-span-3">
                                            <label class="block text-xs text-slate-400 mb-1">Jumlah</label>
                                            <input type="number" name="products[0][quantity]" min="1" class="w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500" placeholder="Qty" required>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="button" onclick="addProductRow()" class="mt-2 text-xs flex items-center gap-1 text-emerald-400 hover:text-emerald-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Baris Produk
                            </button>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500 placeholder-slate-600" placeholder="Keterangan tambahan transaksi...">{{ isset($prefilledOrder) ? 'Transaksi dari PO: ' . $prefilledOrder->po_number : '' }}</textarea>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-white/5">
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 hover:scale-[1.02] transition-all">
                            Simpan Transaksi Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Set rowCount agar tidak menimpa baris yang sudah ada jika dari prefilled
        let rowCount = {{ isset($prefilledOrder) ? $prefilledOrder->details->count() : 1 }};

        function addProductRow() {
            const container = document.getElementById('product-rows');
            // Selalu clone baris pertama sebagai template
            const firstRow = container.querySelector('.row-item');
            const newRow = firstRow.cloneNode(true);
            
            // Update name attributes untuk array binding
            const select = newRow.querySelector('select');
            const input = newRow.querySelector('input');
            
            select.name = `products[${rowCount}][id]`;
            select.value = ''; // Reset nilai agar kosong
            
            input.name = `products[${rowCount}][quantity]`;
            input.value = ''; // Reset nilai agar kosong
            
            container.appendChild(newRow);
            rowCount++;
        }
    </script>
</x-app-layout>