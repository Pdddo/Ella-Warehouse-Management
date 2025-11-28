<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl mb-8 shadow-2xl overflow-hidden">
                <div class="p-6 bg-orange-500/10 border-b border-orange-500/20">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-500/20 rounded-lg text-orange-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-orange-400">
                            Order Perlu Konfirmasi ({{ $pendingConfirmationOrders->count() }})
                        </h3>
                    </div>
                </div>

                <div class="p-6">
                    @if($pendingConfirmationOrders->isEmpty())
                        <p class="text-slate-500 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Tidak ada order baru yang menunggu konfirmasi.
                        </p>
                    @else
                        <ul class="divide-y divide-white/5">
                            @foreach ($pendingConfirmationOrders as $order)
                                <li class="py-4 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('restock-orders.show', $order) }}" class="text-lg font-bold text-white font-mono hover:text-violet-400 transition-colors">{{ $order->po_number }}</a>
                                            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-orange-500/20 text-orange-400 border border-orange-500/30">Pending</span>
                                        </div>
                                        <p class="text-sm text-slate-400 mt-1">Dipesan oleh <span class="text-slate-200">{{ $order->manager->name }}</span> • {{ $order->order_date->format('d M Y') }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('supplier.orders.show', $order) }}" class="px-4 py-2 bg-white/5 hover:bg-violet-500/10 text-violet-400 border border-white/10 hover:border-violet-500/30 text-sm font-semibold rounded-xl transition-all">
                                            Detail
                                        </a>

                                        <form action="{{ route('supplier.orders.confirm', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/20 transition-all hover:scale-105">
                                                Confirm
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('supplier.orders.deny', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-white/5 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 border border-white/10 hover:border-rose-500/30 text-sm font-semibold rounded-xl transition-all">
                                                Deny
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-xl">
                <h3 class="text-lg font-bold text-white mb-6">Riwayat Proses Order</h3>
                
                @if($shipmentHistory->isEmpty())
                    <p class="text-slate-500 text-sm">Belum ada riwayat order yang diproses.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="text-xs text-slate-400 uppercase bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 rounded-l-lg">PO Number</th>
                                    <th class="px-6 py-3">Pemesan</th>
                                    <th class="px-6 py-3">Tanggal Order</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 rounded-r-lg text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($shipmentHistory as $order)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 font-mono font-medium text-slate-300">
                                                {{ $order->po_number }}
                                        </td>
                                        <td class="px-6 py-4 text-white">{{ $order->manager_name }}</td>
                                        <td class="px-6 py-4 text-slate-400">{{ $order->order_date->format('d F Y') }}</td>
                                        
                                        <td class="px-6 py-4">
                                            @php
                                                $statusClasses = [
                                                    'confirmed' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                    'in_transit' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                                    'received' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                    'cancelled' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                                ];
                                                $bgClass = $statusClasses[$order->status] ?? 'bg-slate-500/10 text-slate-400';
                                            @endphp
                                            <span class="px-2 py-1 rounded text-[10px] uppercase font-bold border {{ $bgClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('supplier.orders.show', $order) }}" 
                                                class="px-3 py-1.5 bg-white/5 hover:bg-violet-500/20 text-violet-400 border border-white/10 hover:border-violet-500/30 text-xs font-semibold rounded-lg transition-all inline-flex items-center gap-1">
                                                <span>Detail</span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 border-t border-white/5 pt-4">
                        {{ $shipmentHistory->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>