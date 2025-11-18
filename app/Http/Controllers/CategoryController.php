<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan daftar semua kategori.
    public function index()
    {
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
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function show(Category $category)
    {
        // Memuat kategori beserta relasi produknya, dengan paginasi untuk produk.
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
        // Validasi: Jangan biarkan kategori dihapus jika masih ada produk di dalamnya.
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}