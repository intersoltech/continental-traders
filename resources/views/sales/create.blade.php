@extends('layouts.app')
@section('title')
    Add Sale
@endsection
@section('content')
<main id="main" class="main">

  <div class="pagetitle">
    <h1>Add New Sale</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Add Sale</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Sale Form</h5>

            <form action="{{ route('sales.store') }}" method="POST">
              @csrf

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Customer</label>
                <div class="col-sm-10">
                  <select name="customer_id" class="form-select">
                    @foreach($customers as $customer)
                      <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Product</label>
                <div class="col-sm-10">
                  <select name="product_id" class="form-select">
                    @foreach($products as $product)
                      <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->selling_price }})</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Quantity</label>
                <div class="col-sm-10">
                  <input type="number" name="quantity" class="form-control" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Discount (%)</label>
                <div class="col-sm-10">
                  <select name="discount" class="form-select">
                    <option value="0">0%</option>
                    <option value="2">2%</option>
                    <option value="4">4%</option>
                    <option value="6">6%</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Payment Method</label>
                <div class="col-sm-10">
                  <select name="payment_method" class="form-select">
                    <option value="cash">Cash</option>
                    <option value="online">Online</option>
                    <option value="card">Card</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Submit Sale</button>
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
