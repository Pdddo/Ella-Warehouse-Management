<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Detail Restock Order') }}
            </h2>
        </div>
    </x-slot>

    <a href="{{ route('restock-orders.index') }}" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali
    </a>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl overflow-hidden relative">

                <div class="p-8 border-b border-white/5 bg-white/5 flex flex-col md:flex-row justify-between items-start gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-white font-mono tracking-tight">
                            {{ $restockOrder->po_number }}
                        </h3>
                        <p class="text-sm text-slate-400 mt-1">
                            Dibuat pada: {{ $restockOrder->order_date->format('d M Y') }}
                        </p>
                    </div>
                    <div class="mt-2 md:mt-0">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                'confirmed' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                'in_transit' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                                'received' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                'cancelled' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                            ];
                            $bgClass = $statusClasses[$restockOrder->status] ?? 'bg-slate-500/20 text-slate-400';
                        @endphp
                        <span class="px-4 py-1.5 inline-flex text-sm font-bold rounded-xl border {{ $bgClass }}">
                            {{ ucfirst(str_replace('_', ' ', $restockOrder->status)) }}
                        </span>
                    </div>
                </div>

                <div class="p-8 text-slate-300">
                    @include('partials.alert')

                    <div class="grid grid-cols-2 gap-x-8 gap-y-6 mb-8">
                        <div>
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Dibuat Oleh</h4>
                            <p class="text-base font-medium text-white">{{ $restockOrder->manager->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Supplier</h4>
                            <p class="text-base font-medium text-white">{{ $restockOrder->supplier->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Perkiraan Tiba</h4>
                            <p class="text-base font-medium text-emerald-400">{{ $restockOrder->expected_delivery_date ? $restockOrder->expected_delivery_date->format('d M Y') : 'Tidak ditentukan' }}</p>
                        </div>
                        @if($restockOrder->notes)
                        <div class="col-span-2 bg-white/5 p-4 rounded-xl border border-white/5">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Catatan</h4>
                            <p class="text-sm italic text-slate-300">{{ $restockOrder->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-white mb-4">Detail Produk</h3>
                    <div class="relative overflow-x-auto rounded-xl border border-white/10">
                        <table class="w-full text-sm text-left text-slate-300">
                            <thead class="text-xs text-slate-400 uppercase bg-white/5">
                                <tr>
                                    <th class="px-6 py-3">Nama Produk</th>
                                    <th class="px-6 py-3 text-right">Jumlah</th>
                                    <th class="px-6 py-3 text-right">Unit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse ($restockOrder->details as $detail)
                                    <tr class="bg-[#0a0a0f]/30 hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 font-medium text-white">{{ $detail->product->name }}</td>
                                        <td class="px-6 py-4 text-right font-mono text-violet-300">{{ $detail->quantity }}</td>
                                        <td class="px-6 py-4 text-right text-slate-500">{{ $detail->product->unit ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-6 text-slate-500">Tidak ada detail produk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Rating & Feedback --}}
                    <div class="mt-8 bg-gradient-to-br from-white/5 to-white/0 p-6 rounded-2xl border border-white/10">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            Rating & Feedback Supplier
                        </h3>

                        @if($restockOrder->rating)
                            {{-- Tampilkan Rating --}}
                            <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                                <div class="flex items-center mb-2">
                                    <span class="text-slate-400 mr-2 text-sm uppercase font-bold">Rating:</span>
                                    <div class="flex text-yellow-400 gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-xl">{{ $i <= $restockOrder->rating ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                    <span class="ml-2 font-bold text-white">({{ $restockOrder->rating }}/5)</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block mb-1 text-sm uppercase font-bold">Feedback:</span>
                                    <p class="text-slate-300 italic">"{{ $restockOrder->supplier_feedback ?? 'Tidak ada pesan feedback.' }}"</p>
                                </div>
                            </div>

                        @elseif($restockOrder->status === 'received' && auth()->user()->role === 'manager')
                            {{-- Form Rating (Hanya Manager) --}}
                            <form action="{{ route('restock-orders.rate', $restockOrder) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Berikan Rating</label>
                                    <div class="flex gap-4">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer flex flex-col items-center group">
                                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                                <span class="text-2xl text-slate-600 peer-checked:text-yellow-400 group-hover:text-yellow-500/50 transition-colors">★</span>
                                                <span class="text-xs text-slate-500 peer-checked:text-yellow-400">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                <div>
                                    <label for="supplier_feedback" class="block text-sm font-medium text-slate-300 mb-2">Feedback (Opsional)</label>
                                    <textarea name="supplier_feedback" id="supplier_feedback" rows="3"
                                        class="block w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-yellow-500 focus:border-yellow-500 placeholder-slate-600"
                                        placeholder="Bagaimana performa supplier ini?"></textarea>
                                </div>
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-black font-bold px-6 py-2 rounded-xl transition shadow-lg shadow-yellow-500/20">
                                    Kirim Rating
                                </button>
                            </form>
                        @else
                            <div class="p-4 rounded-xl bg-white/5 border border-dashed border-white/10 text-center">
                                <p class="text-slate-500 text-sm">
                                    @if($restockOrder->status !== 'received')
                                        Rating tersedia setelah barang diterima.
                                    @else
                                        Belum ada rating yang diberikan.
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>


                    {{-- PANEL AKSI UNTUK MANAGER (Ganti Status) --}}
                    @if (auth()->user()->role === 'manager')
                        <div class="mt-8 pt-6 border-t border-white/10">
                            <h3 class="text-lg font-bold text-white mb-4">Aksi Manager</h3>
                            <div class="flex flex-wrap items-center gap-4">
                                @if ($restockOrder->status === 'confirmed')
                                    <form method="POST" action="{{ route('restock-orders.updateStatus', $restockOrder) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="in_transit">
                                        <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-xl shadow-lg shadow-purple-500/20 transition-all">
                                            Tandai "Dalam Perjalanan"
                                        </button>
                                    </form>
                                @elseif ($restockOrder->status === 'in_transit')
                                    <form method="POST" action="{{ route('restock-orders.updateStatus', $restockOrder) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="received">
                                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
                                            Tandai "Telah Diterima"
                                        </button>
                                    </form>
                                @else
                                    <p class="text-slate-500 text-sm italic">Tidak ada aksi lanjutan untuk status ini.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- PANEL AKSI UNTUK SUPPLIER (Konfirmasi / Tolak) --}}
                    @if (auth()->user()->role === 'supplier')
                        <div class="mt-8 pt-6 border-t border-white/10">
                            <h3 class="text-lg font-bold text-white mb-4">Aksi Supplier</h3>

                            @if($restockOrder->status === 'pending')
                                <div class="flex flex-wrap items-center gap-4">
                                    <form method="POST" action="{{ route('supplier.orders.confirm', $restockOrder) }}">
                                        @csrf
                                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all" onclick="return confirm('Terima pesanan ini?')">
                                            Terima Pesanan
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('supplier.orders.deny', $restockOrder) }}">
                                        @csrf
                                        <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow-lg shadow-rose-500/20 transition-all" onclick="return confirm('Tolak pesanan ini?')">
                                            Tolak Pesanan
                                        </button>
                                    </form>
                                </div>
                            @else
                                <p class="text-slate-500 text-sm italic">Anda sudah memproses pesanan ini.</p>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
