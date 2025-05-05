@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 mt-24">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Keranjang Belanja</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-6 py-3 rounded-lg mb-6 shadow-md text-center">
            {{ session('success') }}
        </div>
    @endif

    @if($cartItems->count())
    <form action="{{ route('cart.checkout') }}" method="GET" id="checkoutForm">
        <div class="overflow-x-auto bg-white shadow-lg rounded-xl">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase tracking-wide text-xs">
                    <tr>
                        <th class="px-4 py-3 text-center">
                            <input type="checkbox" id="selectAll" onclick="toggleCheckboxes(this)">
                        </th>
                        <th class="px-4 py-3">Gambar</th>
                        <th class="px-4 py-3">Buku</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        @php
                            $itemTotal = $item->book->book_price * $item->quantity;
                        @endphp
                        <tr class="border-b hover:bg-gray-50 transition" data-item-id="{{ $item->id }}">
                            <td class="px-4 py-3 text-center">
                                <input
                                    type="checkbox"
                                    name="selected_items[]"
                                    value="{{ $item->id }}"
                                    class="item-checkbox"
                                    data-price="{{ $itemTotal }}"
                                    onchange="updateTotal()"
                                >
                            </td>
                            <td class="px-4 py-3">
                                @if($item->book->book_image)
                                    <img src="{{ asset('storage/' . $item->book->book_image) }}" alt="{{ $item->book->book_name }}" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg text-gray-500 text-xs">No Image</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $item->book->book_name }}</td>
                            <td class="px-4 py-3 text-gray-700">Rp {{ number_format($item->book->book_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <input
                                        type="number"
                                        value="{{ $item->quantity }}"
                                        min="1"
                                        max="{{ $item->book->book_stock }}"
                                        class="quantity-input w-16 border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 text-center"
                                        data-url="{{ route('cart.update', $item->id) }}"
                                        data-price="{{ $item->book->book_price }}"
                                    >
                                    <div class="loader hidden">
                                        <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                Rp <span class="total-price">{{ number_format($itemTotal, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button
                                        type="button"
                                        onclick="submitDelete('{{ route('cart.remove', $item->id) }}')"
                                        class="text-red-500 hover:underline text-sm"
                                    >
                                        Hapus
                                    </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 font-semibold text-right text-gray-800">
                        <td colspan="5" class="px-4 py-4 text-right">Total Keseluruhan:</td>
                        <td class="px-4 py-4" id="totalDisplay">Rp 0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6 text-right">
            <button type="submit" class="px-6 py-3 bg-green-500 text-white font-semibold rounded-xl shadow-md hover:bg-green-600 transition duration-200">
                Pesan Sekarang
            </button>
        </div>
    </form>
    @else
        <div class="bg-white p-8 rounded-xl shadow-md text-center">
            <p class="text-gray-600 text-lg">Keranjang masih kosong.</p>
            <a href="{{ route('books.index') }}" class="mt-4 inline-block text-blue-600 font-medium hover:underline">Lihat Buku</a>
        </div>
    @endif
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    window.updateTotal = () => {
        let total = 0;
        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            total += parseFloat(checkbox.dataset.price);
        });
        document.getElementById('totalDisplay').textContent =
            `Rp ${total.toLocaleString('id-ID')}`;
    };

    window.toggleCheckboxes = (source) => {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked;
            checkbox.dispatchEvent(new Event('change'));
        });
    };

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.oldValue = input.value;

        input.addEventListener('change', async function() {
            const quantity = parseInt(this.value);
            const maxStock = parseInt(this.max);
            const url = this.dataset.url;
            const price = parseFloat(this.dataset.price);
            const row = this.closest('tr');
            const loader = this.nextElementSibling;
            const totalCell = row.querySelector('.total-price');
            const checkbox = row.querySelector('.item-checkbox');

            if(quantity < 1 || quantity > maxStock) {
                this.value = this.oldValue;
                alert(`Jumlah harus antara 1 dan ${maxStock}`);
                return;
            }

            try {
                this.disabled = true;
                loader.classList.remove('hidden');

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: quantity,
                        _method: 'PATCH'
                    })
                });

                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'Update gagal');

                const newTotal = price * quantity;
                totalCell.textContent = newTotal.toLocaleString('id-ID');
                checkbox.dataset.price = newTotal;
                updateTotal();
                this.oldValue = quantity;

            } catch (error) {
                console.error('Error:', error);
                alert(error.message || 'Terjadi kesalahan');
                this.value = this.oldValue;
            } finally {
                this.disabled = false;
                loader.classList.add('hidden');
            }
        });
    });

    updateTotal();
});

function submitDelete(url) {
        const form = document.getElementById('deleteForm');
        if (!form) {
            alert('Form delete tidak ditemukan!');
            return;
        }
        form.action = url;
        form.submit();
    }
</script>

<style>
.loader {
    transition: opacity 0.3s ease;
}
.animate-spin {
    animation: spin 1s linear infinite;
}
@keyframes spin {
    100% { transform: rotate(360deg); }
}
</style>
@endsection
