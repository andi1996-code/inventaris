{{-- resources/views/pages/pos.blade.php --}}
@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Produk -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Products</div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            @foreach ($products as $product)
                                <div class="card m-2" style="width: 150px;">
                                    <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">${{ number_format($product->price, 2) }}</p>
                                        <button class="btn btn-success btn-sm add-to-cart" data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keranjang Belanja -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Shopping Cart</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <!-- Daftar item akan ditambahkan secara dinamis -->
                            </tbody>
                        </table>
                        <hr>
                        <p>Subtotal: <span id="subtotal">$0.00</span></p>
                        <p>Discount: <span id="discount">$0.00</span></p>
                        <p>VAT: <span id="vat">$0.00</span></p>
                        <h5>Total: <span id="total">$0.00</span></h5>
                        <button class="btn btn-primary btn-block mt-3">Payment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function updateCartDisplay() {
        const cartItemsContainer = document.getElementById('cart-items');
        const subtotalElement = document.getElementById('subtotal');
        const discountElement = document.getElementById('discount');
        const vatElement = document.getElementById('vat');
        const totalElement = document.getElementById('total');

        cartItemsContainer.innerHTML = '';
        let subtotal = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            const row = `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>$${item.price.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="removeFromCart(${item.id})">Remove</button>
                    </td>
                </tr>
            `;
            cartItemsContainer.innerHTML += row;
        });

        const discount = subtotal > 100 ? 10 : 0; // Example: $10 discount if subtotal > $100
        const vat = subtotal * 0.10; // VAT is 10%
        const total = subtotal - discount + vat;

        subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
        discountElement.textContent = `$${discount.toFixed(2)}`;
        vatElement.textContent = `$${vat.toFixed(2)}`;
        totalElement.textContent = `$${total.toFixed(2)}`;

        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function addToCart(product) {
        const existingProduct = cart.find(item => item.id === product.id);

        if (existingProduct) {
            existingProduct.quantity += 1;
        } else {
            cart.push({
                ...product,
                quantity: 1
            });
        }

        updateCartDisplay();
        alert(`Added ${product.name} to cart!`);
    }

    function removeFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        updateCartDisplay();
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateCartDisplay();

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const product = {
                    id: parseInt(this.getAttribute('data-id')),
                    name: this.getAttribute('data-name'),
                    price: parseFloat(this.getAttribute('data-price'))
                };

                addToCart(product);
            });
        });
    });
</script>
@endsection

