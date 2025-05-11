<!-- Navbar -->
<nav class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur border-b border-green-200 shadow-md">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <!-- Logo -->
        <div class="text-xl font-bold text-green-700">
            <a href="/">📚 Book Store</a>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="{{ route('books.index') }}" class="text-sm text-gray-700 hover:text-green-600 transition">Books</a>
            <a href="{{ route('about') }}" class="text-sm text-gray-700 hover:text-green-600 transition">About</a>
            @auth
            <a href="{{ route('contact') }}" class="text-sm text-gray-700 hover:text-green-600 transition">Contact</a>
            <a href="{{ route('profile') }}" class="text-sm text-gray-700 hover:text-green-600 transition">Profile</a>
            <div class="relative" x-data="{ dropdownOpen: false }" @mouseenter="dropdownOpen = true" @mouseleave="dropdownOpen = false">
                <button class="flex items-center text-sm text-gray-700 hover:text-green-600 transition"
                        aria-haspopup="true" :aria-expanded="dropdownOpen">
                    {{ Auth::user()->name }}
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="dropdownOpen" x-transition
                     class="absolute right-0 mt-2 w-48 bg-white border border-green-200 rounded-lg shadow-lg z-50">
                    <a href="{{ route('cart.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">🛒 Cart</a>
                    <a href="{{ route('transaction.paymentHistory') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50">✅ Transaction</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </div>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-green-600 transition">Login</a>
            @endauth
        </div>

        <!-- Hamburger Button -->
        <div class="md:hidden" x-data="{ open: false }">
            <button @click="open = !open" class="text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{ 'inline': open, 'hidden': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Mobile Menu -->
            <div x-show="open" x-transition @click.away="open = false"
                 class="absolute right-4 mt-3 w-64 bg-white border border-green-200 shadow-md rounded-lg p-4 z-50 space-y-2">
                <a href="{{ route('books.index') }}" @click="open = false" class="block text-sm text-gray-700 hover:text-green-600">Books</a>
                <a href="{{ route('about') }}" @click="open = false" class="block text-sm text-gray-700 hover:text-green-600">About</a>

                @auth
                <div x-data="{ dropdownOpen: false }">
                    <button @click="dropdownOpen = !dropdownOpen"
                            class="w-full flex items-center justify-between text-sm text-gray-700 hover:text-green-600">
                        {{ Auth::user()->name }}
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" x-transition class="mt-2 pl-2 space-y-2">
                        <a href="{{ route('cart.index') }}"
                           @click="dropdownOpen = false; open = false"
                           class="block text-sm text-gray-700 hover:text-green-600">🛒 Cart</a>
                        <a href="{{ route('transaction.paymentHistory') }}"
                           @click="dropdownOpen = false; open = false"
                           class="block text-sm text-gray-700 hover:text-green-600">✅ Transaction</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left text-sm text-gray-700 hover:text-green-600">🚪 Logout</button>
                        </form>
                    </div>
                </div>
                @else
                    <a href="{{ route('login') }}" @click="open = false"
                       class="block text-sm text-gray-700 hover:text-green-600">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
