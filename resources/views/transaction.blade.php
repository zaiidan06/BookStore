@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12 mt-24">
    <div class="w-full max-w-6xl mx-auto bg-white rounded-xl shadow-xl p-10">
        <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Checkout</h1>

        @if($cartItems->isEmpty())
            <div class="text-center text-gray-600 text-lg">
                Keranjang belanja Anda kosong.
            </div>
        @else
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cart.transaction') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Kolom Kiri: Informasi Pembeli --}}
                <div>
                    <h2 class="text-xl font-semibold mb-6 text-gray-700">Informasi Pembeli</h2>
                    <div class="space-y-5">
                        <div>
                            <label class="block font-medium mb-1">Nama Lengkap</label>
                            <input type="text" value="{{ $user->name ?? '' }}" disabled class="w-full border px-4 py-2 rounded bg-gray-100">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">No. Telepon</label>
                            <input type="text" name="phone_number" required value="{{ old('phone_number', $user->phone_number ?? '') }}" class="w-full border px-4 py-2 rounded">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Alamat Lengkap</label>
                            <textarea name="shipping_address" rows="3" required class="w-full border px-4 py-2 rounded">{{ old('shipping_address', $user->shipping_address ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Kurir Pengiriman</label>
                            <select name="delivery_courier" required class="w-full border px-4 py-2 rounded">
                                <option value="">-- Pilih Kurir --</option>
                                <option value="JNE" {{ old('delivery_courier') == 'JNE' ? 'selected' : '' }}>JNE</option>
                                <option value="J&T" {{ old('delivery_courier') == 'J&T' ? 'selected' : '' }}>J&T</option>
                                <option value="SiCepat" {{ old('delivery_courier') == 'SiCepat' ? 'selected' : '' }}>SiCepat</option>
                                <option value="AnterAja" {{ old('delivery_courier') == 'AnterAja' ? 'selected' : '' }}>AnterAja</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Opsi Pengiriman</label>
                            <select name="shipping_option" required class="w-full border px-4 py-2 rounded">
                                <option value="">-- Pilih Opsi Pengiriman--</option>
                                <option value="standard" {{ old('shipping_option') == 'standard' ? 'selected' : '' }}>Standard (2-3 hari)</option>
                                <option value="express" {{ old('shipping_option') == 'express' ? 'selected' : '' }}>Express (1 hari)</option>
                                <option value="same_day" {{ old('shipping_option') == 'same_day' ? 'selected' : '' }}>Same Day</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Metode Pembayaran</label>
                            <select name="payment_type" required class="w-full border px-4 py-2 rounded">
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                @foreach ($paymentTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('payment_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Biaya Pengiriman</label>
                            <div class="flex items-center gap-2">
                                <span id="shippingCost" class="w-full border px-4 py-2 rounded bg-gray-100 block">Rp 0</span>
                                <input type="hidden" name="shipping_cost" id="shippingCostInput" value="0">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Kolom Kanan: Ringkasan Pesanan --}}
                <div>
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Ringkasan Pesanan</h2>
                    <div class="space-y-4 overflow-y-auto max-h-[400px] pr-2">
                        @foreach($cartItems as $item)
                        <div class="flex items-start border p-4 rounded-lg shadow-sm">
                            <img src="{{ asset('storage/' . $item->book->book_image) }}" alt="Book Image" class="w-32 h-40 object-cover rounded mr-4 border">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-800 py-2">{{ $item->book->book_name }}</h3>
                                <p class="text-sm text-gray-600 p-1">Harga: Rp {{ number_format($item->book->book_price, 2, ',', '.') }}</p>
                                <p class="text-sm text-gray-600 p-1">Jumlah: {{ $item->quantity }}</p>
                                <p class="text-sm font-semibold text-gray-700 p-1">
                                    Total: Rp {{ number_format($item->book->book_price * $item->quantity, 2, ',', '.') }}
                                </p>
                            </div>
                            <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 text-right font-bold text-xl">
                        Total Pembayaran:<br>
                        <span class="text-green-600 total-payment">Rp {{ number_format($totalPayment, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="text-right mt-10">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg shadow-md text-lg transition">
                    Konfirmasi Pembelian
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const shippingOption = document.querySelector('[name="shipping_option"]');
        const shippingCostDisplay = document.getElementById('shippingCost');
        const shippingCostInput = document.getElementById('shippingCostInput');
        const totalPaymentElement = document.querySelector('.total-payment');
        let shippingCost = 0;

        const shippingRates = {
            'standard': 20000,
            'express': 50000,
            'same_day': 100000
        };

        const itemTotal = {{ $totalPayment }};

        const formatRupiah = (amount) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        function updateTotals() {
            const totalPayment = itemTotal + shippingCost;
            shippingCostDisplay.textContent = formatRupiah(shippingCost);
            shippingCostInput.value = shippingCost;
            totalPaymentElement.textContent = formatRupiah(totalPayment);
        }

        shippingOption.addEventListener('change', function () {
            shippingCost = shippingRates[this.value] || 0;
            updateTotals();
        });

        // Inisialisasi awal
        updateTotals();
    });
    </script>
@endsection
