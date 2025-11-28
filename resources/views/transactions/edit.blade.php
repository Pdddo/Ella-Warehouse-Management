<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-8 relative overflow-hidden">
                
                @if($transaction->type == 'incoming')
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-green-500"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
                @else
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rose-500 to-red-500"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
                @endif

                <header class="mb-8 pb-4 border-b border-white/5 flex justify-between items-end">
                    <div>
                        <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                            @if($transaction->type == 'incoming')
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                Edit Barang Masuk
                            @else
                                <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                Edit Barang Keluar
                            @endif
                        </h3>
                        <p class="text-slate-400 text-sm mt-1 font-mono">#{{ $transaction->transaction_number }}</p>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="text-slate-400 hover:text-white text-sm">Batal</a>
                </header>

                <form method="POST" action="{{ route('transactions.update', $transaction) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-white/5 rounded-xl border border-white/5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                            <p class="text-white">{{ $transaction->created_at->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">
                                {{ $transaction->type == 'incoming' ? 'Supplier' : 'Customer / Tujuan' }}
                            </label>
                            <p class="text-white">
                                {{ $transaction->type == 'incoming' ? ($transaction->supplier->name ?? '-') : ($transaction->customer_name ?? '-') }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="{{ $transaction->type == 'incoming' ? 'text-emerald-400' : 'text-rose-400' }} font-semibold text-sm uppercase tracking-wider">
                                Daftar Produk
                            </h4>
                            <button type="button" onclick="addProductRow()" class="text-xs flex items-center gap-1 text-slate-400 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Baris
                            </button>
                        </div>
                        
                        <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                            <div id="product-rows">
                                {{-- Loop existing details --}}
                                @foreach($transaction->details as $index => $detail)
                                    <div class="grid grid-cols-12 gap-4 mb-4 items-end row-item relative group">
                                        <div class="col-span-7 md:col-span-6">
                                            <label class="block text-xs text-slate-400 mb-1">Produk</label>
                                            <select name="products[{{ $index }}][id]" class="w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-2 focus:border-transparent {{ $transaction->type == 'incoming' ? 'focus:ring-emerald-500' : 'focus:ring-rose-500' }}" required>
                                                <option value="">Pilih Produk</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} (Stok: {{ $product->stock }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-3 md:col-span-3">
                                            <label class="block text-xs text-slate-400 mb-1">Jumlah</label>
                                            <input type="number" name="products[{{ $index }}][quantity]" value="{{ $detail->quantity }}" min="1" class="w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-2 focus:border-transparent {{ $transaction->type == 'incoming' ? 'focus:ring-emerald-500' : 'focus:ring-rose-500' }}" placeholder="Qty" required>
                                        </div>
                                        <div class="col-span-2 md:col-span-3 flex items-center pb-2">
                                            <button type="button" onclick="removeRow(this)" class="text-rose-500 hover:text-rose-400 p-2 rounded-lg hover:bg-rose-500/10 transition-colors" title="Hapus Baris">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">Catatan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-2 focus:border-transparent placeholder-slate-600 {{ $transaction->type == 'incoming' ? 'focus:ring-emerald-500' : 'focus:ring-rose-500' }}">{{ old('notes', $transaction->notes) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-white/5 gap-4">
                         <a href="{{ route('transactions.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3 text-white font-bold rounded-xl shadow-lg hover:scale-[1.02] transition-all {{ $transaction->type == 'incoming' ? 'bg-gradient-to-r from-emerald-600 to-teal-600 shadow-emerald-500/20' : 'bg-gradient-to-r from-rose-600 to-red-600 shadow-rose-500/20' }}">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Set initial row count based on existing items
        let rowCount = {{ $transaction->details->count() }};

        function addProductRow() {
            const container = document.getElementById('product-rows');
            // Ambil row pertama sebagai template kloning
            // Jika row pertama terhapus, kita buat element baru manual atau pastikan minimal 1 row tersisa
            let templateRow = container.querySelector('.row-item');
            
            if (!templateRow) {
                // Fallback jika semua baris dihapus (sebaiknya dicegah, tapi buat jaga-jaga)
                location.reload(); 
                return; 
            }

            const newRow = templateRow.cloneNode(true);
            
            // Update name attributes
            const select = newRow.querySelector('select');
            const input = newRow.querySelector('input');
            
            select.name = `products[${rowCount}][id]`;
            select.value = ''; // Reset selection
            
            input.name = `products[${rowCount}][quantity]`;
            input.value = ''; // Reset quantity
            
            container.appendChild(newRow);
            rowCount++;
        }

        function removeRow(button) {
            const container = document.getElementById('product-rows');
            const rows = container.querySelectorAll('.row-item');
            
            if (rows.length > 1) {
                button.closest('.row-item').remove();
            } else {
                alert("Minimal harus ada satu produk.");
            }
        }
    </script>
</x-app-layout>