<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Transaksi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            color: #333;
            padding: 30px;
        }

        .invoice-container {
            border: 1px solid #ccc;
            padding: 30px;
            max-width: 800px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #007b5e;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #007b5e;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #007b5e;
        }

        .user-info, .delivery-info {
            margin-bottom: 15px;
        }

        .user-info p, .delivery-info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table thead {
            background-color: #f5f5f5;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .total-section {
            margin-top: 20px;
            text-align: right;
        }

        .total-section p {
            margin: 4px 0;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
            color: #888;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .barcode {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <div class="header">
        <div class="logo">
            <h1 class="font-bold text-xl bg-green-500">Book Store</h1>
            {{-- <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="max-width: 150px;"> --}}
        </div>
        <div class="invoice-info">
            <p><span class="label">Tanggal Cetak:</span> {{ now()->format('d-m-Y H:i') }}</p>
        </div>
    </div>

    <div class="invoice-title">
        Invoice Transaksi
    </div>

    <div class="user-info">
        <p><span class="label">Nama:</span> {{ $user->name }}</p>
        <p><span class="label">Email:</span> {{ $user->email }}</p>
        <p><span class="label">No HP:</span> {{ $user->phone_number ?? '-' }}</p>
        <p><span class="label">Alamat Pengiriman:</span> {{ $user->shipping_address }}</p>
    </div>

    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>Buku</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Kurir</th>
            <th>Total</th>
            <th>Pembayaran</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @php
            $grandTotal = 0;
            $totalOngkir = 0;
        @endphp
        @foreach($lastTransactions as $index => $trx)
            @php
                $subtotal = $trx->book->book_price * $trx->quantity;
                $totalOngkir += $trx->delivery->delivery_price;
                $grandTotal += $subtotal + $totalOngkir;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $trx->book->book_name }}</td>
                <td>Rp{{ number_format($trx->book->book_price, 0, ',', '.') }}</td>
                <td>{{ $trx->quantity }}</td>
                <td>{{ $trx->delivery->delivery_courier }}</td>
                <td>Rp{{ number_format($totalBayar, 0, ',', '.') }}</td>
                <td>{{ ucfirst($trx->payment_type) }}</td>
                <td>{{ ucfirst($trx->payment_status) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="total-section">
        {{-- <p>Subtotal Buku: Rp{{ number_format($subtotal, 0, ',', '.') }}</p> --}}
        {{-- <p>Total Ongkir: Rp{{ number_format($totalOngkir, 0, ',', '.') }}</p> --}}
        {{-- <p>Grand Total: Rp{{ number_format($grandTotal, 0, ',', '.') }}</p> --}}
        <p>Total Bayar: Rp{{ number_format($totalBayar, 0, ',', '.') }}</p>
    </div>

    {{-- <div class="barcode">
        <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode">
    </div> --}}

    <!-- Add Barcode Image -->
    <div class="barcode">
        <img src="{{ public_path('storage/barcode.png') }}" alt="Barcode" />
    </div>

    <div class="footer">
        Terima kasih telah bertransaksi di {{ config('app.name') }}.
    </div>
</div>
</body>
</html>
