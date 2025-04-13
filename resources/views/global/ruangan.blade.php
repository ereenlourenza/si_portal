@extends('global.layouts.app')

@section('content')

<div class="text-lg md:text-xl font-semibold">
    @if(session('success_ruangan'))
        <div class='text-white mb-6 bg-green-600 px-4 py-4 shadow text-center md:text-left'>{{ session('success_ruangan') }}</div>
    @endif
    @if(session('error_ruangan'))
        <div class='text-white mb-6 bg-red-600 px-4 py-4 shadow text-center md:text-left'>{{ session('error_ruangan') }}</div>
    @endif
</div>

<div class="container-fluid py-12 space-y-6 px-4">

    <h1 class="max-w-5xl mx-auto text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Peminjaman Ruangan Gereja
    </h1>

    <!-- Penjelasan -->
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow px-6 py-5 text-gray-800 space-y-4 border border-yellow-300">
        <p class="text-base md:text-lg">
            Jemaat yang ingin menggunakan fasilitas ruang gereja seperti ruang ibadah, ruang pertemuan, atau ruang perpustakaan dapat mengajukan peminjaman ruangan melalui formulir online ini.
        </p>
        <p class="text-sm text-gray-600">
            Setelah pengisian formulir, silakan pantau status verifikasi dan persetujuan dari PHMJ melalui halaman status peminjaman. Harap mengisi formulir dengan lengkap dan menyertakan informasi waktu serta keperluan dengan jelas.
        </p>
    </div>

    <!-- Button Aksi -->
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-center md:justify-start gap-4">
        <a href="{{ route('peminjaman-ruangan.create') }}" class="w-full md:w-auto">
            <button class="w-full md:w-auto bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 px-6 rounded shadow transition">
                Isi Formulir Peminjaman
            </button>
        </a>

        <a href="{{ route('peminjaman-ruangan.status') }}" class="w-full md:w-auto">
            <button class="w-full md:w-auto bg-white border border-amber-600 text-amber-700 hover:bg-amber-50 font-medium py-3 px-6 rounded shadow transition">
                Cek Status Peminjaman
            </button>
        </a>
    </div>

</div>
@endsection
