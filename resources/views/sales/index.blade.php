@extends('layouts.app')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Purchases</h1>
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
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <a href="{{ route('sales.create') }}" class="btn btn-primary mb-3">Create Sale</a>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Sale Records</h5>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th><strong>Sold Items</strong></th> <!-- For the toggle button -->
                                        <th>#</th>
                                        <th>Sale ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Total Amount</th>
                                        <th>Payment Method</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sales as $sale)
                                        <tr>
                                            <td>                                                
                                                <ol class="mt-2" type="1">
                                                    @foreach ($sale->saleItems as $item)
                                                        <li>{{ $item->product->name }} — Qty: {{ $item->quantity }},
                                                            Price: Rs {{ number_format($item->price, 2) }}, Total: Rs
                                                            {{ number_format($item->total, 2) }}</li>
                                                    @endforeach
                                                </ol>
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $sale->id }}</td>
                                            <td>{{ $sale->customer->name }}</td>
                                            <td>{{ $sale->created_at->toDateString() }}</td>
                                            <td>{{ number_format($sale->total, 2) }}</td>
                                            <td>{{ ucfirst($sale->payment_method) }}</td>
                                            <td>
                                                <a href="{{ route('sales.receipt', $sale->id) }}" class="btn btn-sm btn-info" target="_blank">Print Receipt</a>
                                                <a href="{{ route('sales.edit', $sale) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('sales.destroy', $sale) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
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
