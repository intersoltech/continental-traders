@extends('layouts.app')
@section('title')
    Products
@endsection
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Product List</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Products</li>
        <li class="breadcrumb-item active">All</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Batteries</h5>
            <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>

            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Type</th>
                  <th>Capacity</th>
                  <th>Warranty</th>
                  <th>Quantity</th>
                  <th>Cost</th>
                  <th>Selling</th>
                  <th>Vendor</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($products as $product)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->capacity }}</td>
                    <td>{{ $product->warranty_months }} months</td>
                    <td>{{ $product->quantity }}</td>
                    <td>Rs {{ $product->cost_price }}</td>
                    <td>Rs {{ $product->selling_price }}</td>
                    <td>{{ $product->vendor->name ?? 'N/A' }}</td>
                    <td>
                      <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                      <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

          </div>
        </div>

      </div>
    </div>
  </section>
</main>
@endsection
