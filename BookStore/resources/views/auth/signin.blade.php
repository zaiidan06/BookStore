<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login / Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: url("{{ asset('images/signin-background.jpeg') }}");
        }
        .form-container {
            transition: all 0.5s ease-in-out;
        }
        .form-container .form {
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body
class="flex items-center justify-center min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/signin-background.jpeg') }}');">
<div class="absolute inset-0 bg-black bg-opacity-30"></div>

{{-- <!-- Flash Message -->
 @if(session('success'))
 <div
     x-data="{ show: true }"
     x-show="show"
     x-transition
     x-init="setTimeout(() => show = false, 1000)"
     class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-800 px-6 py-3 rounded-lg shadow-lg z-50"
 >
     {{ session('success') }}
 </div>
 @endif --}}

<div class="w-full max-w-md p-8 rounded-xl shadow-xl bg-white/20 backdrop-blur-lg">
    <div class="flex justify-center">
            <a href="{{ route('main')}}" class="text-2xl font-bold text-center text-green-400 mb-6">BookStore</a>
        </div>
        <!-- Form Container -->
        <div class="form-container">
            <!-- Login Form -->
            <div id="loginForm" class="form space-y-6">
                <form action="login" method="POST">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-white">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="Enter your email"
                            class="w-full mt-2 px-4 py-3 mb-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required
                        />
                        @error('email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-white">Password</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Enter your password"
                            class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required
                        />
                        @error('password')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button
                        type="submit"
                        class="w-full py-2 mt-6 text-white font-bold bg-green-500 rounded-md shadow-xl shadow-green-300 hover:bg-green-600 focus:ring-2 focus:ring-green-400 focus:outline-none transition-all duration-300 ease-in-out"
                    >
                        Login
                    </button>
                </form>
                <p class="mt-6 text-sm text-center text-white">
                    Don't have an account?
                    <a href="javascript:void(0)" id="switchToRegister" class="text-green-400 hover:underline">Register</a>
                </p>
            </div>

            <!-- Register Form -->
                <div id="registerForm" class="form space-y-6 hidden">
                    <form action="register" method="POST">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-white">Name</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            placeholder="Enter your name"
                            class="w-full mt-2 px-4 py-2 mb-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required
                        />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-white">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="Enter your email"
                            class="w-full mt-2 px-4 py-2 mb-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required
                        />
                        @error('email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-white">Password</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Enter your password"
                            class="w-full mt-2 px-4 py-2 mb-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required
                        />
                        @error('password')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-white">Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            placeholder="Confirm your password"
                            class="w-full mt-2 px-4 py-2 mb-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required
                        />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-white">Phone Number</label>
                        <input
                            type="text"
                            name="phone_number"
                            id="phone_number"
                            placeholder="Enter your phone"
                            class="w-full mt-2 px-4 py-2 mb-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required
                        />
                        @error('phone_number')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-white">Address</label>
                        <input
                            type="text"
                            name="shipping_address"
                            id="shipping_address"
                            placeholder="Enter your Address"
                            class="w-full mt-2 px-4 py-2 mb-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                            required
                        />
                        @error('shipping_address')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                    <button
                        type="submit"
                        class="w-full py-2 mt-6 text-white font-bold bg-green-500 rounded-md shadow-xl shadow-green-300 hover:bg-green-600 focus:ring-2 focus:ring-green-400 focus:outline-none transition-all duration-300 ease-in-out"
                    >
                        Register
                    </button>
                    @if (session('success'))
                        <div class="p-4 mb-4 text-green-800 bg-green-200 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                </form>
                <p class="mt-6 text-sm text-center text-white">
                    Already have an account?
                    <a href="javascript:void(0)" id="switchToLogin" class="text-green-400 hover:underline">Login</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const switchToRegister = document.getElementById('switchToRegister');
        const switchToLogin = document.getElementById('switchToLogin');

        switchToRegister.addEventListener('click', () => {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            registerTab.classList.add('text-indigo-600');
            loginTab.classList.remove('text-indigo-600');
            registerForm.classList.add('form');
        });

        switchToLogin.addEventListener('click', () => {
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
            loginTab.classList.add('text-indigo-600');
            registerTab.classList.remove('text-indigo-600');
            loginForm.classList.add('form');
        });
    </script>
</body>
</html>
