<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Order Restock') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Tombol dan Form Filter -->
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('restock-orders.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Buat Order Baru
                        </a>
                        <form method="GET" action="{{ route('restock-orders.index') }}">
                            <div class="flex items-center">
                                <select name="status" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <x-primary-button class="ms-3">Filter</x-primary-button>
                            </div>
                        </form>
                    </div>

                    @include('partials.alert')

                    <!-- Tabel Order -->
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">PO Number</th>
                                    <th class="px-6 py-3">Supplier</th>
                                    <th class="px-6 py-3">Tgl Order</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono">{{ $order->po_number }}</td>
                                    <td class="px-6 py-4">{{ $order->supplier->name }}</td>
                                    <td class="px-6 py-4">{{ $order->order_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @switch($order->status)
                                                @case('pending') bg-yellow-100 text-yellow-800 @break
                                                @case('completed') bg-green-100 text-green-800 @break
                                                @case('cancelled') bg-red-100 text-red-800 @break
                                            @endswitch">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('restock-orders.show', $order) }}" class="font-medium text-blue-600 hover:underline">Lihat Detail</a>
                                        @if ($order->status == 'pending')
                                        <form action="{{ route('restock-orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan order ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:underline ms-3">Batalkan</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4">Tidak ada data order.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $orders->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>