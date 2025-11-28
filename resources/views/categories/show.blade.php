<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Detail Kategori: {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('categories.index') }}" class="inline-flex items-center text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
                <a href="{{ route('categories.edit', $category) }}" class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-sm font-medium hover:bg-white/10 transition-colors">
                    Edit Kategori
                </a>
            </div>

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mb-8 shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $category->name }}</h3>
                        <p class="text-slate-400 mt-1">{{ $category->description ?: 'Tidak ada deskripsi khusus untuk kategori ini.' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-white/5 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Produk dalam Kategori Ini</h3>
                    <span class="px-3 py-1 rounded-full bg-white/5 text-xs text-slate-300 border border-white/10">{{ $products->total() }} Item</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-slate-400 uppercase bg-white/5">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Produk</th>
                                <th scope="col" class="px-6 py-3">SKU</th>
                                <th scope="col" class="px-6 py-3">Stok</th>
                                <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-slate-300">
                            @forelse ($products as $product)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 font-medium text-white">{{ $product->name }}</td>
                                <td class="px-6 py-4 font-mono text-slate-400">{{ $product->sku ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-md {{ $product->stock > 10 ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }} text-xs font-semibold">
                                        {{ $product->stock }} {{ $product->unit ?? 'Unit' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('products.show', $product) }}" class="text-violet-400 hover:text-violet-300 hover:underline">Lihat Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                    Belum ada produk di dalam kategori ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($products->hasPages())
                <div class="px-6 py-4 border-t border-white/5">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>