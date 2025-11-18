<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Staff Gudang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Entry -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Entry Transaksi</h3>
                <div class="flex flex-wrap gap-4">
                    {{-- DIUBAH: transactions.create menjadi transactions.create_incoming --}}
                    <a href="{{ route('transactions.create.incoming') }}" class="px-6 py-3 bg-green-600 text-white rounded-md text-base font-semibold">
                        + Catat Barang Masuk
                    </a>
                    {{-- DIUBAH: transactions.create menjadi transactions.create_outgoing --}}
                    <a href="{{ route('transactions.create.outgoing') }}" class="px-6 py-3 bg-red-600 text-white rounded-md text-base font-semibold">
                        - Catat Barang Keluar
                    </a>
                </div>
            </div>

            <!-- Transaksi Hari Ini -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Transaksi Hari Ini ({{ \Carbon\Carbon::now()->format('d F Y') }})</h3>
                    @if($todayTransactions->isEmpty())
                        <p>Belum ada transaksi yang dicatat hari ini.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($todayTransactions as $transaction)
                                <li class="py-3 flex justify-between items-center">
                                    <div class="flex items-center">
                                        @if($transaction->type == 'incoming')
                                            <span class="p-2 bg-green-100 text-green-700 rounded-full mr-3">↑</span>
                                        @else
                                            <span class="p-2 bg-red-100 text-red-700 rounded-full mr-3">↓</span>
                                        @endif
                                        <div>
                                            <p class="font-semibold font-mono">{{ $transaction->transaction_number }}</p>
                                            <p class="text-sm text-gray-600">Dicatat oleh {{ $transaction->user->name }} pada {{ $transaction->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                    @foreach($transaction->details as $detail)
                                        <p class="text-sm text-gray-800 text-right">{{ $detail->quantity }} {{ $detail->product->unit }} - {{ $detail->product->name }}</p>
                                    @endforeach
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>