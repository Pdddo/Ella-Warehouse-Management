<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Buat Restock Order Baru') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="restockOrderForm(null)">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-8 relative overflow-hidden">

                <form method="POST" action="{{ route('restock-orders.store') }}">
                    @csrf

                    @include('partials.alert')

                    <header class="mb-8 border-b border-white/5 pb-4">
                        <h3 class="text-xl font-bold text-white">Detail Pesanan</h3>
                        <p class="text-slate-400 text-sm">Isi informasi supplier dan barang yang akan dipesan.</p>
                    </header>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <x-input-label for="supplier_id" :value="__('Pilih Supplier')" class="text-slate-300" />
                            <select name="supplier_id" id="supplier_id" class="mt-1 block w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500" required>
                                <option value="" class="bg-[#0a0a0f] text-slate-500">Pilih Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" class="bg-[#0a0a0f]">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="order_date" :value="__('Tanggal Order')" class="text-slate-300" />
                            <input type="date" id="order_date" name="order_date" x-model="orderDate" class="mt-1 block w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500" required>
                        </div>
                        <div>
                            <x-input-label for="expected_delivery_date" :value="__('Perkiraan Tiba')" class="text-slate-300" />
                            <input type="date" id="expected_delivery_date" name="expected_delivery_date" class="mt-1 block w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500" :min="orderDate">
                        </div>
                    </div>

                    <div class="bg-white/5 rounded-xl p-6 border border-white/5 mb-8">
                        <h3 class="text-lg font-bold text-white mb-4">Tambah Produk</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-medium text-slate-400 mb-1">Produk</label>
                                <select x-model="selectedProductId" class="block w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="" class="bg-[#0a0a0f] text-slate-500">Pilih Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-name="{{ $product->name }}" class="bg-[#0a0a0f]">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1">Jumlah</label>
                                <input type="number" x-model.number="quantity" min="1" class="block w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <button type="button" @click="addProduct()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-medium transition-colors shadow-lg shadow-emerald-500/20">
                                + Tambah
                            </button>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-white mb-3">Daftar Item Order</h3>
                    <div class="relative overflow-x-auto rounded-xl border border-white/10 bg-[#0a0a0f]/30 mb-6">
                        <table class="w-full text-sm text-left text-slate-300">
                            <thead class="text-xs text-slate-400 uppercase bg-white/5">
                                <tr>
                                    <th class="px-6 py-3">Nama Produk</th>
                                    <th class="px-6 py-3">Jumlah</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 font-medium text-white">
                                            <input type="hidden" :name="'products[' + index + '][id]'" :value="item.id">
                                            <span x-text="item.name"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="hidden" :name="'products[' + index + '][quantity]'" :value="item.quantity">
                                            <span x-text="item.quantity" class="font-mono text-violet-300"></span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button type="button" @click="removeItem(index)" class="text-rose-400 hover:text-rose-300 hover:underline transition-colors">Hapus</button>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="items.length === 0">
                                    <tr><td colspan="3" class="text-center py-8 text-slate-500">Belum ada produk yang ditambahkan.</td></tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-8">
                        <x-input-label for="notes" :value="__('Catatan (Opsional)')" class="text-slate-300" />
                        <textarea id="notes" name="notes" x-model="notes" rows="3" class="mt-1 block w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-violet-500 focus:border-violet-500 placeholder-slate-600" placeholder="Catatan tambahan untuk supplier..."></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-white/5">
                        <a href="{{ route('restock-orders.index') }}" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 hover:scale-[1.02] transition-all disabled:opacity-50 disabled:cursor-not-allowed" x-bind:disabled="items.length === 0">
                            Simpan Restock Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function restockOrderForm(initialData) {
        return {
            selectedProductId: '',
            quantity: 1,
            orderDate: initialData?.order_date || '{{ date('Y-m-d') }}',
            notes: initialData?.notes || '',
            items: initialData?.items || [],

            addProduct() {
                if (!this.selectedProductId || this.quantity <= 0) {
                    alert('Silakan pilih produk dan masukkan jumlah yang valid.');
                    return;
                }
                if (this.items.some(item => item.id == this.selectedProductId)) {
                    alert('Produk ini sudah ada di dalam daftar.');
                    return;
                }
                const selectedOption = document.querySelector(`select[x-model='selectedProductId'] option[value='${this.selectedProductId}']`);
                this.items.push({
                    id: this.selectedProductId,
                    name: selectedOption.dataset.name,
                    quantity: this.quantity,
                });
                this.selectedProductId = '';
                this.quantity = 1;
            },
            removeItem(index) {
                this.items.splice(index, 1);
            }
        }
    }
    </script>
</x-app-layout>
