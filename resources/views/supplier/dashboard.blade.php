<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Supplier - Daftar Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @include('partials.alert')
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">PO Number</th>
                                    <th class="px-6 py-3">Pemesan (Manager)</th>
                                    <th class="px-6 py-3">Tgl Order</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono">{{ $order->po_number }}</td>
                                    <td class="px-6 py-4">{{ $order->manager->name }}</td>
                                    <td class="px-6 py-4">{{ $order->order_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'denied') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($order->status == 'pending')
                                            <form action="{{ route('supplier.orders.confirm', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="font-medium text-green-600 hover:underline">Confirm</button>
                                            </form>
                                            <form action="{{ route('supplier.orders.deny', $order) }}" method="POST" class="inline ms-3">
                                                @csrf
                                                <button type="submit" class="font-medium text-red-600 hover:underline">Deny</button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4">Tidak ada order untuk Anda.</td></tr>
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