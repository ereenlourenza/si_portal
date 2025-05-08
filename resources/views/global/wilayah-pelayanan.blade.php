@extends('global.layouts.app')

@section('content')

    <section class="max-w-5xl mx-auto px-4 py-12 text-gray-800 leading-relaxed">
        <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow">
            Wilayah Pelayanan GPIB Immanuel Malang
        </h1>
    
        <div class="text-center px-4 py-4">
            <img src="/images/global/wilayah-pelayanan.webp" alt="Wilayah Pelayanan" class="w-full sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl mx-auto my-4 h-auto rounded shadow">
            <p class="text-sm italic text-gray-500">Wilayah Pelayanan</p>
        </div>

        <h2 class="text-xl md:text-2xl font-semibold text-amber-700 mb-2 mt-8">
            BATAS WILAYAH PELAYANAN SEKTOR
        </h2>

        @foreach ($sektor as $item)
            <h2 class="text-lg md:text-xl font-bold text-amber-700 mb-2 mt-8">
                {{ $item->sektor_nama }}
            </h2>
            
            {{-- <pre class="text-xs bg-gray-100 p-4 overflow-auto">
                {{ htmlentities($item->deskripsi) }}
            </pre> --}}
            
            <div class="prose prose-sm md:prose-base prose-amber max-w-none text-justify">
                {!! nl2br(
                    preg_replace(
                        ['/\b(Sebelah Timur)\b/i', '/\b(Sebelah Utara)\b/i', '/\b(Sebelah Barat)\b/i', '/\b(Sebelah Selatan)\b/i', '/\b(Sebelah Barat\/Selatan)\b/i'],
                        ['<strong>$1</strong>', '<strong>$1</strong>', '<strong>$1</strong>', '<strong>$1</strong>', '<strong>$1</strong>'],
                        e($item->deskripsi)
                    )
                ) !!}                
            </div>
            
        @endforeach

    </section>
@endsection

@push('css')
@endpush

@push('js')
@endpush