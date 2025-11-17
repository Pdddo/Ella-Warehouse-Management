<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Order: {{ $restockOrder->po_number }}
            </h2>
            {{-- Link kembali ke halaman yang sesuai tergantung role pengguna --}}
            @if(auth()->user()->role === 'manager')
                <a href="{{ route('restock-orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    &larr; Kembali ke Daftar Order
                </a>
            @elseif(auth()->user()->role === 'supplier')
                 <a href="{{ route('supplier.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    &larr; Kembali ke Dashboard
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
             @include('partials.alert')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <!-- Header Detail -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6 pb-6 border-b">
                        <div>
                            <dt class="font-medium text-gray-500">PO Number</dt>
                            <dd class="mt-1 font-mono">{{ $restockOrder->po_number }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @switch($restockOrder->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('confirmed') bg-blue-100 text-blue-800 @break
                                        @case('in_transit') bg-purple-100 text-purple-800 @break
                                        @case('received') bg-green-100 text-green-800 @break
                                        @case('denied')
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $restockOrder->status)) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Dibuat Oleh</dt>
                            <dd class="mt-1">{{ $restockOrder->manager->name }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Supplier</dt>
                            <dd class="mt-1">{{ $restockOrder->supplier->name }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Tanggal Order</dt>
                            <dd class="mt-1">{{ $restockOrder->order_date->format('d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Ekspektasi Tiba</dt>
                            <dd class="mt-1">{{ $restockOrder->expected_delivery_date->format('d F Y') }}</dd>
                        </div>
                    </div>

                    <!-- Daftar Produk -->
                    <h3 class="text-lg font-medium mb-4">Produk dalam Order</h3>
                    <div class="flow-root">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($restockOrder->details as $detail)
                                <tr>
                                    <td class="px-6 py-4">{{ $detail->product->name }} <span class="text-gray-500 font-mono text-xs">({{ $detail->product->sku }})</span></td>
                                    <td class="px-6 py-4">{{ $detail->quantity }} {{ $detail->product->unit }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Catatan -->
                    @if($restockOrder->notes)
                    <div class="mt-6 border-t pt-6">
                        <h3 class="text-lg font-medium">Catatan</h3>
                        <p class="mt-2 text-gray-600 whitespace-pre-wrap">{{ $restockOrder->notes }}</p>
                    </div>
                    @endif

                    <!-- Tombol Aksi untuk Manager -->
                    @if(auth()->user()->role === 'manager')
                    <div class="mt-8 pt-6 border-t flex justify-end gap-3">
                        {{-- Tombol untuk membatalkan order yang masih PENDING --}}
                        @if($restockOrder->status === 'pending')
                            <form action="{{ route('restock-orders.destroy', $restockOrder) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan order ini?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">Batalkan Order</x-danger-button>
                            </form>
                        @endif

                        {{-- Tombol untuk menandai IN TRANSIT --}}
                        @if($restockOrder->status === 'confirmed')
                            <form action="{{ route('restock-orders.updateStatus', $restockOrder) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="in_transit">
                                <x-primary-button type="submit" class="bg-blue-600 hover:bg-blue-500">Tandai "In Transit"</x-primary-button>
                            </form>
                        @endif

                        {{-- Tombol untuk menandai RECEIVED --}}
                        @if($restockOrder->status === 'in_transit')
                            <form action="{{ route('restock-orders.updateStatus', $restockOrder) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin barang telah diterima? Stok akan diperbarui secara otomatis dan aksi ini tidak dapat dibatalkan.');">
                                @csrf
                                <input type="hidden" name="status" value="received">
                                <x-primary-button type="submit" class="bg-green-600 hover:bg-green-500">Tandai "Received"</x-primary-button>
                            </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>