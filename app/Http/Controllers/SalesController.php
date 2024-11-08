<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\Session;

class SalesController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('pages.sales.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'exists:products,id',
            'products.*.quantity' => 'integer|min:1'
        ]);

        // Buat transaksi penjualan baru
        $sale = Sale::create([
            'total_amount' => 0, // ini akan dihitung nanti
        ]);

        $totalAmount = 0;

        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $quantity = $productData['quantity'];
            $subtotal = $product->price * $quantity;

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ]);

            $totalAmount += $subtotal;
        }

        // Update total harga di penjualan
        $sale->update(['total_amount' => $totalAmount]);

        return redirect()->route('sales.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // Ambil data produk dari database
        $product = Product::find($productId);
        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        // Ambil keranjang dari session, atau inisialisasi array kosong jika belum ada
        $cart = Session::get('cart', []);

        // Jika produk sudah ada di keranjang, tambahkan quantity-nya
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // Jika produk belum ada di keranjang, tambahkan sebagai item baru
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
            ];
        }

        // Simpan kembali ke session
        Session::put('cart', $cart);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function removeFromCart($productId)
    {
        // Ambil keranjang dari session
        $cart = Session::get('cart', []);

        // Hapus item produk berdasarkan product_id
        unset($cart[$productId]);

        // Simpan kembali ke session
        Session::put('cart', $cart);

        return back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong.');
        }

        // Buat transaksi penjualan
        $sale = Sale::create(['total_amount' => 0]);
        $totalAmount = 0;

        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ]);
            $totalAmount += $subtotal;
        }

        // Update total amount di penjualan
        $sale->update(['total_amount' => $totalAmount]);

        // Hapus keranjang dari session setelah checkout selesai
        Session::forget('cart');

        return redirect()->route('sales.index')->with('success', 'Checkout berhasil!');
    }
}
