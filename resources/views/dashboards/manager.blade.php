<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Manager Gudang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-sm font-medium text-gray-500">Total Unit di Gudang</h3>
                    <p class="text-3xl font-semibold text-gray-900 mt-2">{{ number_format($totalItems) }}</p>
                </div>
                <div class="bg-red-50 p-6 rounded-lg shadow-sm border border-red-200">
                    <h3 class="text-sm font-medium text-red-600">Produk Stok Rendah</h3>
                    <p class="text-3xl font-semibold text-red-700 mt-2">{{ $lowStockCount }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Ongoing Restock Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Restock Berjalan</h3>
                         @if($ongoingRestocks->isEmpty())
                            <p class="text-sm text-gray-500">Tidak ada order restock yang sedang berjalan.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach ($ongoingRestocks as $order)
                                    <li class="py-3 flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold font-mono">{{ $order->po_number }}</p>
                                            <p class="text-sm text-gray-600">Status: <span class="font-bold">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></p>
                                        </div>
                                        <a href="{{ route('restock-orders.show', $order) }}" class="text-sm text-blue-500 hover:underline">Lihat Detail</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">5 Transaksi Terakhir</h3>
                        <p class="text-xs text-gray-400 mb-2">Sistem saat ini tidak memiliki 'pending approval'. Ini adalah transaksi yang sudah dicatat.</p>
                        @if($recentTransactions->isEmpty())
                            <p class="text-sm text-gray-500">Belum ada transaksi.</p>
                        @else
                           <ul class="divide-y divide-gray-200">
                                @foreach ($recentTransactions as $transaction)
                                    <li class="py-3">
                                        <p class="font-semibold font-mono flex items-center">
                                            @if($transaction->type == 'incoming')
                                                <span class="text-green-500 mr-2">●</span> Masuk
                                            @else
                                                <span class="text-red-500 mr-2">●</span> Keluar
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-600">{{ $transaction->created_at->diffForHumans() }} oleh {{ $transaction->user->name }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>