<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500">Total Produk</h3>
                    <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $totalProducts }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500">Transaksi Bulan Ini</h3>
                    <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $transactionsThisMonth }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500">Total Unit Inventori</h3>
                    <p class="text-3xl font-semibold text-gray-900 mt-2">{{ number_format($totalStockValue) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Nilai inventori aktual memerlukan data harga beli.</p>
                </div>
            </div>

            <!-- Quick Access -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Akses Cepat</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm">Manajemen Produk</a>
                    <a href="{{ route('categories.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm">Manajemen Kategori</a>
                    <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm">Lihat Transaksi</a>
                    <a href="{{ route('restock-orders.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm">Order Restock</a>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-red-600 mb-4">Peringatan Stok Rendah</h3>
                    @if($lowStockProducts->isEmpty())
                        <p>👍 Semua stok produk dalam kondisi aman.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($lowStockProducts as $product)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-600">Stok saat ini: <span class="font-bold text-red-500">{{ $product->stock }}</span> (Minimum: {{ $product->min_stock }})</p>
                                    </div>
                                    <a href="{{ route('products.show', $product) }}" class="text-sm text-blue-500 hover:underline">Lihat Detail</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-4">Menunggu Persetujuan Supplier</h3>
                
                @if($pendingSuppliers->isEmpty())
                    <p class="text-gray-500">Tidak ada pendaftar supplier baru.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Nama</th>
                                    <th class="px-4 py-2 text-left">Email</th>
                                    <th class="px-4 py-2 text-left">Tanggal Daftar</th>
                                    <th class="px-4 py-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingSuppliers as $supplier)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $supplier->name }}</td>
                                        <td class="px-4 py-2">{{ $supplier->email }}</td>
                                        <td class="px-4 py-2">{{ $supplier->created_at->format('d M Y') }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <form action="{{ route('admin.suppliers.approve', $supplier->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm" onclick="return confirm('Setujui akun supplier ini?')">
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