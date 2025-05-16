@extends('global.layouts.app')

@section('content')


<div class="max-w-5xl mx-auto px-4 py-12 leading-relaxed">
    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-8 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Pengurus Harian Majelis Jemaat (PHMJ)
    </h1>

    <div class="prose max-w-4xl mb-10">
        <p class="text-justify">
            Masa tugas PHMJ adalah 2 (dua) tahun 6 (enam) bulan dan dapat dipilih kembali. Seseorang tidak dapat dipilih kembali setelah menjalani 2 (dua) kali masa tugas / periode menjabat berturut-turut, namun dapat dipilih kembali selaku fungsionaris PHMJ setelah melewati masa jeda selama 1 (satu) tahun. 
        </p>
        
    </div>

    <h1 class="text-xl md:text-2xl font-semibold text-amber-700 mb-8 px-4 py-4 rounded text-center">
        Susunan PHMJ yang bertugas saat ini.
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 ">
        @foreach($phmjList as $phmj)
            <div class="bg-white hover:-translate-y-1 duration-300 transition rounded-lg shadow-xl hover:shadow-2xl text-center p-4">
                <div class="aspect-w-3 aspect-h-4 mb-3">
                    <img loading="lazy"
                        src="{{ $phmj->pelayan->foto ? asset('storage/images/pelayan/' . $phmj->pelayan->foto) : asset('storage/images/pelayan/avatar.webp') }}" 
                        alt="Foto PHMJ" 
                        class="object-cover w-auto h-48 mx-auto rounded"
                    >
                </div>
                {{-- <p class="text-sm text-gray-500">{{ $phmj->periode_mulai }} - {{ $phmj->periode_selesai }}</p> --}}
                <p class="text-sm text-gray-500">{{ $phmj->jabatan }}</p>
                <h2 class="text-md font-semibold">{{ $phmj->pelayan->nama }}</h2>
            </div>
        @endforeach
    </div>
    
</div>

@endsection
