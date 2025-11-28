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
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        $orders = $query->paginate(10)->withQueryString();
        return view('restock_orders.index', compact('orders'));
    }

    // Menampilkan form untuk membuat pesanan restock baru.
    public function create()
    {
        $suppliers = User::where('role', 'supplier')->orderBy('name')->get();
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
        ]);

        try {
            DB::beginTransaction();
            $order = RestockOrder::create([
                'po_number' => 'PO-' . date('Ym') . '-' . Str::random(6),
                'manager_id' => Auth::id(),
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);
            foreach ($request->products as $product) {
                $order->details()->create([
                    'product_id' => $product['id'],
                    'quantity'   => $product['quantity'],
                ]);
            }
            DB::commit();
            return redirect()->route('restock-orders.index')->with('success', 'Restock order berhasil dibuat.');
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

    // Menampilkan form untuk mengedit pesanan restock.
    public function edit(RestockOrder $restockOrder)
    {
        // Aturan Bisnis: Hanya order 'pending' yang boleh diedit.
        if ($restockOrder->status !== 'pending') {
            return redirect()->route('restock-orders.show', $restockOrder)->with('error', 'Hanya order dengan status "pending" yang dapat diedit.');
        }

        $restockOrder->load('details'); // Muat detail yang ada
        $suppliers = User::where('role', 'supplier')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('restock_orders.edit', compact('restockOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, RestockOrder $restockOrder)
    {
        // Aturan Bisnis: Hanya order 'pending' yang boleh diupdate.
        if ($restockOrder->status !== 'pending') {
            return redirect()->route('restock-orders.show', $restockOrder)->with('error', 'Hanya order dengan status "pending" yang dapat diupdate.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Update data utama pada order
            $restockOrder->update([
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'notes' => $request->notes,
            ]);

            // Sinkronisasi detail produk: hapus yang lama, buat yang baru.
            $restockOrder->details()->delete();
            foreach ($request->products as $product) {
                $restockOrder->details()->create([
                    'product_id' => $product['id'],
                    'quantity'   => $product['quantity'],
                ]);
            }

            DB::commit();

            return redirect()->route('restock-orders.show', $restockOrder)->with('success', 'Restock order berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui order: ' . $e->getMessage())->withInput();
        }
    }

    public function storeRating(Request $request, RestockOrder $restockOrder)
    {
        // Validasi: Pastikan user adalah manager (sudah dihandle middleware, tapi double check aman)
        if (auth()->user()->role !== 'manager') {
            abort(403);
        }

        // Validasi: Sesuai modul, rating hanya boleh jika status "Received"
        if ($restockOrder->status !== 'received') {
            return back()->with('error', 'Rating hanya dapat diberikan setelah barang diterima (Status: Received).');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5', // Skala 1-5
            'supplier_feedback' => 'nullable|string|max:500',
        ]);

        $restockOrder->update([
            'rating' => $request->rating,
            'supplier_feedback' => $request->supplier_feedback,
        ]);

        return back()->with('success', 'Rating dan feedback untuk supplier berhasil disimpan.');
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
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_transit,received,cancelled'
        ]);
        
        $restockOrder->update(['status' => $request->status]);

        return redirect()->route('restock-orders.index')->with('success', 'Status order berhasil diubah menjadi ' . ucfirst($request->status) . '.');
    }
}