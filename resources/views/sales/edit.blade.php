@extends('layouts.app')
@section('title')
    Edit Sale
@endsection
@section('content')
<main id="main" class="main">

  <div class="pagetitle">
    <h1>Edit Sale</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Edit Sale</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Update Sale</h5>

            <form action="{{ route('sales.update', $sale->id) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Customer</label>
                <div class="col-sm-10">
                  <select name="customer_id" class="form-select">
                    @foreach($customers as $customer)
                      <option value="{{ $customer->id }}" {{ $customer->id == $sale->customer_id ? 'selected' : '' }}>
                        {{ $customer->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Product</label>
                <div class="col-sm-10">
                  <select name="product_id" class="form-select">
                    @foreach($products as $product)
                      <option value="{{ $product->id }}" {{ $product->id == $sale->product_id ? 'selected' : '' }}>
                        {{ $product->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Quantity</label>
                <div class="col-sm-10">
                  <input type="number" name="quantity" class="form-control" value="{{ $sale->quantity }}">
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Discount (%)</label>
                <div class="col-sm-10">
                  <select name="discount" class="form-select">
                    <option value="0" {{ $sale->discount == 0 ? 'selected' : '' }}>0%</option>
                    <option value="2" {{ $sale->discount == 2 ? 'selected' : '' }}>2%</option>
                    <option value="4" {{ $sale->discount == 4 ? 'selected' : '' }}>4%</option>
                    <option value="6" {{ $sale->discount == 6 ? 'selected' : '' }}>6%</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Payment Method</label>
                <div class="col-sm-10">
                  <select name="payment_method" class="form-select">
                    <option value="cash" {{ $sale->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="online" {{ $sale->payment_method == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="card" {{ $sale->payment_method == 'card' ? 'selected' : '' }}>Card</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Update Sale</button>
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
