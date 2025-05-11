@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded shadow mt-36">
    <h2 class="text-2xl font-semibold mb-6 text-green-600">✅ Transaksi Berhasil!</h2>

    <div class="mb-6">
        <h3 class="font-bold text-lg mb-2">Informasi Pengguna</h3>
        <p>Nama: {{ $user->name }}</p>
        <p>Email: {{ $user->email }}</p>
        <p>Saldo Tersisa: <strong>Rp {{ number_format($user->balance, 2, ',', '.') }}</strong></p>
    </div>

    <div class="mb-6">
        <h3 class="font-bold text-lg mb-2">Detail Transaksi</h3>

        <table class="w-full table-auto text-left border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Judul Buku</th>
                    <th class="px-4 py-2">Jumlah</th>
                    <th class="px-4 py-2">Harga Satuan</th>
                    <th class="px-4 py-2">Subtotal</th>
                    <th class="px-4 py-2">Metode</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $transaction->book->book_name }}</td>
                    <td class="px-4 py-2">{{ $transaction->quantity }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($transaction->book->book_price, 2, ',', '.') }}</td>
                    <td class="px-4 py-2">Rp {{ number_format($transaction->total_payment, 2, ',', '.') }}</td>
                    <td class="px-4 py-2">{{ ucfirst($transaction->payment_type) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center mt-8">
        <a href="{{ route('transaction.paymentHistory') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Kembali ke Halaman Riwayat Pembayaran
        </a>

        <a href="{{ route('transaction.pdf.single', ['id' => $transaction->id]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Download Invoice Transaksi Ini
        </a>

        <a href="{{ route('transaction.pdf') }}" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
            Lihat Semua Pembayaran (PDF)
        </a>
    </div>
</div>
@endsection
