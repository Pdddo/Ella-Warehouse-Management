<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notifikasi -->
            @include('partials.alert')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Kolom Transaksi Masuk -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Transaksi Barang Masuk</h3>
                            <a href="{{ route('transactions.create.incoming') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                Buat Baru
                            </a>
                        </div>
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">No. Transaksi</th>
                                        <th scope="col" class="px-6 py-3">Tanggal</th>
                                        <th scope="col" class="px-6 py-3">Staff</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($incoming as $tx)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 font-mono text-xs">{{ $tx->transaction_number }}</td>
                                        <td class="px-6 py-4">{{ $tx->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4">{{ $tx->user->name }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center py-4">Belum ada transaksi masuk.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $incoming->links() }}</div>
                    </div>
                </div>

                <!-- Kolom Transaksi Keluar -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium">Transaksi Barang Keluar</h3>
                            <a href="{{ route('transactions.create.outgoing') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                Buat Baru
                            </a>
                        </div>
                         <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">No. Transaksi</th>
                                        <th scope="col" class="px-6 py-3">Tanggal</th>
                                        <th scope="col" class="px-6 py-3">Staff</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($outgoing as $tx)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 font-mono text-xs">{{ $tx->transaction_number }}</td>
                                        <td class="px-6 py-4">{{ $tx->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4">{{ $tx->user->name }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center py-4">Belum ada transaksi keluar.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $outgoing->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>