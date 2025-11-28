<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Manajemen Restock Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-white">Pesanan Restock</h3>
                    <p class="text-slate-400 text-sm">Kelola pemesanan barang ke supplier</p>
                </div>

                @if(auth()->user()->role !== 'supplier')
                <a href="{{ route('restock-orders.create') }}" class="px-5 py-2.5 bg-emerald-500/20 text-emerald-400 font-medium rounded-xl flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Order Baru
                </a>
                @endif
            </div>

            @include('partials.alert')

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl overflow-hidden shadow-2xl relative">
                
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/5">
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">PO Number</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Supplier</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Order</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Manager</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-slate-300">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-white font-bold">
                                        {{ $order->po_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded bg-emerald-500/20 flex items-center justify-center text-emerald-400 text-xs font-bold">
                                                {{ substr($order->supplier->name ?? '?', 0, 1) }}
                                            </div>
                                            <span class="text-slate-300">{{ $order->supplier->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-400">
                                        {{ $order->order_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                                'confirmed' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                'in_transit' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                                'received' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'cancelled' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            ];
                                            $bgClass = $statusClasses[$order->status] ?? 'bg-slate-500/10 text-slate-400';
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg border {{ $bgClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-400">
                                        {{ $order->manager->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('restock-orders.show', $order) }}" class="text-slate-400 hover:text-violet-400 transition-colors">Detail</a>
                                            @if ($order->status === 'pending')
                                                <a href="{{ route('restock-orders.edit', $order) }}" class="text-slate-400 hover:text-blue-400 transition-colors">Edit</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        Tidak ada restock order yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-white/5">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>