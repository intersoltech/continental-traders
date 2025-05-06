@extends('layouts.app')
@section('title', 'Purchases')
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Purchases</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Purchases</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <a href="{{ route('purchases.create') }}" class="btn btn-primary mb-3">Add Purchase</a>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Purchase Records</h5>

            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Vendor</th>
                  <th>Date</th>
                  <th>Total Amount</th>
                  <th>Payment Method</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($purchases as $purchase)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $purchase->vendor->name }}</td>
                    <td>{{ $purchase->purchase_date }}</td>
                    <td>{{ number_format($purchase->total_amount, 2) }}</td>
                    <td>{{ ucfirst($purchase->payment_method) }}</td>
                    <td>
                      <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning">Edit</a>
                      <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
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
