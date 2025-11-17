<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Produk') }}
            </h2>
            <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; Kembali ke Daftar Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    <!-- Grid Utama: Gambar di Kiri, Info di Kanan -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <!-- Kolom Gambar -->
                        <div class="md:col-span-1">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg shadow-md">
                                    <span class="text-gray-500">Tidak ada gambar</span>
                                </div>
                            @endif
                        </div>

                        <!-- Kolom Info Detail -->
                        <div class="md:col-span-2">
                            <h3 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 font-mono mt-1">SKU: {{ $product->sku }}</p>
                            
                            <p class="mt-4 text-gray-600">{{ $product->description }}</p>

                            <!-- Info Stok -->
                            <div class="mt-6">
                                @if($product->stock <= $product->min_stock)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        Stok Rendah: {{ $product->stock }} / {{ $product->min_stock }} {{ $product->unit }}
                                    </span>
                                @else
                                     <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Stok Aman: {{ $product->stock }} {{ $product->unit }}
                                    </span>
                                @endif
                            </div>

                            <!-- Detail Harga dan Atribut Lain -->
                            <div class="mt-6 border-t pt-6 grid grid-cols-2 gap-y-4 gap-x-8 text-sm">
                                <div>
                                    <dt class="font-medium text-gray-500">Kategori</dt>
                                    <dd class="mt-1 text-gray-900">{{ $product->category->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Lokasi Rak</dt>
                                    <dd class="mt-1 text-gray-900">{{ $product->rack_location ?: '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Harga Beli</dt>
                                    <dd class="mt-1 text-gray-900">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Harga Jual</dt>
                                    <dd class="mt-1 text-gray-900">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</dd>
                                </div>
                            </div>
                            
                            <!-- Tombol Aksi -->
                             <div class="mt-8 flex items-center gap-4">
                                <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-700 active:bg-blue-900">
                                    Edit Produk
                                </a>
                                @if(auth()->user()->role === 'manager' && $product->stock <= $product->min_stock)
                                    <a href="#" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        Buat Order Restock
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Transaksi Terakhir -->
                    <div class="mt-12 border-t pt-8">
                        <h4 class="text-lg font-medium text-gray-900">5 Transaksi Terakhir</h4>
                        <div class="mt-4 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">No. Transaksi</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tipe</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Jumlah</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @forelse ($product->transactionDetails as $detail)
                                            <tr>
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $detail->transaction->transaction_number }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    @if($detail->transaction->type === 'incoming')
                                                        <span class="text-green-600">Masuk</span>
                                                    @else
                                                        <span class="text-red-600">Keluar</span>
                                                    @endif
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $detail->quantity }} {{ $product->unit }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $detail->transaction->created_at->format('d M Y, H:i') }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="px-3 py-4 text-center text-sm text-gray-500">Belum ada riwayat transaksi.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>