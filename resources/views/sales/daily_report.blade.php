@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Daily Sale Report</h1>
        <p>Date: {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</p>
    </div>

    <section class="section">
        <table class="table table-bordered" style="font-size: 12px;">
            <thead style="background-color: #c4d9f0;">
                <tr>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Cash</th>
                    <th>Bill No.</th>
                    <th>Bank</th>
                    <th>Online</th>
                    <th>POS</th>
                    <th>Type</th>
                    <th>Scrape</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $data)
                    <tr>
                        <td>{{ $data['name'] }}</td>
                        <td>{{ $data['qty'] }}</td>
                        <td>{{ number_format($data['cash'], 2) }}</td>
                        <td>{{ $data['bill_no'] }}</td>
                        <td>{{ number_format($data['bank'], 2) }}</td>
                        <td>{{ number_format($data['online'], 2) }}</td>
                        <td>{{ number_format($data['pos'], 2) }}</td>
                        <td>{{ $data['type'] }}</td>
                        <td>{{ $data['scrape'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button onclick="window.print()" class="btn btn-primary mt-3">Print Report</button>
    </section>
</main>
@endsection
