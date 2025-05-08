@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sales</h1>
    <a href="{{ route('sales.create') }}" class="btn btn-primary mb-3">Create Sale</a>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Discount</th>
                <th>Final Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
            <tr>
                <td>{{ $sale->date }}</td>
                <td>{{ $sale->customer->name }}</td>
                <td>{{ $sale->total }}</td>
                <td>{{ $sale->discount }}</td>
                <td>{{ $sale->final_total }}</td>
                <td>
                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete sale?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
