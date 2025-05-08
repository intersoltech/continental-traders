@extends('layouts.app')
@section('title')
    Sales
@endsection
@section('content')
<main id="main" class="main">

  <div class="pagetitle">
    <h1>Sales Table</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Sales</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Sales Records</h5>

            <a href="{{ route('sales.create') }}" class="btn btn-primary mb-3">Add Sale</a>

            <table class="table datatable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Customer</th>
                  <th>Product</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Total</th>
                  <th>Payment Method</th>
                  <th>Discount %</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($sales as $sale)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $sale->customer->name }}</td>
                  <td>{{ $sale->product->name }}</td>
                  <td>{{ $sale->quantity }}</td>
                  <td>{{ $sale->selling_price }}</td>
                  <td>{{ $sale->total_amount }}</td>
                  <td>{{ ucfirst($sale->payment_method) }}</td>
                  <td>{{ $sale->discount }}%</td>
                  <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                  <td>
                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline">
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
