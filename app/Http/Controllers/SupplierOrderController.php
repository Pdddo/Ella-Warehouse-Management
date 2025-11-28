<?php

namespace App\Http\Controllers;

use App\Models\RestockOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class SupplierOrderController extends Controller
{
    
    // Menampilkan dashboard supplier dengan daftar order yang ditugaskan kepada mereka.
    public function index()
    {
        $$user = Auth::user();

        // Ambil semua order yang supplier_id-nya adalah user yang login
        $orderss = RestockOrder::with(['manager', 'supplier'])
            ->where('supplier_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('restock_orders.index', compact('orders'));
    }

    public function show(RestockOrder $restockOrder)
    {
        // Keamanan: Pastikan supplier hanya bisa melihat order miliknya sendiri
        if ($restockOrder->supplier_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Load relasi yang dibutuhkan
        $restockOrder->load(['manager', 'supplier', 'details.product']);

        return view('restock_orders.show', compact('restockOrder'));
    }

    
    // Supplier mengkonfirmasi sebuah order.
    public function confirm(RestockOrder $restockOrder)
    {
       if ($restockOrder->supplier_id !== Auth::id()) {
            abort(403);
        }

        if ($restockOrder->status !== 'pending') {
            return back()->with('error', 'Order tidak dalam status pending.');
        }

        $restockOrder->update(['status' => 'confirmed']);

        return back()->with('success', 'Order berhasil dikonfirmasi. Segera proses pengiriman.');
    }
    
    // Supplier menolak sebuah order.
    public function deny(RestockOrder $restockOrder)
    {
        if ($restockOrder->supplier_id !== Auth::id()) {
            abort(403);
        }

        if ($restockOrder->status !== 'pending') {
            return back()->with('error', 'Order tidak dalam status pending.');
        }

        $restockOrder->update(['status' => 'cancelled']);

        return back()->with('success', 'Order telah ditolak.');
    }
}