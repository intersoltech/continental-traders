<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Daily Sales Report - {{ $today->toDateString() }}</h1>
    <table>
        <thead>
            <tr>
                <th>#</th><th>Customer</th><th>Total</th><th>Payment</th><th>Time</th>
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
</body>
</html>
