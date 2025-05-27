@extends('layouts.app')

@section('title')
    Daily Report
@endsection

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Daily Report</h1>
        <p>Date: {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</p>
    </div>    

    <section class="section">
        <table class="table table-bordered" style="font-size: 12px;">
            <thead style="background-color: #c4d9f0;">
                <tr>
                    <th>Bill No.</th>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Cash</th>
                    <th>Online</th>
                    <th>POS</th>
                    <th>Amount</th>
                </tr>
            </thead>
            @php
                $totalCash = 0;
                $totalOnline = 0;
                $totalPos = 0;
                $totalAmount = 0;
            @endphp
            <tbody>
                @foreach($reportData as $data)
                    @php
                        $totalCash += $data['cash'];
                        $totalOnline += $data['online'];
                        $totalPos += $data['pos'];
                        $totalAmount += $data['amount'];
                    @endphp
                    <tr>
                        <td>{{ $data['bill_no'] }}</td>
                        <td>{{ $data['customer'] }}</td>
                        <td>{{ $data['type'] }}</td>
                        <td>{{ $data['product'] }}</td>
                        <td>{{ $data['qty'] }}</td>
                        <td>{{ number_format($data['cash'], 2) }}</td>
                        <td>{{ number_format($data['online'], 2) }}</td>
                        <td>{{ number_format($data['pos'], 2) }}</td>
                        <td>{{ number_format($data['amount'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Totals:</th>
                    <th>{{ number_format($totalCash, 2) }}</th>
                    <th>{{ number_format($totalOnline, 2) }}</th>
                    <th>{{ number_format($totalPos, 2) }}</th>
                    <th>{{ number_format($totalAmount, 2) }}</th>                    
                </tr>
            </tfoot>
        </table>

        <button onclick="window.print()" class="btn btn-primary mt-3">Print Report</button>
    </section>
</main>
@endsection
