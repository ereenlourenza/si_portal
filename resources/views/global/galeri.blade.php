@extends('global.layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-4 py-12 leading-relaxed">
    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-4 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Galeri
    </h1>

    <div class="max-w-6xl mx-auto px-4">
        @foreach ($galeriByKategori as $kategoriNama => $galeris)
            <div class="py-12">
                <h2 class="text-xl font-bold mt-10 text-center uppercase">{{ $kategoriNama }}</h2>
    
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    @foreach ($galeris as $galeri)
                        <div class="relative group overflow-hidden rounded-lg shadow-lg h-48 w-full">
                            <!-- Foto Galeri -->
                            <img loading="lazy" src="{{ asset('storage/images/galeri/' . $galeri->foto) }}" 
                                 alt="{{ $galeri->judul }}"
                                 class="w-full h-full rounded shadow object-cover transition duration-300 group-hover:brightness-50">
    
                            <!-- Overlay teks -->
                            <div class="absolute bottom-0 left-0 w-full p-4 text-white opacity-0 group-hover:opacity-100 transition duration-300 bg-gradient-to-t from-black/60 via-black/30 to-transparent">
                                <h3 class="text-md font-semibold">{{ $galeri->judul }}</h3>
                                @if ($galeri->deskripsi)
                                    <p class="text-sm mt-1 line-clamp-2">{{ $galeri->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
    
                <div class="text-center mt-6">
                    <a href="{{ route('galeri-kategori', ['id' => $galeris->first()->kategorigaleri_id ?? 0]) }}" 
                       class="inline-block bg-amber-500 text-white font-semibold px-5 py-2 rounded-full shadow-md hover:bg-amber-600 transition duration-300 ease-in-out">
                        Selengkapnya
                    </a>
                </div>
                
            </div>
        @endforeach
    </div>
    
        
</div>

@endsection
