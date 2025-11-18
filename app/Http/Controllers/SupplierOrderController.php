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
        $orders = RestockOrder::with('manager')
            ->where('supplier_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('supplier.dashboard', compact('orders'));
    }

    
    // Supplier mengkonfirmasi sebuah order.
    public function confirm(RestockOrder $restockOrder)
    {
        // Validasi: Pastikan order ini milik supplier yang sedang login
        if ($restockOrder->supplier_id !== Auth::id() || $restockOrder->status !== 'pending') {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        $restockOrder->update(['status' => 'confirmed']);
        return back()->with('success', 'Order berhasil dikonfirmasi.');
    }

    
    // Supplier menolak sebuah order.
    public function deny(RestockOrder $restockOrder)
    {
        // Validasi
        if ($restockOrder->supplier_id !== Auth::id() || $restockOrder->status !== 'pending') {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        $restockOrder->update(['status' => 'denied']);
        return back()->with('success', 'Order telah ditolak.');
    }
}