@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Produk</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active">Edit Produk</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Form Edit Produk</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update',$product->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Input Nama Produk --}}
                        <div class="form-group">
                            <label for="name">Nama Produk</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name',$product->name) }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Input Deskripsi --}}
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" required>{{ old('description',$product->description) }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Input SKU --}}
                        <div class="form-group">
                            <label for="sku">Kode Barang</label>
                            <input type="text" name="sku" id="sku" class="form-control"
                                value="{{ old('sku',$product->sku) }}" required>
                            @error('sku')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Input Harga --}}
                        <div class="form-group">
                            <label for="price">Harga</label>
                            <input type="number" name="price" id="price" class="form-control"
                                value="{{ old('price',$product->price) }}" required>
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Input Stok --}}
                        <div class="form-group">
                            <label for="stock">Stok</label>
                            <input type="number" name="stock" id="stock" class="form-control"
                                value="{{ old('stock',$product->stock) }}" required>
                            @error('stock')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Input Kategori (Dropdown) --}}
                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id',$product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
