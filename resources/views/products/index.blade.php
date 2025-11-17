<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Tombol dan Form Pencarian -->
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Tambah Produk
                        </a>
                        <form method="GET" action="{{ route('products.index') }}">
                            <div class="flex">
                                <x-text-input type="text" name="search" placeholder="Cari Nama/SKU..." :value="request('search')" />
                                <x-primary-button class="ms-3">
                                    Cari
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Notifikasi -->
                    @include('partials.alert')

                    <!-- Tabel Produk -->
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">SKU</th>
                                    <th scope="col" class="px-6 py-3">Nama Produk</th>
                                    <th scope="col" class="px-6 py-3">Kategori</th>
                                    <th scope="col" class="px-6 py-3">Stok</th>
                                    <th scope="col" class="px-6 py-3">Lokasi Rak</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono text-xs">{{ $product->sku }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $product->name }}</td>
                                    <td class="px-6 py-4">{{ $product->category->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold">{{ $product->stock }}</span> {{ $product->unit }}
                                        @if($product->stock <= $product->min_stock)
                                            <span class="bg-red-100 text-red-800 text-xs font-medium ms-2 px-2.5 py-0.5 rounded-full">Stok Rendah</span>
                                        @else
                                             <span class="bg-green-100 text-green-800 text-xs font-medium ms-2 px-2.5 py-0.5 rounded-full">Aman</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $product->rack_location }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('products.show', $product) }}" class="font-medium text-green-600 hover:underline">Lihat</a>
                                        <a href="{{ route('products.edit', $product) }}" class="font-medium text-blue-600 hover:underline ms-3">Edit</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:underline ms-3">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data produk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>