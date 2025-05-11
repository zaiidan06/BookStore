{{-- Footer --}}
<footer class="bg-gray-100 text-gray-800 mt-20 border-t border-green-200">
    <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">

      {{-- Brand --}}
      <div>
        <h4 class="text-xl font-bold text-green-700 mb-3">BookStore</h4>
        <p class="text-sm text-green-900">Toko buku digital terbaik untuk menemani petualangan literasi kamu.</p>
      </div>

      {{-- Navigation --}}
      <div>
        <h5 class="text-md font-semibold mb-3 text-green-800">Navigasi</h5>
        <ul class="space-y-2 text-sm">
          <li><a href="{{ route('books.index') }}" class="hover:text-green-700 transition-all duration-300">Koleksi Buku</a></li>
          <li><a href="{{ route('about') }}" class="hover:text-green-700 transition-all duration-300">Tentang Kami</a></li>
        </ul>
      </div>

    {{-- Contact Button --}}
    <div>
        <h5 class="text-md font-semibold mb-3 text-green-800">Hubungi Kami</h5>
        <p class="text-sm text-green-900 mb-4">Punya pertanyaan atau saran? Kami siap membantu.</p>

        @auth
            <button onclick="document.getElementById('contactModal').showModal()"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl transition-all duration-300">
                Kirim Pesan
            </button>
        @endauth

        @guest
            <button onclick="alert('Silakan login terlebih dahulu untuk mengirim pesan.')"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-xl transition-all duration-300">
                Kirim Pesan
            </button>
        @endguest
    </div>


    </div>

    <div class="border-t bg-green-400 text-center py-4 text-sm text-white">
      &copy; {{ date('Y') }} BookStore. All rights reserved.
    </div>

    {{-- Contact Modal --}}
    <dialog id="contactModal" class="modal modal-bottom sm:modal-middle">
      <div class="modal-box bg-white rounded-lg p-6 max-w-lg relative">

        {{-- Close Button --}}
        <form method="dialog">
          <button class="absolute right-4 top-4 text-gray-400 hover:text-red-500 text-xl" aria-label="Close">
            &times;
          </button>
        </form>

        <h3 class="font-bold text-lg text-green-700 mb-2">Hubungi Admin</h3>
        <p class="text-sm text-gray-600 mb-4">Silakan isi form berikut untuk menghubungi kami.</p>

        <form method="POST" action="{{ route('contact.store') }}">
            @csrf

            <div class="mb-4">
                <label for="message" class="block text-gray-700 font-medium mb-2">Your Message</label>
                <textarea name="message" id="message" rows="6"
                          class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:border-blue-400"
                          required>{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Send
            </button>
        </form>
      </div>
    </dialog>
  </footer>
