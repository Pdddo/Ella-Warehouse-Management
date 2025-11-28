<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard Staff Gudang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 mb-8 shadow-2xl">
                <h3 class="text-lg font-bold text-white mb-6">Quick Entry Transaksi</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Tombol Barang Masuk --}}
                    <a href="{{ route('transactions.create.incoming') }}" class="group relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-emerald-900/40 to-emerald-900/10 border border-emerald-500/20 hover:border-emerald-500/50 transition-all hover:shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                        <div class="relative z-10 flex items-center gap-4">
                            <div class="p-3 rounded-xl bg-emerald-500/20 text-emerald-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-white">Barang Masuk</h4>
                                <p class="text-sm text-emerald-200/60">Catat penerimaan barang baru</p>
                            </div>
                        </div>
                    </a>
                    
                    {{-- Tombol Barang Keluar --}}
                    <a href="{{ route('transactions.create.outgoing') }}" class="group relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-rose-900/40 to-rose-900/10 border border-rose-500/20 hover:border-rose-500/50 transition-all hover:shadow-[0_0_20px_rgba(244,63,94,0.2)]">
                        <div class="relative z-10 flex items-center gap-4">
                            <div class="p-3 rounded-xl bg-rose-500/20 text-rose-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-white">Barang Keluar</h4>
                                <p class="text-sm text-rose-200/60">Catat pengeluaran barang</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-white">Transaksi Hari Ini</h3>
                    <span class="px-3 py-1 rounded-full bg-white/5 text-xs text-slate-400 border border-white/5">{{ \Carbon\Carbon::now()->format('d F Y') }}</span>
                </div>

                @if($todayTransactions->isEmpty())
                    <div class="text-center py-10">
                        <p class="text-slate-500">Belum ada transaksi yang dicatat hari ini.</p>
                    </div>
                @else
                    <ul class="divide-y divide-white/5">
                        @foreach ($todayTransactions as $transaction)
                            <li class="py-4 hover:bg-white/5 transition-colors px-4 -mx-4 rounded-xl">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-start gap-3">
                                        @if($transaction->type == 'incoming')
                                            <div class="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg mt-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                            </div>
                                        @else
                                            <div class="p-2 bg-rose-500/10 text-rose-400 rounded-lg mt-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-white font-mono">{{ $transaction->transaction_number }}</p>
                                            <p class="text-xs text-slate-500">Oleh <span class="text-slate-300">{{ $transaction->user->name }}</span> • {{ $transaction->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        @foreach($transaction->details as $detail)
                                            <div class="text-sm text-slate-300">
                                                <span class="font-bold text-white">{{ $detail->quantity }}</span> {{ $detail->product->unit ?? 'Unit' }} 
                                                <span class="text-slate-500">x</span> {{ $detail->product->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>