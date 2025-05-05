@extends('layouts.app')

@section('content')
<div x-data="{ openModalId: null, search: '{{ strtolower($search) }}' }" class="container mx-auto px-4 py-10 mt-24">

    <!-- Flash Message -->
    @if(session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        x-init="setTimeout(() => show = false, 3000)"
        class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-800 px-6 py-3 rounded-lg shadow-lg z-50"
    >
        {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-800 drop-shadow-lg">Temukan Bukumu Sekarang!</h1>
    </div>

    <!-- Search Bar -->
    <div class="flex justify-center mb-10">
        <input
            x-model="search"
            type="text"
            placeholder="🔍 Cari buku berdasarkan judul..."
            class="w-full max-w-2xl px-6 py-3 rounded-full bg-white/20 backdrop-blur border border-white/30 shadow-lg text-slate-800 placeholder:text-slate-800/70 focus:outline-none focus:ring-2 focus:ring-green-300 transition"
        >
    </div>

    <!-- Buku per kategori -->
    @forelse($groupedBooks as $category => $books)
    @if($books->isNotEmpty())
    <div class="mb-16">
        <h2 class="text-2xl font-semibold text-slate-800 mb-6 flex items-center gap-3">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            {{ $category }}
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($books as $book)
            <template x-if="!search || '{{ strtolower($book->book_name) }}'.toLowerCase().includes(search)">
                <div class="flex flex-col min-h-[300px] w-[240px] sm:w-[260px] bg-white/10 border border-white/30 backdrop-blur-md text-white rounded-2xl hover:shadow-lg hover:shadow-green-200 transform duration-300 overflow-hidden">
                    <!-- Gambar Buku -->
                    @if($book->book_image)
                    <div class="aspect-[4/5] overflow-hidden rounded-t-xl">
                    <img src="{{ asset('storage/' . $book->book_image) }}" alt="{{ $book->book_name }}" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    @else
                        <div class="w-full h-64 bg-gray-500/20 flex items-center justify-center text-slate-300/60">No Image</div>
                    @endif

                    <!-- Info Buku -->
                    <div class="p-5 space-y-2 flex-grow">
                        <h3 class="text-lg font-bold text-slate-800 truncate">{{ $book->book_name }}</h3>
                        <p class="text-sm text-slate-800 line-clamp-2 h-12">{{ Str::limit($book->book_description, 60) }}</p>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-800">Stok: {{ $book->book_stock }}</span>
                            <span class="text-green-400 font-bold text-xl">Rp {{ number_format($book->book_price, 0, ',', '.') }}</span>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-between gap-2 mt-4">
                            @guest
                                @if($book->book_stock <= 0)
                                    <button disabled class="w-full py-2 px-4 bg-gray-300 text-gray-600 text-sm rounded-full cursor-not-allowed">
                                        Barang Habis
                                    </button>
                                @else
                                    <button
                                        @click="alert('Silakan login terlebih dahulu untuk menambahkan ke keranjang.')"
                                        class="flex items-center justify-center gap-2 py-2 w-full text-slate-800 text-sm border border-black rounded-full hover:scale-105 transform duration-300 hover:border-green-500"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.3 5.3a1 1 0 001 .7h12.6a1 1 0 001-.7L21 13M7 13h10M9 21a1 1 0 11-2 0 1 1 0 012 0zm10 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                        </svg>
                                        Cart
                                    </button>
                                @endif
                            @else
                                @if($book->book_stock <= 0)
                                    <button disabled class="w-full py-2 px-4 bg-gray-300 text-gray-600 text-sm rounded-full cursor-not-allowed">
                                        Barang Habis
                                    </button>
                                @else
                                <button @click="openModalId = {{ $book->id }}"
                                    class="flex items-center justify-center gap-2 py-2 w-full text-slate-800 text-sm border border-black rounded-full hover:scale-105 transform duration-300 hover:border-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.3 5.3a1 1 0 001 .7h12.6a1 1 0 001-.7L21 13M7 13h10M9 21a1 1 0 11-2 0 1 1 0 012 0zm10 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                    </svg>
                                    Cart
                                </button>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </template>

            <!-- Modal -->
            <div
                x-show="openModalId === {{ $book->id }}"
                x-transition
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            >
                <div @click.away="openModalId = null" class="bg-white p-6 rounded-2xl shadow-2xl max-w-md w-full relative">
                    <button @click="openModalId = null" class="absolute top-4 right-4 text-gray-500 hover:text-red-500 text-xl">×</button>
                    <h2 class="text-xl font-bold text-green-600 mb-3">🛒 Tambah ke Keranjang</h2>
                    <p class="text-gray-700">Buku: <strong>{{ $book->book_name }}</strong></p>
                    <p class="text-gray-700 mb-4">Harga: <strong>Rp {{ number_format($book->book_price, 0, ',', '.') }}</strong></p>

                    <form action="{{ route('cart.add') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <label class="block text-sm mb-1 font-semibold">Jumlah</label>
                        <input type="number" name="quantity" min="1" max="{{ $book->book_stock }}" value="1" required class="w-full border px-3 py-2 rounded mb-4 focus:ring focus:ring-green-300">

                        <div class="flex justify-end gap-2">
                            <button type="button" @click="openModalId = null" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @empty
    <div class="text-center text-slate-800 text-lg my-10">
        Tidak ada buku yang ditemukan.
    </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('search', () => ({
            search: '{{ strtolower($search) }}',
        }));
    });
</script>
@endpush
