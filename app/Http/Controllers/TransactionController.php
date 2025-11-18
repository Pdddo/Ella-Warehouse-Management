<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\RestockOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $incoming = Transaction::with('user')->where('type', 'incoming')->latest()->paginate(10, ['*'], 'incoming_page');
        $outgoing = Transaction::with('user')->where('type', 'outgoing')->latest()->paginate(10, ['*'], 'outgoing_page');

        $receivedOrders = RestockOrder::with('supplier')
                                    ->where('status', 'received')
                                    ->whereNull('processed_at')
                                    ->latest()
                                    ->get();

        return view('transactions.index', compact('incoming', 'outgoing', 'receivedOrders'));
    }

    public function createIncoming(Request $request)
    {
        $suppliers = User::where('role', 'supplier')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $prefilledOrder = null;
        if ($request->has('restock_order_id')) {
            $prefilledOrder = RestockOrder::with('details.product')->find($request->query('restock_order_id'));
        }
        return view('transactions.create_incoming', compact('suppliers', 'products', 'prefilledOrder'));
    }

    public function storeIncoming(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'transaction_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();
            $transaction = Transaction::create([
                'transaction_number' => 'TR-IN-' . date('Ymd') . '-' . \Illuminate\Support\Str::random(5),
                'user_id'          => Auth::id(),
                'type'             => 'incoming',
                'status'           => 'pending',
                'notes'            => $request->notes,
                'supplier_id'      => $request->supplier_id,
            ]);
            foreach ($request->products as $product) {
                $transaction->details()->create(['product_id' => $product['id'], 'quantity' => $product['quantity']]);
            }
            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi barang masuk berhasil dibuat dan menunggu persetujuan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function createFromRestock(RestockOrder $restockOrder)
    {
        if ($restockOrder->processed_at) {
            return redirect()->route('transactions.index')->with('error', 'Restock Order ini sudah pernah diproses sebelumnya.');
        }
        if ($restockOrder->status !== 'received') {
            return redirect()->route('transactions.index')->with('error', 'Hanya Restock Order dengan status "Received" yang bisa diproses.');
        }
        try {
            DB::beginTransaction();
            $transaction = Transaction::create([
                'transaction_number' => 'TR-IN-' . date('Ymd') . '-' . \Illuminate\Support\Str::random(5),
                'user_id'          => Auth::id(),
                'type'             => 'incoming',
                'status'           => 'pending',
                'notes'            => 'Transaksi otomatis dari Restock Order ' . $restockOrder->po_number,
                'supplier_id'      => $restockOrder->supplier_id,
            ]);
            foreach ($restockOrder->details as $detail) {
                $transaction->details()->create(['product_id' => $detail->product_id, 'quantity' => $detail->quantity]);
            }
            $restockOrder->update(['processed_at' => now()]);
            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibuat (' . $transaction->transaction_number . ') dan menunggu persetujuan Manager.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transactions.index')->with('error', 'Gagal membuat transaksi otomatis: ' . $e->getMessage());
        }
    }
    
    // Metode show dan approve tetap sama seperti sebelumnya
    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'supplier', 'details.product', 'approvedBy');
        return view('transactions.show', compact('transaction'));
    }

    public function approve(Transaction $transaction)
    {
        if (Auth::user()->role !== 'manager') { abort(403); }
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini tidak lagi dalam status pending.');
        }
        try {
            DB::beginTransaction();
            $transaction->update([
                'status' => 'completed',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
            foreach ($transaction->details as $detail) {
                if ($transaction->type === 'incoming') {
                    $detail->product->increment('stock', $detail->quantity);
                } else {
                    $detail->product->decrement('stock', $detail->quantity);
                }
            }
            DB::commit();
            return redirect()->route('transactions.show', $transaction)->with('success', 'Transaksi berhasil disetujui dan stok telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui transaksi: ' . $e->getMessage());
        }
    }
}