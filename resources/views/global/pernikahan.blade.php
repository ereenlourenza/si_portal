@extends('global.layouts.app')

@section('content')

<div class="text-lg md:text-xl font-semibold">
    @if(session('success_pernikahan'))
        <div class='text-white mb-6 bg-green-600 px-4 py-4 shadow text-center md:text-left'>{{ session('success_pernikahan') }}</div>
    @endif
    @if(session('error_pernikahan'))
        <div class='text-white mb-6 bg-red-600 px-4 py-4 shadow text-center md:text-left'>{{ session('error_pernikahan') }}</div>
    @endif
</div>

<div class="container-fluid py-12 space-y-6 px-4">

    <h1 class="max-w-5xl mx-auto text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Sakramen Pemberkatan Nikah
    </h1>

    <!-- Penjelasan -->
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow px-6 py-5 text-gray-800 space-y-4 border border-yellow-300">
        <p class="text-base md:text-lg">
            Sakramen Pemberkatan Nikah adalah penyatuan dua pribadi dalam kasih Allah, yang diberkati di hadapan jemaat dan Tuhan melalui ibadah khusus.
        </p>
        <p class="text-sm text-gray-600">
            Jemaat yang ingin mengajukan pemberkatan pernikahan dapat mengisi formulir pendaftaran secara online. Setelah pendaftaran, silahkan pantau status verifikasi dari Admin melalui halaman status pendaftaran.
        </p>
    </div>

    <!-- Button Aksi -->
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-center md:justify-start gap-4">
        <a href="{{ route('pemberkatan-nikah.create') }}" class="w-full md:w-auto">
            <button class="w-full md:w-auto bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 px-6 rounded shadow transition">
                Isi Formulir Pernikahan
            </button>
        </a>

        <a href="{{ route('pemberkatan-nikah.status') }}" class="w-full md:w-auto">
            <button class="w-full md:w-auto bg-white border border-amber-600 text-amber-700 hover:bg-amber-50 font-medium py-3 px-6 rounded shadow transition">
                Cek Status Pendaftaran
            </button>
        </a>
    </div>

</div>
@endsection
