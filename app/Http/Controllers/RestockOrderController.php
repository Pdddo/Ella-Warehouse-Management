<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\RestockOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RestockOrderController extends Controller
{
    // Menampilkan daftar semua pesanan restock.
    public function index(Request $request)
    {
        $query = RestockOrder::with(['supplier', 'manager'])->latest();

        // Fitur filter berdasarkan status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();
        return view('restock_orders.index', compact('orders'));
    }

    // Menampilkan form untuk membuat pesanan restock baru.
    public function create()
    {
        // Ambil daftar user dengan peran 'supplier'
        $suppliers = User::where('role', 'supplier')->orderBy('name')->get();
        // Ambil semua produk
        $products = Product::orderBy('name')->get();

        return view('restock_orders.create', compact('suppliers', 'products'));
    }

    // Menyimpan pesanan restock baru ke database.
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit' => 'required|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $order = RestockOrder::create([
                'po_number' => 'PO-' . date('Ym') . '-' . Str::upper(Str::random(5)),
                'manager_id' => Auth::id(), // Manager yang sedang login
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'notes' => $request->notes,
                'status' => 'pending', // Status awal adalah 'pending'
            ]);

            foreach ($request->products as $item) {
                $order->details()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                ]);
            }

            DB::commit();

            return redirect()->route('restock-orders.index')->with('success', 'Order restock berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat order: ' . $e->getMessage())->withInput();
        }
    }

    // Menampilkan detail satu pesanan restock.
    public function show(RestockOrder $restockOrder)
    {
        $restockOrder->load(['supplier', 'manager', 'details.product']);
        return view('restock_orders.show', compact('restockOrder'));
    }

    /**
     * Menampilkan form untuk mengedit pesanan restock (jika diperlukan).
     * Catatan: Biasanya order yang sudah dibuat jarang diedit, lebih sering dibatalkan.
     * Untuk saat ini kita skip fungsionalitas edit.
     */
    public function edit(RestockOrder $restockOrder)
    {
        // Fitur edit bisa dikembangkan di kemudian hari jika diperlukan.
        // Contoh: hanya bisa edit jika status masih 'pending'.
        return redirect()->route('restock-orders.show', $restockOrder)->with('error', 'Fungsionalitas edit belum tersedia.');
    }

    // Mengupdate pesanan restock (jika diperlukan).
    public function update(Request $request, RestockOrder $restockOrder)
    {
        // Logika update
        abort(501, 'Not Implemented');
    }

    // Membatalkan pesanan restock.
    public function destroy(RestockOrder $restockOrder)
    {
        if ($restockOrder->status !== 'pending') {
            return back()->with('error', 'Hanya order dengan status "pending" yang dapat dibatalkan.');
        }

        $restockOrder->update(['status' => 'cancelled']);

        return redirect()->route('restock-orders.index')->with('success', 'Order berhasil dibatalkan.');
    }

    public function updateStatus(Request $request, RestockOrder $restockOrder)
    {
    $request->validate(['status' => 'required|in:in_transit,received']);

    $newStatus = $request->status;
    $allowedStatuses = [];

    // Logika alur status
    if ($restockOrder->status === 'confirmed') {
        $allowedStatuses = ['in_transit'];
    } elseif ($restockOrder->status === 'in_transit') {
        $allowedStatuses = ['received'];
    }

    if (!in_array($newStatus, $allowedStatuses)) {
        return back()->with('error', "Status tidak dapat diubah dari '{$restockOrder->status}' ke '{$newStatus}'.");
    }

    // Jika status adalah 'received', jalankan logika penambahan stok
    if ($newStatus === 'received') {
        try {
            DB::beginTransaction();
            
            // Update status order
            $restockOrder->update(['status' => 'received']);
            
            // Buat Transaksi Barang Masuk
            $transaction = \App\Models\Transaction::create([ /* ... kode sama seperti di metode receive() lama ... */ ]);
            
            // Update stok produk
            foreach ($restockOrder->details as $detail) {
                $transaction->details()->create([/*...*/]);
                \App\Models\Product::find($detail->product_id)->increment('stock', $detail->quantity);
            }
            
            DB::commit();
            return redirect()->route('restock-orders.show', $restockOrder)->with('success', 'Order diterima dan stok telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    } else {
        $restockOrder->update(['status' => $newStatus]);
        return back()->with('success', 'Status order berhasil diperbarui.');
        }
    }
}