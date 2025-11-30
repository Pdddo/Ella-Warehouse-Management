<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'incoming' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center gap-3 shadow-lg shadow-emerald-500/10" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
                    <div class="p-2 bg-emerald-500/20 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm">Berhasil!</p>
                        <p class="text-xs opacity-90">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl flex items-center gap-3 shadow-lg shadow-rose-500/10" x-data="{ show: true }" x-show="show" x-transition.duration.500ms>
                    <div class="p-2 bg-rose-500/20 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm">Terjadi Kesalahan!</p>
                        <p class="text-xs opacity-90">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            {{-- Notifikasi Restock (HANYA UNTUK STAFF) --}}
            @if(auth()->user()->role === 'staff' && isset($receivedOrders) && $receivedOrders->count() > 0)
            <div class="mb-8 p-6 bg-[#0a0a0f]/60 backdrop-blur-xl border border-emerald-500/30 rounded-2xl shadow-[0_0_30px_-10px_rgba(16,185,129,0.2)] relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500 shadow-[0_0_15px_#10b981]"></div>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-emerald-500/10 rounded-xl border border-emerald-500/20 text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Barang Masuk Menunggu</h3>
                            <p class="text-sm text-slate-400">
                                <span class="font-bold text-emerald-400">{{ $receivedOrders->count() }}</span> Restock Order telah diterima di gudang.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($receivedOrders as $order)
                            <a href="{{ route('transactions.create.incoming', ['restock_order_id' => $order->id]) }}"
                               class="px-4 py-2 text-xs font-bold rounded-lg bg-emerald-500 hover:bg-emerald-400 text-[#0a0a0f] transition-all shadow-lg hover:shadow-emerald-500/20 flex items-center gap-2">
                                <span>Proses #{{ $order->po_number }}</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-white">Transaksi Gudang</h3>
                    <p class="text-slate-400 text-sm">Kelola arus barang masuk dan keluar</p>
                </div>

                <div class="flex items-center gap-3 bg-[#0a0a0f]/60 p-1 rounded-xl border border-white/10">
                    <button @click="activeTab = 'incoming'"
                        :class="activeTab === 'incoming' ? 'bg-emerald-500/20 text-emerald-400 shadow-lg' : 'text-slate-400 hover:text-white'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        Masuk
                    </button>
                    <button @click="activeTab = 'outgoing'"
                        :class="activeTab === 'outgoing' ? 'bg-rose-500/20 text-rose-400 shadow-lg' : 'text-slate-400 hover:text-white'"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        Keluar
                    </button>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('transactions.create.incoming') }}" class="px-4 py-2 bg-emerald-500/20 text-emerald-400 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                        + Masuk
                    </a>
                    <a href="{{ route('transactions.create.outgoing') }}" class="px-4 py-2 bg-rose-500/20 text-rose-400 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                        - Keluar
                    </a>
                </div>
            </div>

            {{-- Form Filter & Search --}}
            <div class="mb-8">
                <form method="GET" action="{{ route('transactions.index') }}" class="p-4 bg-[#0a0a0f]/60 border border-white/5 rounded-2xl flex flex-col md:flex-row gap-4 items-end">

                    {{-- Search --}}
                    <div class="w-full md:flex-1">
                        <label for="search" class="block text-xs font-medium text-slate-400 mb-1">Cari Supplier / Customer</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2.5 bg-[#0a0a0f] border border-white/10 rounded-xl text-sm text-white focus:ring-violet-500 focus:border-violet-500 placeholder-slate-600"
                                placeholder="Nama Supplier, Customer, atau No. Transaksi...">
                            <svg class="w-4 h-4 text-slate-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    {{-- Filter: Tanggal --}}
                    <div class="w-full md:w-auto">
                        <label for="transaction_date" class="block text-xs font-medium text-slate-400 mb-1">Tanggal</label>
                        <input type="date" name="transaction_date" value="{{ request('transaction_date') }}"
                            class="w-full bg-[#0a0a0f] border border-white/10 rounded-xl text-sm text-white focus:ring-violet-500 focus:border-violet-500">
                    </div>

                    {{-- Filter: Status --}}
                    <div class="w-full md:w-48">
                        <label for="status" class="block text-xs font-medium text-slate-400 mb-1">Status</label>
                        <select name="status" class="w-full bg-[#0a0a0f] border border-white/10 rounded-xl text-sm text-white focus:ring-violet-500 focus:border-violet-500">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        </select>
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit" class="px-6 py-2.5 bg-white/10 hover:bg-white/20 text-white font-medium rounded-xl transition-colors">
                        Filter
                    </button>

                    {{-- Tombol Reset --}}
                    @if(request()->hasAny(['search', 'status', 'transaction_date']))
                        <a href="{{ route('transactions.index') }}" class="px-4 py-2.5 text-slate-400 hover:text-white transition-colors">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div x-show="activeTab === 'incoming'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">

                <div class="mb-4 flex items-center gap-2 text-emerald-400">
                    <div class="w-2 h-8 bg-emerald-500 rounded-full"></div>
                    <h4 class="text-lg font-bold text-white">Riwayat Barang Masuk</h4>
                </div>

                <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-emerald-500/20 rounded-2xl overflow-hidden shadow-2xl relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none -z-10"></div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-white/5 bg-emerald-500/5">
                                    <th class="px-6 py-4 text-xs font-semibold text-emerald-300 uppercase tracking-wider">No. Transaksi</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-emerald-300 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-emerald-300 uppercase tracking-wider">Supplier</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-emerald-300 uppercase tracking-wider">Petugas</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-emerald-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-emerald-300 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-slate-300">
                                @forelse ($incoming as $transaction)
                                <tr class="hover:bg-emerald-500/5 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-white font-medium">
                                        {{ $transaction->transaction_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-400">
                                        {{ $transaction->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-white">
                                        {{ $transaction->supplier->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-400">
                                        {{ $transaction->user->name }}
                                    </td>
                                    {{--Tampilan Status Badge --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                                'verified' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            ];
                                            $badgeClass = $statusColors[$transaction->status] ?? 'bg-slate-500/10 text-slate-400 border-white/10';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full border text-xs font-bold uppercase tracking-wide {{ $badgeClass }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            {{-- Tombol Detail --}}
                                            <a href="{{ route('transactions.show', $transaction) }}" class="text-slate-400 hover:text-emerald-400 transition-colors p-1" title="Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            {{-- tombol edit--}}
                                            @if($transaction->status === 'pending')
                                                <a href="{{ route('transactions.edit', $transaction) }}" class="text-slate-400 hover:text-blue-400 transition-colors p-1" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada data barang masuk.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($incoming->hasPages())
                    <div class="px-6 py-4 border-t border-white/5">
                        {{ $incoming->links() }}
                    </div>
                    @endif
                </div>
            </div>

            <div x-show="activeTab === 'outgoing'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">

                <div class="mb-4 flex items-center gap-2 text-rose-400">
                    <div class="w-2 h-8 bg-rose-500 rounded-full"></div>
                    <h4 class="text-lg font-bold text-white">Riwayat Barang Keluar</h4>
                </div>

                <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-rose-500/20 rounded-2xl overflow-hidden shadow-2xl relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-rose-500/5 rounded-full blur-3xl pointer-events-none -z-10"></div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-white/5 bg-rose-500/5">
                                    <th class="px-6 py-4 text-xs font-semibold text-rose-300 uppercase tracking-wider">No. Transaksi</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-rose-300 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-rose-300 uppercase tracking-wider">Customer / Tujuan</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-rose-300 uppercase tracking-wider">Petugas</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-rose-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-rose-300 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-slate-300">
                                @forelse ($outgoing as $transaction)
                                <tr class="hover:bg-rose-500/5 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-white font-medium">
                                        {{ $transaction->transaction_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-400">
                                        {{ $transaction->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-white">
                                        {{ $transaction->customer_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-400">
                                        {{ $transaction->user->name }}
                                    </td>
                                    {{-- Tampilan Status Badge --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                                'approved' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                'shipped' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            ];
                                            $badgeClass = $statusColors[$transaction->status] ?? 'bg-slate-500/10 text-slate-400 border-white/10';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full border text-xs font-bold uppercase tracking-wide {{ $badgeClass }}">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('transactions.show', $transaction) }}" class="text-slate-400 hover:text-rose-400 transition-colors p-1" title="Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            {{-- Tombol Edit --}}
                                            @if($transaction->status === 'pending')
                                                <a href="{{ route('transactions.edit', $transaction) }}" class="text-slate-400 hover:text-blue-400 transition-colors p-1" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada data barang keluar.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($outgoing->hasPages())
                    <div class="px-6 py-4 border-t border-white/5">
                        {{ $outgoing->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
