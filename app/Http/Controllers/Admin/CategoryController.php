<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Tampilkan data kategori
    public function index()
    {
        $categories = Category::all();
        return view('pages.categories.index', compact('categories'));
    }

    // Tampilkan form create kategori
    public function create()
    {
        return view('pages.categories.create');
    }

    // Simpan data kategori
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index'); // Perbaikan di sini
    }

    //destroy kategori
    public function destroy(Category $category)
    {

        // Cek apakah ada produk yang menggunakan kategori ini
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('warning', 'Kategori ini sedang digunakan oleh produk lain dan tidak bisa dihapus.');
        }

        // Jika tidak ada produk yang menggunakan kategori, lanjutkan penghapusan
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    //edit kategori
    public function edit(Category $category)
    {
        $category = Category::findOrFail($category->id);
        return view('pages.categories.edit', compact('category'));
    }

    //update kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index');
    }
}
