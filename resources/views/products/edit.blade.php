@extends('layouts.app')
@section('title')
    Edit Product
@endsection
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Edit Product</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Products</li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Update Battery</h5>

            <form action="{{ route('products.update', $product->id) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Type</label>
                <div class="col-sm-10">
                  <input type="text" name="type" class="form-control" value="{{ $product->type }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Capacity</label>
                <div class="col-sm-10">
                  <input type="text" name="capacity" class="form-control" value="{{ $product->capacity }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Warranty (Months)</label>
                <div class="col-sm-10">
                  <input type="number" name="warranty_months" class="form-control" value="{{ $product->warranty_months }}">
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Quantity</label>
                <div class="col-sm-10">
                  <input type="number" name="quantity" class="form-control" value="{{ $product->quantity }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Cost Price</label>
                <div class="col-sm-10">
                  <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ $product->cost_price }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Selling Price</label>
                <div class="col-sm-10">
                  <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ $product->selling_price }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Vendor</label>
                <div class="col-sm-10">
                  <select name="vendor_id" class="form-select" required>
                    @foreach ($vendors as $vendor)
                      <option value="{{ $vendor->id }}" {{ $vendor->id == $product->vendor_id ? 'selected' : '' }}>
                        {{ $vendor->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-success">Update</button>
                </div>
              </div>

            </form>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>
@endsection
