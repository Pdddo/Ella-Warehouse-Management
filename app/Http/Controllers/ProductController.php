<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    
    // Menampilkan daftar semua produk dengan fungsionalitas pencarian dan paginasi.
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        // Logika untuk pencarian berdasarkan nama atau SKU
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        }

        // menghitung total stok
        $totalStock = Product::sum('stock');

        $products = $query->paginate(10)->withQueryString();

        return view('products.index', compact('products', 'totalStock'));
    }


    // Menampilkan form untuk membuat produk baru.
    // Mengirimkan data kategori untuk pilihan dropdown.
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    // Menyimpan produk baru ke dalam database.
    public function store(Request $request)
    {
        // Validasi input sesuai persyaratan
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'rack_location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate SKU unik
        $validated['sku'] = 'SKU-' . mt_rand(1000, 9999) . '-' . Str::lower(Str::random(4));

        // Logika untuk upload gambar
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product_images', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    
    // Menampilkan detail satu produk.
    public function show(Product $product)
    {
        // Eager load relasi untuk ditampilkan di halaman detail
        $product->load(['category', 'transactionDetails.transaction' => function ($query) {
            $query->latest()->limit(5);
        }]);
        
        return view('products.show', compact('product'));
    }

    //Menampilkan form untuk mengedit produk.
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    
    // Mengupdate data produk di database.
    public function update(Request $request, Product $product)
    {
        // Validasi (SKU tidak dapat diubah)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'rack_location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Logika untuk update gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('product_images', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    
    // Menghapus produk dari database
    public function destroy(Product $product)
    {
        // Validasi: tidak bisa hapus jika masih ada stok
        if ($product->stock > 0) {
            return back()->with('error', 'Gagal! Produk tidak dapat dihapus karena masih memiliki stok.');
        }

        // Hapus gambar dari storage
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}