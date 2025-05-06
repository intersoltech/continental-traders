@extends('layouts.app')
@section('title', 'Edit Sales Item')
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Edit Sales Item</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Edit Sales Item</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Update Sales Item</h5>

        <form action="{{ route('sales-items.update', $salesItem->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Sale</label>
            <div class="col-sm-10">
              <select name="sale_id" class="form-select" required>
                @foreach ($sales as $sale)
                  <option value="{{ $sale->id }}" {{ $salesItem->sale_id == $sale->id ? 'selected' : '' }}>
                    {{ $sale->customer_name ?? 'Sale #' . $sale->id }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Product</label>
            <div class="col-sm-10">
              <select name="product_id" class="form-select" required>
                @foreach ($products as $product)
                  <option value="{{ $product->id }}" {{ $salesItem->product_id == $product->id ? 'selected' : '' }}>
                    {{ $product->name }} - Rs.{{ $product->selling_price }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Quantity</label>
            <div class="col-sm-10">
              <input type="number" name="quantity" class="form-control" value="{{ $salesItem->quantity }}" required min="1">
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-success">Update</button>
          </div>
        </form>

      </div>
    </div>
  </section>
</main>
@endsection
