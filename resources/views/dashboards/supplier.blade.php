<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Order yang Perlu Dikonfirmasi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-orange-50 border-b border-orange-200">
                    <h3 class="text-lg font-medium text-orange-800 mb-4">
                        Order Baru Perlu Dikonfirmasi ({{ $pendingConfirmationOrders->count() }})
                    </h3>
                    @if($pendingConfirmationOrders->isEmpty())
                        <p class="text-sm text-gray-500">👍 Tidak ada order baru yang menunggu konfirmasi Anda.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($pendingConfirmationOrders as $order)
                                <li class="py-3 flex flex-col md:flex-row md:justify-between md:items-center">
                                    <div>
                                        <a href="{{ route('restock-orders.show', $order) }}" class="font-semibold font-mono text-blue-600 hover:underline">{{ $order->po_number }}</a>
                                        <p class="text-sm text-gray-600">Dipesan oleh: {{ $order->manager->name }} pada {{ $order->order_date->format('d M Y') }}</p>
                                    </div>
                                    <div class="flex items-center gap-4 mt-2 md:mt-0">
                                        <form action="{{ route('supplier.orders.confirm', $order) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-md hover:bg-green-600">Confirm</button>
                                        </form>
                                        <form action="{{ route('supplier.orders.deny', $order) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-md hover:bg-red-600">Deny</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Riwayat Pengiriman -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Pengiriman (Telah Diterima Gudang)</h3>
                     @if($shipmentHistory->isEmpty())
                        <p class="text-sm text-gray-500">Belum ada riwayat pengiriman yang tercatat.</p>
                    @else
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">PO Number</th>
                                        <th class="px-6 py-3">Pemesan</th>
                                        <th class="px-6 py-3">Tanggal Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shipmentHistory as $order)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-mono">
                                                 <a href="{{ route('restock-orders.show', $order) }}" class="font-medium text-blue-600 hover:underline">{{ $order->po_number }}</a>
                                            </td>
                                            <td class="px-6 py-4">{{ $order->manager->name }}</td>
                                            <td class="px-6 py-4">{{ $order->order_date->format('d F Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $shipmentHistory->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>