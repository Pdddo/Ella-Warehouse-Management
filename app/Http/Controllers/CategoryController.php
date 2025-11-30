<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan daftar semua kategori.
    public function index()
    {
        // ambil data kategori beserta jumlah produk di setiap kategori baru urutkan dari yang paling baru
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    // Menampilkan form untuk membuat kategori baru.
    public function create()
    {
        return view('categories.create');
    }

    // Menyimpan kategori baru ke database.
    public function store(Request $request)
    {
        //cek validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function show(Category $category)
    {
        // ambil produk yg tersambung dengan kategori
        $products = $category->products()->latest()->paginate(10);

        return view('categories.show', compact('category', 'products'));
    }

    // Menampilkan form untuk mengedit kategori.
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }


    // Mengupdate kategori yang ada di database.
    public function update(Request $request, Category $category)
    {
        //cek validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Menghapus kategori dari database.
    public function destroy(Category $category)
    {
        // cek apakah kategori masih memiliki produk sebelum dihapus.
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
