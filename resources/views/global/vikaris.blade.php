@extends('global.layouts.app')

@section('content')


<div class="max-w-5xl mx-auto px-4 py-12 leading-relaxed">
    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-8 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Vikaris
    </h1>

    <div class="prose max-w-4xl mb-10">
        <p class="text-justify mb-10 ">
            Calon Vikaris GPIB diwajibkan lulus tes masuk Vikariat yang terdiri dari tes akademik, tes kesehatan, psikotes dan mengikuti pembinaan Pra-Vikariat. Vikaris GPIB, wajib mengikuti masa Vikariat I dan II di sebuah Jemaat GPIB yang ditentukan oleh Majelis Sinode GPIB selama kurang lebih 2 tahun sebelum diteguhkan dalam jabatan Pendeta/Pelayan Firman dan Sakramen GPIB.        
        </p>
        @if ($vikarisList->count())
            <p class="text-justify text-amber-700">
                Tahun ini kami menerima vikaris yang sedang menjalani masa pelayanannya di jemaat ini.
            </p> 
        @else
            <p class="text-justify text-amber-700">
                Tahun ini kami belum menerima vikaris yang menjalani masa pelayanannya di jemaat ini.
            </p> 
        @endif 
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($vikarisList as $vikaris)
            <div class="bg-white hover:-translate-y-1 duration-300 transition rounded-lg shadow-xl hover:shadow-2xl text-center p-4">
                <div class="aspect-w-3 aspect-h-4 mb-3">
                    <img 
                        src="{{ $vikaris->foto ? asset('storage/images/pelayan/' . $vikaris->foto) : asset('storage/images/pelayan/avatar.png') }}" 
                        alt="Foto Vikaris" 
                        class="object-cover w-auto h-48 mx-auto rounded"
                    >
                </div>
                <p class="text-sm text-gray-500">{{ $vikaris->masa_jabatan_mulai }} - {{ $vikaris->masa_jabatan_selesai }}</p>
                <h2 class="text-md font-semibold">{{ $vikaris->nama }}</h2>
            </div>
        @endforeach
    </div>
    
</div>

@endsection
