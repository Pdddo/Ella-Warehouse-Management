<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-slate-400 hover:text-white mb-6 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-3xl shadow-2xl overflow-hidden">
                <div class="md:flex">
                    
                    <div class="md:w-1/3 bg-black/20 p-8 flex items-center justify-center border-r border-white/5">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="rounded-xl shadow-lg max-h-80 object-cover" alt="{{ $product->name }}">
                        @else
                            <div class="h-64 w-64 rounded-2xl bg-white/5 flex items-center justify-center">
                                <svg class="w-20 h-20 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-8 md:w-2/3">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="px-3 py-1 rounded-full bg-violet-500/10 text-violet-400 border border-violet-500/20 text-xs font-semibold uppercase tracking-wider mb-2 inline-block">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                                <h1 class="text-3xl font-bold text-white mb-1">{{ $product->name }}</h1>
                                <p class="text-slate-400 text-sm font-mono tracking-wide">SKU: {{ $product->sku }}</p>
                            </div>
                            
                            {{-- Stok Utama Badge --}}
                            <div class="text-right">
                                <span class="px-4 py-2 rounded-xl text-sm font-bold border {{ $product->stock > $product->min_stock ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20' }}">
                                    {{ $product->stock }} {{ $product->unit }} Available
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mt-8 p-6 bg-white/5 rounded-2xl border border-white/5">
                            
                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Harga Jual</p>
                                <p class="text-2xl font-mono text-emerald-400 font-bold">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Harga Beli</p>
                                <p class="text-xl font-mono text-slate-300">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Satuan Unit</p>
                                <p class="text-lg text-white capitalize">{{ $product->unit }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Lokasi Gudang</p>
                                <div class="flex items-center gap-2 text-lg text-white">
                                    <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $product->rack_location }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/5">
                            <h4 class="text-sm font-semibold text-slate-300 mb-2">Deskripsi</h4>
                            <p class="text-slate-400 leading-relaxed text-sm">
                                {{ $product->description ?: 'Tidak ada deskripsi yang disediakan untuk produk ini.' }}
                            </p>
                        </div>

                        {{-- [BARU] Riwayat 5 Transaksi Terakhir --}}
                        <div class="mt-8 pt-6 border-t border-white/5">
                            <h4 class="text-sm font-semibold text-slate-300 mb-4">Riwayat 5 Transaksi Terakhir</h4>
                            <div class="overflow-hidden rounded-xl border border-white/10">
                                <table class="w-full text-left text-sm text-slate-400">
                                    <thead class="bg-white/5 text-xs uppercase font-bold text-slate-300">
                                        <tr>
                                            <th class="px-4 py-3">Tanggal</th>
                                            <th class="px-4 py-3">Tipe</th>
                                            <th class="px-4 py-3 text-right">Jumlah</th>
                                            <th class="px-4 py-3 text-right">Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        {{-- Mengambil data relasi transactionDetails, load parent transaction, urutkan, ambil 5 --}}
                                        @forelse($product->transactionDetails()->with('transaction.user')->latest()->take(5)->get() as $detail)
                                            <tr class="hover:bg-white/5 transition-colors">
                                                <td class="px-4 py-3">
                                                    {{ $detail->transaction->created_at->format('d M Y') }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($detail->transaction->type == 'incoming')
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                            MASUK
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                            KELUAR
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-right font-mono text-white">
                                                    {{ $detail->quantity }}
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    {{ $detail->transaction->user->name ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-4 py-6 text-center text-xs italic text-slate-500">
                                                    Belum ada transaksi untuk produk ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Riwayat Transaksi --}}

                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                        <div class="mt-8 flex gap-4">
                            <a href="{{ route('products.edit', $product) }}" class="flex-1 text-center px-4 py-3 bg-emerald-500/20 text-emerald-400 rounded-xl shadow-lg transition-all font-bold text-sm">
                                Edit Data Product
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>