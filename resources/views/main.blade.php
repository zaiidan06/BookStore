@extends('layouts.app')

@section('content')

<div x-data="{ openModalId: null}" class="bg-gray-50 text-gray-800 font-sans">

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

  {{-- Hero Section --}}
  <section class="bg-cover w-full h-[80vh] relative" style="background-image: url('https://images.unsplash.com/photo-1544716278-e513176f20b5');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-6">
      <h1 class="text-white text-4xl md:text-6xl font-bold mb-4 drop-shadow-lg">Jelajahi Dunia Baru Mu Sekarang!</h1>
      <p class="text-white text-lg md:text-xl mb-6 drop-shadow">Temukan buku favoritmu dan nikmati petualangannya.</p>
      <a href="{{ route('books.index')}}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-xl transition border border-green-900 hover:scale-105 shadow-lg shadow-green-500">Jelajahi Sekarang!</a>
    </div>
  </section>

  {{-- Featured Books --}}
  <section id="featured" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-center mb-12">4 Buku Teratas Bulan Ini</h2>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 md:gap-8 place-items-center">
      @foreach ($books->take(4) as $book)
        <div class="bg-white rounded-xl shadow-md shadow-lg w-[240px] sm:w-[260px] flex flex-col min-h-[300px]">
          <div class="aspect-[4/5] overflow-hidden rounded-t-xl">
            <img src="{{ 'storage/' . $book->book_image }}" alt="{{ $book->book_name }}" class="w-full h-full object-cover" />
          </div>
          <div class="p-3 flex flex-col justify-between flex-grow text-center">
            <h3 class="text-sm font-semibold truncate mb-1">{{ $book->book_name }}</h3>
            <p class="text-gray-500 text-xs line-clamp-2 h-[40px]">{{ $book->book_description }}</p>
            <span class="text-gray-500 text-start font-semibold">Tersedia: {{ $book->book_stock }}</span>

            <div class="flex justify-between gap-2 mt-2">
                <span class="text-green-400 font-bold text-xl">Rp {{ number_format($book->book_price, 0, ',', '.') }}</span>
                @guest
                @if($book->book_stock > 0)
                  <button
                    @click="alert('Silakan login terlebih dahulu untuk menambahkan ke keranjang.')"
                    class="flex items-center justify-center gap-2 py-2 px-4 text-slate-800 text-sm border border-black rounded-full hover:scale-105 transform duration-300 hover:border-green-500"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.3 5.3a1 1 0 001 .7h12.6a1 1 0 001-.7L21 13M7 13h10M9 21a1 1 0 11-2 0 1 1 0 012 0zm10 0a1 1 0 11-2 0 1 1 0 012 0z" />
                    </svg>
                    Cart
                  </button>
                @else
                  <button
                    class="flex items-center justify-center gap-2 py-2 px-4 bg-gray-300 text-gray-600 text-sm border border-gray-400 rounded-full cursor-not-allowed"
                    disabled
                  >
                    Barang Habis
                  </button>
                @endif
              @else
                @if($book->book_stock > 0)
                  <button @click="openModalId = {{ $book->id }}"
                    class="flex items-center justify-center gap-2 py-2 px-4 text-slate-800 text-sm border border-black rounded-full hover:scale-105 transform duration-300 hover:border-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.3 5.3a1 1 0 001 .7h12.6a1 1 0 001-.7L21 13M7 13h10M9 21a1 1 0 11-2 0 1 1 0 012 0zm10 0a1 1 0 11-2 0 1 1 0 012 0z" />
                    </svg>
                    Cart
                  </button>
                @else
                  <button
                    class="flex items-center justify-center gap-2 py-2 px-4 bg-gray-300 text-gray-600 text-sm border border-gray-400 rounded-full cursor-not-allowed"
                    disabled
                  >
                    Barang Habis
                  </button>
                @endif
              @endguest
            </div>
          </div>
        </div>

        {{-- Modal --}}
        @auth
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
        @endauth

      @endforeach
    </div>

    <div class="text-center mt-10">
      <a href="{{ route('books.index') }}"
        class="relative inline-block text-white font-semibold py-3 px-6 rounded-full overflow-hidden group text-base w-full">
        <span class="absolute inset-0 bg-gradient-to-r from-yellow-300 to-green-500 transition-opacity duration-500 ease-in-out group-hover:opacity-0"></span>
        <span class="absolute inset-0 bg-gradient-to-r from-green-500 to-yellow-300 opacity-0 transition-opacity duration-500 ease-in-out group-hover:opacity-100"></span>
        <span class="relative">Kunjungi Buku Lainnya →</span>
      </a>
    </div>

  </section>
</div>

@endsection
