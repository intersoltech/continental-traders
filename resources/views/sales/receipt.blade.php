@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <h1>Sales Receipt</h1>
        <button class="btn btn-primary" onclick="window.print()">Print Receipt</button>
    </div>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Sales</a></li>
            <li class="breadcrumb-item active">Receipt #{{ $sale->id }}</li>
        </ol>
    </nav>

    <section class="section receipt-section">
        <div class="card" id="receipt-content">
            <div class="card-body">
                <h5 class="card-title text-center">Sales Receipt</h5>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Customer Details</h6>
                        <p>
                            <strong>Name:</strong> {{ $sale->customer->name }} <br>
                            <strong>Phone:</strong> {{ $sale->customer->phone ?? 'N/A' }} <br>
                            <strong>Address:</strong> {{ $sale->customer->address ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h6>Sale Details</h6>
                        <p>
                            <strong>Receipt #:</strong> {{ $sale->id }}<br>
                            <strong>Date:</strong> {{ $sale->created_at->format('d M Y, h:i A') }}<br>
                            <strong>Payment Method:</strong> {{ ucfirst($sale->payment_method) }}
                        </p>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->saleItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Discount</th>
                                    <td>{{ number_format($sale->discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td><strong>{{ number_format($sale->total, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <p class="text-center mt-4">Thank you for your business!</p>
            </div>
        </div>
    </section>
</main>
@endsection

@push('styles')
<style>
    /* Hide everything except the receipt content when printing */
    @media print {
        body * {
            visibility: hidden;
        }
        #receipt-content, #receipt-content * {
            visibility: visible;
        }
        #receipt-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        /* Hide the print button in print */
        button {
            display: none !important;
        }
    }
</style>
@endpush
