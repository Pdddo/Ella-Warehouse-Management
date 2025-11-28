<x-app-layout>
    <script src="//unpkg.com/alpinejs" defer></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Restock Order') }}: <span class="font-mono text-violet-400">{{ $restockOrder->po_number }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div x-data="restockEditHandler()" x-init="initData()" class="bg-[#0a0a0f]/60 backdrop-blur-xl border border-white/5 rounded-2xl shadow-2xl p-8 relative overflow-hidden">
                
                <form method="POST" action="{{ route('restock-orders.update', $restockOrder->id) }}">
                    @csrf
                    @method('PUT')
                    
                    @if ($errors->any())
                        <div class="mb-6 bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-3 rounded-xl relative">
                            <strong class="font-bold">Periksa input Anda!</strong>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <x-input-label for="supplier_id" :value="__('Supplier')" class="text-slate-300" />
                            <select name="supplier_id" id="supplier_id" class="mt-1 block w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-violet-500 focus:border-violet-500" required>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $restockOrder->supplier_id) == $supplier->id ? 'selected' : '' }} class="bg-[#0a0a0f]">
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="order_date" :value="__('Tanggal Order')" class="text-slate-300" />
                            <input type="date" id="order_date" name="order_date" 
                                   value="{{ old('order_date', $restockOrder->order_date->format('Y-m-d')) }}"
                                   class="mt-1 block w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500" 
                                   required x-ref="orderDateInput"> 
                        </div>

                        <div>
                            <x-input-label for="expected_delivery_date" :value="__('Perkiraan Tiba')" class="text-slate-300" />
                            <input type="date" id="expected_delivery_date" name="expected_delivery_date"
                                   value="{{ old('expected_delivery_date', optional($restockOrder->expected_delivery_date)->format('Y-m-d')) }}"
                                   class="mt-1 block w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500"
                                   required :min="$refs.orderDateInput.value">
                        </div>
                    </div>

                    <div class="bg-white/5 rounded-xl p-6 border border-white/5 mb-8">
                        <h3 class="text-lg font-bold text-violet-400 mb-4">Update Produk</h3>
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            <div class="md:col-span-6">
                                <label class="block text-xs font-medium text-slate-400 mb-1">Pilih Produk</label>
                                <select x-model="tempProductId" id="productSelector" class="block w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="" class="bg-[#0a0a0f]">-- Pilih Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-name="{{ $product->name }}" class="bg-[#0a0a0f]">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-xs font-medium text-slate-400 mb-1">Jumlah</label>
                                <input type="number" x-model.number="tempQuantity" min="1" class="block w-full bg-[#0a0a0f] border border-white/10 text-white rounded-xl focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div class="md:col-span-3">
                                <button type="button" @click="addItem()" class="w-full px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-500 transition font-medium shadow-lg shadow-emerald-500/20">
                                    + Tambah
                                </button>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-white mb-3">Daftar Item Saat Ini</h3>
                    <div class="relative overflow-x-auto border border-white/10 rounded-xl bg-[#0a0a0f]/30 mb-6">
                        <table class="w-full text-sm text-left text-slate-300">
                            <thead class="text-xs text-slate-400 uppercase bg-white/5">
                                <tr>
                                    <th class="px-6 py-3">Nama Produk</th>
                                    <th class="px-6 py-3 text-center">Qty</th>
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
                                        <td class="px-6 py-4 text-center">
                                            <input type="hidden" :name="'products[' + index + '][quantity]'" :value="item.quantity">
                                            <span x-text="item.quantity" class="bg-emerald-500/20 text-emerald-300 text-xs font-bold px-2.5 py-0.5 rounded-md border border-emerald-500/20"></span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button type="button" @click="removeItem(index)" class="text-rose-400 hover:text-rose-300 hover:underline font-medium">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                
                                <template x-if="items.length === 0">
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                            List kosong. Silakan tambah produk di atas.
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mb-8">
                        <x-input-label for="notes" :value="__('Catatan')" class="text-slate-300" />
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full bg-[#0a0a0f]/50 border border-white/10 text-white rounded-xl focus:ring-violet-500 focus:border-violet-500">{{ old('notes', $restockOrder->notes) }}</textarea>
                    </div>
                    
                    <div class="flex items-center justify-end gap-4 border-t border-white/5 pt-6">
                        <a href="{{ route('restock-orders.index') }}" class="text-slate-400 hover:text-white transition-colors font-medium text-sm">Batal</a>
                        <x-primary-button class="bg-emerald-600 hover:bg-emerald-500 border-none shadow-lg shadow-emerald-500/20">Simpan Perubahan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const existingItems = @json($restockOrder->details->map(fn($detail) => [
            'id' => $detail->product_id,
            'name' => $detail->product->name,
            'quantity' => $detail->quantity
        ]));

        function restockEditHandler() {
            return {
                items: [],
                tempProductId: '',
                tempQuantity: 1,

                initData() {
                    this.items = existingItems;
                },

                addItem() {
                    if (!this.tempProductId) {
                        alert('Pilih produk dulu!');
                        return;
                    }
                    if (this.tempQuantity < 1) {
                        alert('Jumlah minimal 1');
                        return;
                    }
                    let exists = this.items.find(i => i.id == this.tempProductId);
                    if (exists) {
                        alert('Produk sudah ada di list. Hapus dulu jika ingin ubah jumlah.');
                        return;
                    }
                    let select = document.getElementById('productSelector');
                    let name = select.options[select.selectedIndex].getAttribute('data-name');

                    this.items.push({
                        id: this.tempProductId,
                        name: name,
                        quantity: this.tempQuantity
                    });
                    this.tempProductId = '';
                    this.tempQuantity = 1;
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                }
            }
        }
    </script>
</x-app-layout>