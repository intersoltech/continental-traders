@extends('layouts.app')
@section('title', 'Add Sales Item')
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Add Sales Item</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Add Sales Item</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">New Sales Item</h5>

        <form action="{{ route('sales-items.store') }}" method="POST">
          @csrf

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Sale</label>
            <div class="col-sm-10">
              <select name="sale_id" class="form-select" required>
                @foreach ($sales as $sale)
                  <option value="{{ $sale->id }}">{{ $sale->customer_name ?? 'Sale #' . $sale->id }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Product</label>
            <div class="col-sm-10">
              <select name="product_id" class="form-select" required>
                @foreach ($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }} - Rs.{{ $product->selling_price }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Quantity</label>
            <div class="col-sm-10">
              <input type="number" name="quantity" class="form-control" required min="1">
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary">Add Item</button>
          </div>
        </form>

      </div>
    </div>
  </section>
</main>
@endsection
