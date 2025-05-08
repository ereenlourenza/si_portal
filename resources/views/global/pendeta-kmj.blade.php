@extends('global.layouts.app')

@section('content')


<div class="max-w-5xl mx-auto px-4 py-12 leading-relaxed">
    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-8 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Pendeta - Ketua Majelis Jemaat
    </h1>

    <div class="prose max-w-4xl mb-10">
        <p class="text-justify">
            Tugas dan tanggung jawab Pendeta adalah melaksanakan pemberitaan Firman dan pelayanan sakramen,
            menjaga kemurnian ajaran dan penggembalaan khusus, penugasan presbiter, pengurus dan pelayan Pelkat dan perkenalan PHMJ serta pengurus unit misioner.
        </p>
        
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($pendetaList as $pendeta)
            <div class="bg-white hover:-translate-y-1 duration-300 transition rounded-lg shadow-xl hover:shadow-2xl text-center p-4">
                <div class="aspect-w-3 aspect-h-4 mb-3">
                    <img 
                        src="{{ $pendeta->foto ? asset('storage/images/pelayan/' . $pendeta->foto) : asset('storage/images/pelayan/avatar.webp') }}" 
                        alt="Foto Pendeta" 
                        class="object-cover w-auto h-48 mx-auto rounded"
                    >
                </div>
                <p class="text-sm text-gray-500">{{ $pendeta->masa_jabatan_mulai }} - {{ $pendeta->masa_jabatan_selesai }}</p>
                <h2 class="text-md font-semibold">{{ $pendeta->nama }}</h2>
            </div>
        @endforeach
    </div>
    
</div>

@endsection
