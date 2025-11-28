<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard Manager Gudang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 relative overflow-hidden">
                     <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-20 h-20 text-[#56a09f]" fill="currentColor" viewBox="0 0 24 24"><path d="M4 11h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1zm1-6h4v4H5V5zm15-2h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zm-1 6h-4V5h4v4zM4 21h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1zm1-6h4v4H5v-4zm15-2h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1zm-1 6h-4v-4h4v4z"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400">Total Unit di Gudang</h3>
                    <p class="text-4xl font-bold text-white mt-2">{{ number_format($totalItems) }}</p>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-[#56a09f]"></div>
                </div>

                <div class="bg-rose-900/10 backdrop-blur-xl border border-rose-500/20 rounded-2xl p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-20 h-20 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm.5-13h-1v6l5.25 3.15.5-.866-4.75-2.85z"></path></svg>
                    </div>
                    <h3 class="text-sm font-medium text-rose-400">Produk Stok Rendah</h3>
                    <p class="text-4xl font-bold text-rose-500 mt-2">{{ $lowStockCount }}</p>
                    <p class="text-xs text-rose-300/60 mt-1">Segera lakukan restock order</p>
                </div>
            </div>

            {{-- Pending Approval Transactions --}}
            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl rounded-2xl p-6 mb-8 shadow-[0_0_20px_-5px_rgba(234,179,8,0.1)] relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full"></div>
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-yellow-500/20 rounded-lg text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Transaksi Menunggu Approval</h3>
                            <p class="text-sm text-slate-400">Perlu tinjauan manager untuk memperbarui stok</p>
                        </div>
                    </div>
                    @if($pendingTransactions->isNotEmpty())
                        <span class="px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-400 text-xs font-bold border border-yellow-500/30">
                            {{ $pendingTransactions->count() }} Pending
                        </span>
                    @endif
                </div>

                @if($pendingTransactions->isEmpty())
                    <div class="text-center py-8 border-t border-white/5 mt-4">
                        <p class="text-slate-500 text-sm">Tidak ada transaksi yang perlu disetujui saat ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto mt-4">
                        <table class="w-full text-left text-sm">
                            <thead class="text-xs text-slate-400 uppercase bg-white/5">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-lg">No. Transaksi</th>
                                    <th class="px-4 py-3">Tipe</th>
                                    <th class="px-4 py-3">Oleh</th>
                                    <th class="px-4 py-3">Waktu</th>
                                    <th class="px-4 py-3 rounded-r-lg text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($pendingTransactions as $transaction)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-4 py-3 font-mono font-medium text-white">
                                            {{ $transaction->transaction_number }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($transaction->type == 'incoming')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    MASUK
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                    KELUAR
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-slate-300">{{ $transaction->user->name }}</td>
                                        <td class="px-4 py-3 text-slate-400">{{ $transaction->created_at->diffForHumans() }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('transactions.show', $transaction) }}" class="px-3 py-1.5 bg-yellow-500/20 text-yellow-600 font-bold rounded-lg text-xs transition-colors">
                                                Tinjau
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-xl h-full">
                    <h3 class="text-lg font-bold text-white mb-4">Order Restock Berjalan</h3>
                     @if($ongoingRestocks->isEmpty())
                        <p class="text-sm text-slate-500">Tidak ada order restock yang sedang berjalan.</p>
                    @else
                        <ul class="divide-y divide-white/5">
                            @foreach ($ongoingRestocks as $order)
                                <li class="py-4 flex justify-between items-center group">
                                    <div>
                                        <p class="font-semibold font-mono text-violet-300 group-hover:text-violet-200 transition-colors">{{ $order->po_number }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                                            <p class="text-xs text-slate-400 capitalize">{{ str_replace('_', ' ', $order->status) }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('restock-orders.show', $order) }}" class="px-3 py-1 bg-white/5 hover:bg-white/10 text-xs text-slate-300 rounded-lg transition-colors border border-white/5">Detail</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-xl h-full">
                    <h3 class="text-lg font-bold text-white mb-2">5 Transaksi Terakhir</h3>
                    <p class="text-xs text-slate-500 mb-4 border-b border-white/5 pb-2">Log aktivitas terbaru di gudang</p>
                    
                    @if($recentTransactions->isEmpty())
                        <p class="text-sm text-slate-500">Belum ada transaksi.</p>
                    @else
                       <ul class="space-y-3">
                            @foreach ($recentTransactions as $transaction)
                                <li class="flex items-center justify-between p-3 bg-white/5 rounded-xl hover:bg-white/10 transition-colors border border-white/5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $transaction->type == 'incoming' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $transaction->type == 'incoming' ? 'M19 14l-7 7m0 0l-7-7m7 7V3' : 'M5 10l7-7m0 0l7 7m-7-7v18' }}"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-white capitalize">{{ $transaction->type }}</p>
                                            <p class="text-xs text-slate-500">{{ $transaction->user->name }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-500 font-mono">{{ $transaction->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>