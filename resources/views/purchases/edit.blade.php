@extends('layouts.app')
@section('title', 'Edit Purchase')
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Edit Purchase</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Update Purchase</h5>

            <form action="{{ route('purchases.update', $purchase) }}" method="POST">
              @csrf
              @method('PUT')

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Vendor</label>
                <div class="col-sm-10">
                  <select name="vendor_id" class="form-select" required>
                    @foreach($vendors as $vendor)
                      <option value="{{ $vendor->id }}" {{ $vendor->id == $purchase->vendor_id ? 'selected' : '' }}>
                        {{ $vendor->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Purchase Date</label>
                <div class="col-sm-10">
                  <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Total Amount</label>
                <div class="col-sm-10">
                  <input type="number" step="0.01" name="total_amount" class="form-control" value="{{ $purchase->total_amount }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Payment Method</label>
                <div class="col-sm-10">
                  <select name="payment_method" class="form-select" required>
                    <option value="cash" {{ $purchase->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="online" {{ $purchase->payment_method == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="card" {{ $purchase->payment_method == 'card' ? 'selected' : '' }}>Card</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Update Purchase</button>
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
