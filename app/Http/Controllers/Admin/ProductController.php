<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class ProductController extends Controller
{
    //
    public function index()
    {
        //produk relasi dengan kategori
        $products = Product::with('category')->get();
        return view('pages.products.index', compact('products'));
    }

    // Tampilkan form create produk relasi dengan kategori
    public function create()
    {
        // masukkan data kategori ke dalam variabel categories
        $categories = Category::all();
        return view('pages.products.create', compact('categories'));
    }

    // Simpan data produk
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sku' => 'required|string|max:255',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);

        Product::create($request->all());
        return redirect()->route('products.index');
    }

    //edit produk
    public function edit(Product $product)
    {
        $product = Product::findOrFail($product->id);
        //hapus desimal
        $product->price = (int) $product->price;
        $categories = Category::all();
        return view('pages.products.edit', compact('product', 'categories'));
    }

    //update produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sku' => 'required|string|max:255',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id'
        ]);

        Product::where('id', $product->id)->update($request->only(['name', 'description', 'sku', 'price', 'stock', 'category_id']));
        return redirect()->route('products.index');
    }

    //hapus produk
    public function destroy(Product $product)
    {
        Product::destroy($product->id);
        return redirect()->route('products.index');
    }
}
