<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Detail Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('transactions.index') }}" class="inline-flex items-center text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Riwayat
                </a>
            </div>

            @include('partials.alert')

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl overflow-hidden">

                {{-- Header Card --}}
                <div class="p-8 border-b border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white/5">
                    <div>
                        <h1 class="text-3xl font-bold text-white font-mono">{{ $transaction->transaction_number }}</h1>
                        <p class="text-slate-400 mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $transaction->created_at->format('d F Y, H:i') }}
                        </p>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        {{-- Badge Tipe Transaksi --}}
                        @if($transaction->type == 'incoming')
                            <div class="px-4 py-2 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-xl text-center">
                                <span class="block text-xs uppercase font-bold tracking-wider">Tipe Transaksi</span>
                                <span class="block text-lg font-bold">Barang Masuk</span>
                            </div>
                        @else
                            <div class="px-4 py-2 bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-xl text-center">
                                <span class="block text-xs uppercase font-bold tracking-wider">Tipe Transaksi</span>
                                <span class="block text-lg font-bold">Barang Keluar</span>
                            </div>
                        @endif

                        {{-- Badge Status --}}
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                'verified' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                            ];
                            $badgeClass = $statusColors[$transaction->status] ?? 'bg-slate-500/10 text-slate-400 border-white/10';
                        @endphp
                        <span class="px-3 py-1 rounded-lg border text-xs font-bold uppercase tracking-wide {{ $badgeClass }}">
                            Status: {{ $transaction->status }}
                        </span>
                    </div>
                </div>

                <div class="p-8">
                    {{-- User Info --}}
                    <div class="flex items-center gap-4 mb-8 p-4 rounded-xl bg-white/5 border border-white/5">
                        <div class="w-10 h-10 rounded-full bg-violet-500 flex items-center justify-center text-white font-bold">
                            {{ substr($transaction->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase">Dicatat Oleh</p>
                            <p class="text-white font-medium">{{ $transaction->user->name }}</p>
                        </div>
                    </div>

                    {{-- Tabel Detail --}}
                    <h4 class="text-white font-semibold mb-4">Rincian Item</h4>
                    <div class="overflow-hidden rounded-xl border border-white/10">
                        <table class="w-full text-left">
                            <thead class="bg-white/5 text-slate-400 text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-3">Produk</th>
                                    <th class="px-6 py-3 text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($transaction->details as $detail)
                                <tr class="bg-[#0a0a0f]/30">
                                    <td class="px-6 py-4 text-white font-medium">
                                        {{ $detail->product->name }}
                                        <span class="block text-xs text-slate-500 font-normal">SKU: {{ $detail->product->sku ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-white font-mono">
                                        {{ $detail->quantity }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-white/5 border-t border-white/10">
                                <tr>
                                    <td class="px-6 py-4 text-slate-400 font-semibold text-right">Total Item</td>
                                    <td class="px-6 py-4 text-white font-bold text-right font-mono">
                                        {{ $transaction->details->sum('quantity') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Catatan --}}
                    @if($transaction->notes)
                    <div class="mt-8">
                        <h4 class="text-slate-400 text-sm mb-2">Catatan:</h4>
                        <p class="text-slate-300 italic bg-white/5 p-4 rounded-xl border border-white/5">
                            "{{ $transaction->notes }}"
                        </p>
                    </div>
                    @endif

                    {{-- PANEL APPROVAL (Hanya Admin/Manager & Status Pending) --}}
                    @if((auth()->user()->role === 'admin' || auth()->user()->role === 'manager') && $transaction->status === 'pending')
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <h4 class="text-white font-bold mb-4">Tindakan Manager</h4>
                            <div class="flex gap-4">
                                {{-- Form Approve --}}
                                <form action="{{ route('transactions.approve', $transaction) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2" onclick="return confirm('Apakah Anda yakin ingin menyetujui transaksi ini? Stok akan diperbarui.')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Setujui Transaksi
                                    </button>
                                </form>

                                {{-- Form Reject (Hapus Transaksi) --}}
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-6 py-3 bg-white/5 hover:bg-rose-500/20 text-rose-400 border border-white/10 hover:border-rose-500/30 font-bold rounded-xl transition-all flex items-center gap-2" onclick="return confirm('Apakah Anda yakin ingin menolak dan menghapus transaksi ini?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Tolak & Hapus
                                    </button>
                                </form>
                            </div>
                            <p class="text-xs text-slate-500 mt-3">
                                * Menyetujui transaksi akan otomatis memperbarui stok produk di gudang.
                            </p>
                        </div>
                    @endif

                    {{--AKSI STAFF (Hapus Transaksi Sendiri) --}}
                    @if(auth()->user()->role === 'staff' && $transaction->status === 'pending' && $transaction->user_id === auth()->id())
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <h4 class="text-white font-bold mb-4">Tindakan Anda</h4>
                            <div class="flex gap-4">
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-6 py-3 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow-lg shadow-rose-500/20 transition-all flex items-center gap-2" onclick="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus transaksi ini?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus Transaksi
                                    </button>
                                </form>
                            </div>
                            <p class="text-xs text-slate-500 mt-3">
                                * Transaksi yang dihapus tidak dapat dikembalikan.
                            </p>
                        </div>
                    @endif

                    {{-- Info Approval (Jika sudah diapprove) --}}
                    @if($transaction->status === 'completed' || $transaction->status === 'verified')
                        <div class="mt-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3">
                            <div class="p-2 bg-emerald-500/20 rounded-full text-emerald-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-emerald-400 font-bold text-sm">Transaksi Telah Disetujui</p>
                                <p class="text-slate-400 text-xs">
                                    Disetujui oleh <span class="text-white font-medium">{{ $transaction->approvedBy->name ?? 'Manager' }}</span>
                                    pada {{ $transaction->updated_at->format('d F Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
