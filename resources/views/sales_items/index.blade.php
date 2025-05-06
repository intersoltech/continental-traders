@extends('layouts.app')
@section('title', 'Sales Items')
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Sales Items</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Sales Items</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Sales Items List</h5>
        <a href="{{ route('sales-items.create') }}" class="btn btn-primary mb-3">Add New Sales Item</a>
        <table class="table datatable">
          <thead>
            <tr>
              <th>#</th>
              <th>Sale ID</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Subtotal</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($salesItems as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->sale_id }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
                <td>
                  <a href="{{ route('sales-items.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                  <form action="{{ route('sales-items.destroy', $item->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
</main>
@endsection
