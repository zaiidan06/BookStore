@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white text-slate-800 pt-24 pb-20 px-4 sm:px-10">
    <div class="max-w-5xl mx-auto">
        <!-- Heading -->
        <div class="text-center mb-12">
            <h1 class="text-4xl sm:text-5xl font-extrabold mb-4 text-green-600">Tentang Kami</h1>
            <p class="text-lg text-slate-600">Kenali lebih dekat siapa kami dan apa yang kami lakukan.</p>
        </div>

        <!-- Section 1 -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-800 mb-3">Misi Kami</h2>
            <p class="text-slate-700 leading-relaxed">
                Kami berkomitmen untuk menyediakan pengalaman terbaik dalam pencarian dan pembelian buku secara online.
                Dengan berbagai pilihan buku dan pelayanan yang ramah, kami hadir untuk membantu Anda menemukan buku impian Anda dengan mudah dan cepat.
            </p>
        </div>

        <!-- Section 2 -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-800 mb-3">Apa yang Kami Tawarkan</h2>
            <ul class="list-disc pl-6 space-y-2 text-slate-700">
                <li>Koleksi buku lengkap dari berbagai genre dan kategori.</li>
                <li>Proses pemesanan yang cepat, aman, dan terpercaya.</li>
                <li>Tim dukungan pelanggan yang siap membantu Anda kapan saja.</li>
                <li>Rekomendasi buku yang sesuai dengan minat Anda.</li>
            </ul>
        </div>

        <!-- Section 3 -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-800 mb-3">Tim Kami</h2>
            <p class="text-slate-700 leading-relaxed">
                Kami adalah sekelompok pecinta buku, desainer, dan pengembang web yang memiliki misi yang sama:
                menyatukan orang dengan buku yang mereka cintai. Kami percaya bahwa membaca dapat mengubah hidup dan membuka cakrawala baru.
            </p>
        </div>

        {{-- <!-- Call to Action -->
        <div class="text-center mt-16">
            <h3 class="text-xl font-semibold mb-4">Ingin tahu lebih banyak?</h3>
            <a href="{{ route('contact') }}"
               class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-full transition">
               Hubungi Kami
            </a>
        </div> --}}
    </div>
</div>
@endsection
