<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 sm:mb-0">
                {{ __('Manajemen Restock Order') }}
            </h2>
            <a href="{{ route('restock-orders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                + Buat Order Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    @include('partials.alert')

                    
                    <div class="relative overflow-x-auto rounded-lg border">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">PO Number</th>
                                    <th scope="col" class="px-6 py-3">Supplier</th>
                                    <th scope="col" class="px-6 py-3">Tanggal Order</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Manager</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th scope="row" class="px-6 py-4 font-mono font-medium text-gray-900 whitespace-nowrap">
                                            {{ $order->po_number }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $order->supplier->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->order_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800 @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800 @elseif($order->status == 'in_transit') bg-purple-100 text-purple-800 @elseif($order->status == 'received') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->manager->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-center space-x-2">
                                            <a href="{{ route('restock-orders.show', $order) }}" class="font-medium text-blue-600 hover:underline">Detail</a>
                                            
                                            {{-- Tampilkan tombol Edit hanya jika statusnya 'pending' --}}
                                            @if ($order->status === 'pending')
                                                <a href="{{ route('restock-orders.edit', $order) }}" class="font-medium text-green-600 hover:underline">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                            Tidak ada restock order yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Navigasi Paginasi --}}
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>