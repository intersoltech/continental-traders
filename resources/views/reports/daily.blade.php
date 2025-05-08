@extends('layouts.app')

@section('content')
<h1>Daily Report - {{ $today->toDateString() }}</h1>
<a href="{{ route('reports.pdf') }}" class="btn btn-primary mb-3">Download PDF</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th><th>Customer</th><th>Total</th><th>Payment Type</th><th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $sale)
        <tr>
            <td>{{ $sale->id }}</td>
            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
            <td>{{ number_format($sale->total, 2) }}</td>
            <td>{{ $sale->payment_type }}</td>
            <td>{{ $sale->created_at->format('H:i:s') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
