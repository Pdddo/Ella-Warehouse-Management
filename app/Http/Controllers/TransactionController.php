<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // Menampilkan halaman utama transaksi yang berisi daftar barang masuk dan keluar.
    public function index(Request $request)
    {
        // Ambil transaksi barang masuk
        $incoming = Transaction::with('user')
            ->where('type', 'incoming')
            ->latest()
            ->paginate(10, ['*'], 'incoming_page');

        // Ambil transaksi barang keluar
        $outgoing = Transaction::with('user')
            ->where('type', 'outgoing')
            ->latest()
            ->paginate(10, ['*'], 'outgoing_page');

        return view('transactions.index', compact('incoming', 'outgoing'));
    }

    
    // Menampilkan form untuk membuat transaksi barang masuk baru.

    public function createIncoming()
    {
        $products = Product::orderBy('name')->get();
        return view('transactions.create_incoming', compact('products'));
    }


    // Menyimpan transaksi barang masuk dan mengupdate stok produk.
    public function storeIncoming(Request $request)
    {
        // Validasi input
        $request->validate([
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ], [
            'products.required' => 'Anda harus menambahkan minimal satu produk.',
        ]);

        try {
            // Gunakan DB Transaction untuk memastikan konsistensi data
            DB::beginTransaction();

            // Buat record transaksi utama
            $transaction = Transaction::create([
                'transaction_number' => 'IN-' . time() . Str::upper(Str::random(4)),
                'user_id' => Auth::id(),
                'type' => 'incoming',
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            // Loop setiap produk yang ditambahkan
            foreach ($request->products as $item) {
                // Tambahkan detail transaksi
                $transaction->details()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);

                // Update stok produk
                $product = Product::find($item['id']);
                $product->increment('stock', $item['quantity']);
            }

            // Jika semua berhasil, commit transaksi
            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi barang masuk berhasil dicatat.');

        } catch (\Exception $e) {
            // Jika ada error, rollback semua perubahan
            DB::rollBack();
            
            // Redirect kembali dengan pesan error
            return back()->with('error', 'Terjadi kesalahan saat mencatat transaksi. Error: ' . $e->getMessage())->withInput();
        }
    }

    public function createOutgoing()
    {
        // Hanya tampilkan produk yang memiliki stok > 0
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('transactions.create_outgoing', compact('products'));
    }

    // Menyimpan transaksi barang keluar dan mengurangi stok produk.
    public function storeOutgoing(Request $request)
    {
        // Validasi input
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ], [
            'products.required' => 'Anda harus menambahkan minimal satu produk.',
            'customer_name.required' => 'Nama pelanggan tidak boleh kosong.',
        ]);

        try {
            DB::beginTransaction();

            // Loop pertama: Validasi Stok sebelum melakukan perubahan apapun
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                if ($product->stock < $item['quantity']) {
                    // Jika stok tidak mencukupi, batalkan semuanya
                    throw new \Exception("Stok produk '{$product->name}' tidak mencukupi. Sisa stok: {$product->stock}.");
                }
            }

            // 1. Buat record transaksi utama
            $transaction = Transaction::create([
                'transaction_number' => 'OUT-' . time() . Str::upper(Str::random(4)),
                'user_id' => Auth::id(),
                'type' => 'outgoing',
                'status' => 'completed',
                'customer_name' => $request->customer_name,
                'notes' => $request->notes,
            ]);

            // 2. Loop kedua: Buat detail transaksi dan kurangi stok
            foreach ($request->products as $item) {
                // Tambahkan detail transaksi
                $transaction->details()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);

                // Kurangi stok produk
                $product = Product::find($item['id']);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaksi barang keluar berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return
            back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }
}