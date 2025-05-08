@extends('layouts.app')
@section('title', 'Add New Purchase')
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Add Purchase</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
        <li class="breadcrumb-item active">Add</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Purchase Form</h5>

            <form action="{{ route('purchases.store') }}" method="POST">
              @csrf
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Vendor</label>
                <div class="col-sm-10">
                  <select name="vendor_id" class="form-select" required>
                    <option selected disabled>Select Vendor</option>
                    @foreach($vendors as $vendor)
                      <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Purchase Date</label>
                <div class="col-sm-10">
                  <input type="date" name="purchase_date" class="form-control" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Total Amount</label>
                <div class="col-sm-10">
                  <input type="number" step="0.01" name="total_amount" class="form-control" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Payment Method</label>
                <div class="col-sm-10">
                  <select name="payment_method" class="form-select" required>
                    <option value="cash">Cash</option>
                    <option value="online">Online</option>
                    <option value="card">Card</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Save Purchase</button>
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
