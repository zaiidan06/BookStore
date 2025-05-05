@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 my-12 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow rounded-2xl p-6">
        <div class="flex items-center justify-between space-x-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Balance</h2>
                <p class="text-lg font-semibold text-blue-500">{{'Rp '. number_format(Auth::user()->balance,2,',','.') }}</p>
            </div>
        </div>

        <div class="mt-12 space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Informasi Pribadi</h3>
                <div class="mt-2 text-gray-600">
                    <p><strong>Telepon:</strong> {{Auth::user()->phone_number ?? 'Belum diisi' }}</p>
                    <p><strong>Alamat:</strong> {{Auth::user()->shipping_address ?? 'Belum diisi' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
