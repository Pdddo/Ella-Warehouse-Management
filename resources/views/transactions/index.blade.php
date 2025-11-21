<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Menampilkan Notifikasi (Sukses/Error) -->
            @include('partials.alert')

            {{-- PANEL TUGAS: RESTOCK ORDER YANG SUDAH TIBA DAN PERLU DIPROSES --}}
            @if (Auth::user()->role === 'staff' && $receivedOrders->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-yellow-50 border-b border-yellow-200">
                    <h3 class="text-lg font-medium text-yellow-800 mb-2">
                        Tugas: Buat Transaksi Barang Masuk
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Order berikut telah diterima di gudang. Silakan proses dan buat transaksi barang masuk yang sesuai.
                    </p>
                    <ul class="divide-y divide-gray-300">
                        @foreach ($receivedOrders as $order)
                            <li class="py-3 flex flex-col md:flex-row md:justify-between md:items-center">
                                <div>
                                    <p class="font-semibold font-mono text-gray-800">{{ $order->po_number }}</p>
                                    <p class="text-sm text-gray-600">Dari Supplier: <span class="font-medium">{{ $order->supplier->name }}</span></p>
                                </div>
                                <div class="mt-2 md:mt-0">
                                    <a href="{{ route('transactions.create.incoming', ['restock_order_id' => $order->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-500">
                                        + Proses & Buat Transaksi
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- LAYOUT DUA KOLOM UNTUK RIWAYAT TRANSAKSI --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Kolom Riwayat Transaksi Masuk -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Riwayat Transaksi Masuk</h3>
                            <a href="{{ route('transactions.create.incoming') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                Buat Baru
                            </a>
                        </div>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">No. Transaksi</th>
                                        <th class="px-6 py-3">Tanggal</th>
                                        <th class="px-6 py-3">Staff</th>
                                        <th class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($incoming as $tx)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 font-mono text-xs">{{ $tx->transaction_number }}</td>
                                        <td class="px-6 py-4">{{ $tx->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4">{{ $tx->user->name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('transactions.show', $tx) }}" class="font-medium text-blue-600 hover:underline">Detail</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4">Belum ada transaksi masuk.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $incoming->links('pagination::tailwind') }}</div>
                    </div>
                </div>

                <!-- Kolom Riwayat Transaksi Keluar -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Riwayat Transaksi Keluar</h3>
                            <a href="{{ route('transactions.create.outgoing') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                Buat Baru
                            </a>
                        </div>
                         <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">No. Transaksi</th>
                                        <th class="px-6 py-3">Tanggal</th>
                                        <th class="px-6 py-3">Staff</th>
                                        <th class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($outgoing as $tx)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 font-mono text-xs">{{ $tx->transaction_number }}</td>
                                        <td class="px-6 py-4">{{ $tx->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4">{{ $tx->user->name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('transactions.show', $tx) }}" class="font-medium text-blue-600 hover:underline">Detail</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4">Belum ada transaksi keluar.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $outgoing->links('pagination::tailwind') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>