<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Report - {{ $date }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        tfoot th { text-align: right; }
    </style>
</head>
<body>
    <div style="text-align: center">
        <img src="{{ asset('assets/img/logo-full.png') }}" alt="Big Logo">
    </div>
    <h2>Daily Sales Report - {{ $date }}</h2>

    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Product</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Cash</th>
                <th>Online</th>
                <th>POS</th>
                <th>Amount</th>
                <th>Bill No.</th>
            </tr>
        </thead>
        @php
            $totalCash = 0;
            $totalOnline = 0;
            $totalPos = 0;
            $totalAmount = 0;
        @endphp
        <tbody>
            @foreach ($reportData as $data)
                @php
                    $totalCash += $data['cash'];
                    $totalOnline += $data['online'];
                    $totalPos += $data['pos'];
                    $totalAmount += $data['amount'];
                @endphp
                <tr>
                    <td>{{ $data['customer'] }}</td>
                    <td>{{ $data['product'] }}</td>
                    <td>{{ $data['type'] }}</td>
                    <td>{{ $data['qty'] }}</td>
                    <td>{{ number_format($data['cash'], 2) }}</td>
                    <td>{{ number_format($data['online'], 2) }}</td>
                    <td>{{ number_format($data['pos'], 2) }}</td>
                    <td>{{ number_format($data['amount'], 2) }}</td>
                    <td>{{ $data['bill_no'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align:right;">Totals:</th>
                <th>{{ number_format($totalCash, 2) }}</th>
                <th>{{ number_format($totalOnline, 2) }}</th>
                <th>{{ number_format($totalPos, 2) }}</th>
                <th>{{ number_format($totalAmount, 2) }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
