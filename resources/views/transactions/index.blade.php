<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8"> {{-- Lebarkan container agar tabel muat --}}
            
            @include('partials.alert')

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4">
                <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    
                    <div class="md:col-span-2">
                        <x-input-label for="search" :value="__('Cari Transaksi / Supplier / Customer')" />
                        <x-text-input id="search" name="search" type="text" value="{{ request('search') }}" class="block w-full mt-1" placeholder="Ketik nama atau nomor..." />
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select name="status" id="status" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="transaction_date" :value="__('Tanggal Transaksi')" />
                        {{-- name="transaction_date" ini harus sama persis dengan yang ada di controller --}}
                        <x-text-input id="transaction_date" name="transaction_date" type="date" value="{{ request('transaction_date') }}" class="block w-full mt-1" />
                    </div>

                    <div class="md:col-span-5 flex justify-end gap-2">
                        <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-semibold">Reset</a>
                        <x-primary-button type="submit">Terapkan Filter</x-primary-button>
                    </div>
                </form>
            </div>

            {{-- PANEL TUGAS: RESTOCK ORDER --}}
            @if (Auth::user()->role === 'staff' && isset($receivedOrders) && $receivedOrders->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-yellow-50 border-b border-yellow-200">
                    <h3 class="text-lg font-medium text-yellow-800 mb-2">Tugas: Buat Transaksi Barang Masuk</h3>
                    <ul class="divide-y divide-gray-300">
                        @foreach ($receivedOrders as $order)
                            <li class="py-3 flex flex-col md:flex-row md:justify-between md:items-center">
                                <div>
                                    <p class="font-semibold font-mono text-gray-800">{{ $order->po_number }}</p>
                                    <p class="text-sm text-gray-600">Dari: {{ $order->supplier->name }}</p>
                                </div>
                                <div class="mt-2 md:mt-0">
                                    <a href="{{ route('transactions.create.incoming', ['restock_order_id' => $order->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-md hover:bg-green-500">
                                        + Proses
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
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-full">
                    <div class="p-6 text-gray-900 flex-grow">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-blue-800">⬇️ Riwayat Barang Masuk</h3>
                            @if(Auth::user()->role !== 'supplier')
                                <a href="{{ route('transactions.create.incoming') }}" class="inline-flex items-center px-3 py-1 bg-blue-600 rounded-md font-semibold text-xs text-white uppercase hover:bg-blue-500">
                                    + Baru
                                </a>
                            @endif
                        </div>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-blue-50">
                                    <tr>
                                        <th class="px-4 py-3">Transaksi</th>
                                        <th class="px-4 py-3">Supplier</th> <th class="px-4 py-3">Status</th>   <th class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($incoming as $tx)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="font-mono font-bold text-gray-800">{{ $tx->transaction_number }}</div>
                                            <div class="text-xs text-gray-500">{{ $tx->created_at->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-400">Staff: {{ $tx->user->name }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-medium text-gray-700">{{ $tx->supplier->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($tx->status == 'pending')
                                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Selesai</span>
                                                @if($tx->approvedBy)
                                                    <div class="text-[10px] text-gray-400 mt-1">Oleh: {{ $tx->approvedBy->name }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex flex-col gap-1 items-center">
                                                <a href="{{ route('transactions.show', $tx) }}" class="text-blue-600 hover:underline text-xs font-bold">Detail</a>
                                                
                                                @if($tx->status === 'pending' && Auth::user()->role === 'manager')
                                                    <form action="{{ route('transactions.approve', $tx) }}" method="POST" onsubmit="return confirm('Setujui stok masuk ini?');">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-800 text-xs">✅ Approve</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4">Data tidak ditemukan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $incoming->links('pagination::tailwind') }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-full">
                    <div class="p-6 text-gray-900 flex-grow">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-red-800">⬆️ Riwayat Barang Keluar</h3>
                            @if(Auth::user()->role !== 'supplier')
                                <a href="{{ route('transactions.create.outgoing') }}" class="inline-flex items-center px-3 py-1 bg-red-600 rounded-md font-semibold text-xs text-white uppercase hover:bg-red-500">
                                    + Baru
                                </a>
                            @endif
                        </div>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-red-50">
                                    <tr>
                                        <th class="px-4 py-3">Transaksi</th>
                                        <th class="px-4 py-3">Customer</th> <th class="px-4 py-3">Status</th>   <th class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($outgoing as $tx)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="font-mono font-bold text-gray-800">{{ $tx->transaction_number }}</div>
                                            <div class="text-xs text-gray-500">{{ $tx->created_at->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-400">Staff: {{ $tx->user->name }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-medium text-gray-700">{{ $tx->customer_name ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($tx->status == 'pending')
                                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Selesai</span>
                                                @if($tx->approvedBy)
                                                    <div class="text-[10px] text-gray-400 mt-1">Oleh: {{ $tx->approvedBy->name }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex flex-col gap-1 items-center">
                                                <a href="{{ route('transactions.show', $tx) }}" class="text-blue-600 hover:underline text-xs font-bold">Detail</a>
                                                
                                                @if($tx->status === 'pending' && Auth::user()->role === 'manager')
                                                    <form action="{{ route('transactions.approve', $tx) }}" method="POST" onsubmit="return confirm('Setujui stok keluar ini?');">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-800 text-xs">✅ Approve</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4">Data tidak ditemukan.</td></tr>
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