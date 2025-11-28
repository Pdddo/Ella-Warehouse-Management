<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="relative overflow-hidden bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-violet-400" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7h-4V4c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H4c-1.103 0-2 .897-2 2v9a1 1 0 001 1h18a1 1 0 001-1V9c0-1.103-.897-2-2-2zM4 11h4v8H4v-8zm6-1V4h4v15h-4v-9zm10 9h-4V11h4v8z"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400">Total Produk</h3>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalProducts }}</p>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-[#56a09f]"></div>
                </div>

                <div class="relative overflow-hidden bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                         <svg class="w-16 h-16 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zM4 18V6h16l.002 12H4z"></path><path d="M6 10h12v2H6zm0 4h8v2H6z"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400">Transaksi Bulan Ini</h3>
                    <p class="text-3xl font-bold text-white mt-2">{{ $transactionsThisMonth }}</p>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-[#56a09f]"></div>
                </div>

                <div class="relative overflow-hidden bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg class="w-16 h-16 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 14H4V6h16v12z"></path><path d="M6 10h12v2H6zm0 4h12v2H6z"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400">Total Nilai Inventori</h3>
                    <p class="text-3xl font-bold text-white mt-2 font-mono">Rp {{ number_format($totalStockValue) }}</p>
                    <p class="text-xs text-slate-500 mt-1">Estimasi nilai aset saat ini</p>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-[#56a09f]"></div>
                </div>
            </div>

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mb-8 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Aksez Cepat
                </h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-white border border-white/10 rounded-xl text-sm font-medium transition-all hover:scale-105">Manajemen Produk</a>
                    <a href="{{ route('categories.index') }}" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-white border border-white/10 rounded-xl text-sm font-medium transition-all hover:scale-105">Manajemen Kategori</a>
                    <a href="{{ route('transactions.index') }}" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-white border border-white/10 rounded-xl text-sm font-medium transition-all hover:scale-105">Lihat Transaksi</a>
                    <a href="{{ route('restock-orders.index') }}" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-white border border-white/10 rounded-xl text-sm font-medium transition-all hover:scale-105">Order Restock</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-lg h-full">
                    <h3 class="text-lg font-bold text-rose-400 mb-4 flex items-center gap-2">
                        Peringatan Stok Rendah
                    </h3>
                    @if($lowStockProducts->isEmpty())
                        <div class="flex items-center gap-3 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400">
                            <p>Semua stok produk aman.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-white/5">
                            @foreach ($lowStockProducts as $product)
                                <li class="py-3 flex justify-between items-center group">
                                    <div>
                                        <p class="font-semibold text-white group-hover:text-rose-300 transition-colors">{{ $product->name }}</p>
                                        <p class="text-sm text-slate-500">Sisa: <span class="font-bold text-rose-500">{{ $product->stock }}</span> / Min: {{ $product->min_stock }}</p>
                                    </div>
                                    <a href="{{ route('products.show', $product) }}" class="px-3 py-1 bg-white/5 hover:bg-white/10 text-xs rounded-lg text-slate-300 transition-colors">Detail</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-lg h-full">
                    <h3 class="text-lg font-bold text-white mb-4">Menunggu Persetujuan Supplier</h3>
                    
                    @if($pendingSuppliers->isEmpty())
                        <p class="text-slate-500 text-sm">Tidak ada pendaftar supplier baru.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="text-xs text-slate-400 uppercase bg-white/5">
                                    <tr>
                                        <th class="px-4 py-3 rounded-l-lg">Nama</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3 rounded-r-lg text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($pendingSuppliers as $supplier)
                                        <tr class="hover:bg-white/5 transition-colors">
                                            <td class="px-4 py-3 text-white font-medium">{{ $supplier->name }}</td>
                                            <td class="px-4 py-3 text-slate-400">{{ $supplier->email }}</td>
                                            <td class="px-4 py-3 text-right">
                                                <form action="{{ route('admin.suppliers.approve', $supplier->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 border border-blue-500/20 rounded-lg text-xs font-bold transition-all hover:scale-105" onclick="return confirm('Setujui akun supplier ini?')">
                                                        Approve
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>