<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Restock Order') }}
            </h2>
            <a href="{{ route('restock-orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; Kembali ke Daftar Order
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    @include('partials.alert')

                    <!-- Header -->
                    <div class="flex flex-col md:flex-row justify-between items-start mb-6 pb-6 border-b">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 font-mono">
                                {{ $restockOrder->po_number }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                Dibuat pada: {{ $restockOrder->order_date->format('d M Y') }}
                            </p>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                            @if($restockOrder->status == 'pending') bg-yellow-100 text-yellow-800 
                                @elseif($restockOrder->status == 'confirmed') bg-blue-100 text-blue-800 
                                @elseif($restockOrder->status == 'in_transit') bg-purple-100 text-purple-800 
                                @elseif($restockOrder->status == 'received') bg-green-100 text-green-800 
                                @else bg-red-100 text-red-800 
                            @endif">
                                {{ ucfirst(str_replace('_', ' ', $restockOrder->status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Detail Utama -->
                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Dibuat Oleh (Manager)</h4>
                            <p class="text-base">{{ $restockOrder->manager->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Supplier</h4>
                            <p class="text-base">{{ $restockOrder->supplier->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Perkiraan Tiba</h4>
                            <p class="text-base">{{ $restockOrder->expected_delivery_date ? $restockOrder->expected_delivery_date->format('d M Y') : 'Tidak ditentukan' }}</p>
                        </div>
                        @if($restockOrder->notes)
                        <div class="col-span-2">
                            <h4 class="text-sm font-medium text-gray-500">Catatan</h4>
                            <p class="text-base mt-1 whitespace-pre-wrap">{{ $restockOrder->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Daftar Produk -->
                    <h3 class="text-lg font-medium mb-4 border-t pt-6">Detail Produk Order</h3>
                    <div class="relative overflow-x-auto rounded-lg border">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Nama Produk</th>
                                    <th class="px-6 py-3 text-right">Jumlah</th>
                                    <th class="px-6 py-3 text-right">Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($restockOrder->details as $detail)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $detail->product->name }}</td>
                                        <td class="px-6 py-4 text-right">{{ $detail->quantity }}</td>
                                        <td class="px-6 py-4 text-right text-gray-500">{{ $detail->product->unit ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-4">Tidak ada detail produk untuk order ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Rating & Feedback Supplier</h3>

                        @if($restockOrder->rating)
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="flex items-center mb-2">
                                    <span class="text-gray-600 mr-2">Rating:</span>
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-2xl">{{ $i <= $restockOrder->rating ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                    <span class="ml-2 font-bold text-gray-700">({{ $restockOrder->rating }}/5)</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 block mb-1">Feedback:</span>
                                    <p class="text-gray-800 italic">"{{ $restockOrder->supplier_feedback ?? 'Tidak ada pesan feedback.' }}"</p>
                                </div>
                            </div>

                        @elseif($restockOrder->status === 'received' && auth()->user()->role === 'manager')
                            <form action="{{ route('restock-orders.rate', $restockOrder) }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Berikan Rating (1-5 Bintang)</label>
                                    <div class="flex space-x-4">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer flex flex-col items-center">
                                                <input type="radio" name="rating" value="{{ $i }}" class="mb-1" required>
                                                <span class="text-xl">★ {{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="supplier_feedback" class="block text-sm font-medium text-gray-700">Feedback / Catatan (Opsional)</label>
                                    <textarea name="supplier_feedback" id="supplier_feedback" rows="3" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Contoh: Pengiriman cepat, barang aman."></textarea>
                                </div>

                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                                    Kirim Rating
                                </button>
                            </form>

                        @else
                            <p class="text-gray-500 italic">
                                @if($restockOrder->status !== 'received')
                                    Rating dapat diberikan setelah barang diterima (Status: Received).
                                @else
                                    Belum ada rating yang diberikan.
                                @endif
                            </p>
                        @endif
                    </div>
                    

                    {{-- PANEL AKSI MANAGER DENGAN LOGIKA FINAL --}}
                    @if (auth()->user()->role === 'manager')
                        <div class="mt-8 pt-6 border-t">
                            <h3 class="text-lg font-medium mb-4">Aksi Manager</h3>
                            <div class="flex items-center space-x-4">

                                @if ($restockOrder->status === 'confirmed')
                                    {{-- Jika CONFIRMED: Tampilkan tombol "Dalam Perjalanan" --}}
                                    <form method="POST" action="{{ route('restock-orders.updateStatus', $restockOrder) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="in_transit">
                                        <x-primary-button type="submit">
                                            Tandai "Dalam Perjalanan"
                                        </x-primary-button>
                                    </form>

                                @elseif ($restockOrder->status === 'in_transit')
                                    {{-- Jika IN_TRANSIT: Tampilkan tombol "Telah Diterima" --}}
                                    <form method="POST" action="{{ route('restock-orders.updateStatus', $restockOrder) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="received">
                                        <x-primary-button type="submit">
                                            Tandai "Telah Diterima"
                                        </x-primary-button>
                                    </form>
                                    
                                @else
                                    {{-- Jika status sudah final (received/cancelled) --}}
                                    <p class="text-gray-500 text-sm">Tidak ada aksi yang tersedia untuk status ini.</p>
                                @endif

                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>