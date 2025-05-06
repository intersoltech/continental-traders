@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Receipt #{{ $sale->id }}</div>

        <div class="card-body">
            <p><strong>Date:</strong> {{ $sale->created_at->format('d-m-Y H:i') }}</p>
            <p><strong>Customer:</strong> {{ $sale->customer->name ?? 'Walk-in Customer' }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($sale->payment_method) }}</p>

            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->saleItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                        <td><strong>{{ number_format($sale->total_amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <a href="{{ route('receipts.download', $sale->id) }}" class="btn btn-primary mt-3">Download PDF</a>
        </div>
    </div>
</div>
@endsection
