<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Products Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- tampilkan Notifikasi Error/Sukses --}}
            @include('partials.alert')

            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-white">Semua Produk</h3>
                    <p class="text-slate-400 text-sm">Kelola Persenjataan Anda</p>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    
                    <form method="GET" action="{{ route('products.index') }}" class="w-full md:w-auto flex flex-col md:flex-row items-center gap-3">
                    
                        {{-- Filter Kategori --}}
                        <select name="category" class="w-full md:w-32 py-2.5 px-3 bg-[#0a0a0f]/50 border border-white/10 rounded-xl text-xs text-slate-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        {{-- Filter Status Stok --}}
                        <select name="stock_status" class="w-full md:w-32 py-2.5 px-3 bg-[#0a0a0f]/50 border border-white/10 rounded-xl text-xs text-slate-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Status</option>
                            <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Habis</option>
                        </select>

                        {{-- Sorting --}}
                        <select name="sort" class="w-full md:w-32 py-2.5 px-3 bg-[#0a0a0f]/50 border border-white/10 rounded-xl text-xs text-slate-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Sort By</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stok Terendah</option>
                            <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stok Tertinggi</option>
                        </select>

                        {{-- Search Input --}}
                        <div class="relative w-full md:w-48">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Cari Nama/SKU..." 
                                class="w-full pl-9 pr-4 py-2.5 bg-[#0a0a0f]/50 border border-white/10 rounded-xl text-xs text-slate-300 focus:ring-emerald-500 focus:border-emerald-500 placeholder-slate-600">
                            <svg class="w-4 h-4 text-slate-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>

                        {{-- Submit Filter --}}
                        <button type="submit" class="p-2.5 bg-white/5 hover:bg-white/10 text-slate-300 rounded-xl border border-white/10 transition-colors" title="Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </button>
                    </form>

                    <a href="{{ route('products.create') }}" class="px-5 py-2.5 bg-emerald-500/20 text-emerald-400 font-medium rounded-xl shadow-lg flex items-center gap-2 whitespace-nowrap hover:bg-emerald-500/30 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Produk
                    </a>
                </div>
            </div>

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden shadow-2xl relative">
                
                <div class="absolute top-0 right-0 w-64 h-64 bg-violet-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/5">
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-slate-300">
                            @forelse ($products as $product)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-lg bg-[#0a0a0f] border border-white/10 flex items-center justify-center overflow-hidden">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" class="h-full w-full object-cover" alt="">
                                            @else
                                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-medium text-white">{{ $product->name }}</div>
                                            <div class="text-xs text-slate-500">SKU: {{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-800 text-slate-300 border border-white/5">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                
                                </td>
                                
                                {{-- Data Unit --}}
                                <td class="px-6 py-4 whitespace-nowrap text-slate-400 text-sm">
                                    {{ $product->unit }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap font-mono text-emerald-400">
                                    Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->stock > 10)
                                        <span class="px-2 py-1 text-xs rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                            Low: {{ $product->stock }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('products.show', $product) }}" class="text-slate-400 hover:text-violet-400 transition-colors" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        
                                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                                            <a href="{{ route('products.edit', $product) }}" class="text-slate-400 hover:text-blue-400 transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-rose-400 transition-colors" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p>Tidak ada produk yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="px-6 py-4 border-t border-white/5 bg-white/5">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>