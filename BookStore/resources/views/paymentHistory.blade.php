@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="w-full max-w-6xl mx-auto bg-white rounded-xl shadow-xl p-10">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Riwayat Pembayaran</h1>
            <a href="{{ route('transaction.pdf') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Cetak Semua (PDF)
            </a>
        </div>

        {{-- Notifikasi --}}
        @if(session('pending'))
            <div class="bg-yellow-100 text-yellow-700 p-4 rounded-lg mb-6">
                {{ session('pending') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            @forelse ($lastTransactions as $transaction)
                <div class="border p-6 rounded-lg shadow-sm">
                    <div class="flex justify-between items-start flex-wrap gap-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold">
                                {{ $transaction->book->book_name }}
                            </h3>
                            <div class="mt-2 text-gray-600">
                                <p>Jumlah: {{ $transaction->quantity }}</p>
                                <p>Total: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                                <p class="mt-2">
                                    Status:
                                    <span class="px-2 py-1 rounded-full {{ $transaction->payment_status === 'paid' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                        {{ ucfirst($transaction->payment_status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <a href="{{ route('transaction.pdf.single', $transaction->id) }}"
                               class="px-4 py-2 rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200">
                                Cetak PDF
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-600">Belum ada riwayat transaksi.</p>
            @endforelse
        </div>

        {{-- Total untuk PDF --}}
        @if(isset($isPdf) && $isPdf)
        <div class="mt-8 text-right border-t pt-4">
            <h3 class="text-xl font-bold">
                Total Pembayaran: Rp {{ number_format($totalBayar, 0, ',', '.') }}
            </h3>
        </div>
        @endif
    </div>
</div>
@endsection
