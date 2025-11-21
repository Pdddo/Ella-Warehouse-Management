<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\RestockOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Menentukan dashboard mana yang akan ditampilkan berdasarkan peran pengguna.
     */
    public function index()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return $this->adminDashboard();
            case 'manager':
                return $this->managerDashboard();
            case 'staff':
                return $this->staffDashboard();
            case 'supplier':
                return $this->supplierDashboard();
            default:
                return view('dashboard');
        }
    }

    // Data dan view untuk dashboard Admin.
    private function adminDashboard()
    {
        $totalProducts = Product::count();
        $transactionsThisMonth = Transaction::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        $totalStockValue = DB::table('products')->sum(DB::raw('stock * sell_price'));

        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->orderBy('stock', 'asc')->limit(5)->get();

        $pendingSuppliers = User::where('role', 'supplier')
                                ->where('is_approved', false)
                                ->latest()
                                ->get();
        return view('dashboards.admin', compact('totalProducts', 'transactionsThisMonth', 'totalStockValue', 'lowStockProducts', 'pendingSuppliers'));
    }

    // Data dan view untuk dashboard Warehouse Manager.
    private function managerDashboard()
    {
        $totalItems = Product::sum('stock');
        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->count();
        
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        $ongoingRestocks = RestockOrder::whereIn('status', ['pending', 'confirmed', 'in_transit'])->latest()->get();

        return view('dashboards.manager', compact('totalItems', 'lowStockCount', 'recentTransactions', 'ongoingRestocks'));
    }

    // Data dan view untuk dashboard Staff Gudang.
    private function staffDashboard()
    {
        $todayTransactions = Transaction::with('user', 'details.product')
                                        ->whereDate('created_at', today())
                                        ->latest()
                                        ->get();
        
        return view('dashboards.staff', compact('todayTransactions'));
    }

    // Data dan view untuk dashboard Supplier.
    
    private function supplierDashboard()
    {
        $user = Auth::user();
        // Pesanan yang menunggu konfirmasi dari supplier
        $pendingConfirmationOrders = RestockOrder::select('restock_orders.*', 'users.name as manager_name')
            ->join('users', 'restock_orders.manager_id', '=', 'users.id')
            ->where('restock_orders.supplier_id', $user->id)
            ->where('restock_orders.status', 'pending')
            ->latest('restock_orders.created_at')
            ->get();

        $shipmentHistory = RestockOrder::select('restock_orders.*', 'users.name as manager_name')
            ->join('users', 'restock_orders.manager_id', '=', 'users.id')
            ->where('restock_orders.supplier_id', $user->id)
            ->where('restock_orders.status', 'received')
            ->latest('restock_orders.created_at')
            ->paginate(5);

        return view('dashboards.supplier', compact('pendingConfirmationOrders', 'shipmentHistory'));
    }


    public function approveSupplier($id)
    {
        $user = User::findOrFail($id);
        
        //pastikan dulu user tersebut betul supplier
        if ($user->role !== 'supplier') {
            return back()->with('error', 'User ini bukan supplier.');
        }

        $user->update(['is_approved' => true]);

        return back()->with('success', 'Akun supplier ' . $user->name . ' berhasil disetujui.');
    }
}