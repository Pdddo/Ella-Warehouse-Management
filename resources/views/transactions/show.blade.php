<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Transaksi') }}
            </h2>
            <a href="{{ route('transactions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali ke Daftar Transaksi</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert untuk pesan sukses/error --}}
            @include('partials.alert')

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    <!-- Header Detail: No Transaksi dan Status -->
                    <div class="flex flex-col md:flex-row justify-between items-start mb-6 pb-6 border-b">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $transaction->transaction_number }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                Dibuat pada: {{ $transaction->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($transaction->status == 'pending') bg-yellow-100 text-yellow-800 
                                @elseif(in_array($transaction->status, ['verified', 'approved', 'completed'])) bg-green-100 text-green-800 
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Detail Utama Transaksi -->
                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Tipe Transaksi</h4>
                            <p class="text-base font-semibold {{ $transaction->type === 'incoming' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'incoming' ? 'Barang Masuk' : 'Barang Keluar' }}
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Dibuat Oleh</h4>
                            <p class="text-base">{{ $transaction->user->name ?? 'N/A' }}</p>
                        </div>

                        {{-- Menampilkan Supplier atau Customer --}}
                        @if ($transaction->type === 'incoming' && $transaction->supplier)
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Supplier</h4>
                                <p class="text-base">{{ $transaction->supplier->name }}</p>
                            </div>
                        @elseif ($transaction->type === 'outgoing')
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Customer / Tujuan</h4>
                                <p class="text-base">{{ $transaction->customer_name }}</p>
                            </div>
                        @endif

                        {{-- Menampilkan informasi Approval --}}
                        @if ($transaction->status !== 'pending' && $transaction->approvedBy)
                             <div>
                                <h4 class="text-sm font-medium text-gray-500">Disetujui Oleh</h4>
                                <p class="text-base">{{ $transaction->approvedBy->name }}</p>
                            </div>
                             <div>
                                <h4 class="text-sm font-medium text-gray-500">Tanggal Disetujui</h4>
                                <p class="text-base">{{ $transaction->approved_at->format('d M Y, H:i') }}</p>
                            </div>
                        @endif
                    </div>
                    
                    @if($transaction->notes)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-500">Catatan</h4>
                        <p class="text-base mt-1 bg-gray-50 p-3 rounded-md">{{ $transaction->notes }}</p>
                    </div>
                    @endif

                    <!-- Daftar Produk -->
                    <h3 class="text-lg font-medium mb-4 border-t pt-6">Detail Produk</h3>
                    <div class="relative overflow-x-auto rounded-lg border">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Nama Produk</th>
                                    <th class="px-6 py-3 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaction->details as $detail)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $detail->product->name }}</td>
                                        <td class="px-6 py-4 text-right">{{ $detail->quantity }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center py-4">Tidak ada produk dalam transaksi ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if (auth()->user()->role === 'manager' && $transaction->status === 'pending')
                    <div class="mt-8 pt-6 border-t text-right">
                        <form method="POST" action="{{ route('transactions.approve', $transaction) }}" onsubmit="return confirm('Anda yakin ingin menyetujui transaksi ini? Stok akan diperbarui secara permanen.');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Setujui Transaksi Ini
                            </button>
                        </form>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>