@extends('global.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12 leading-relaxed">
    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-8 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Pelkat Pelayanan Teruna
    </h1>

    <div class="max-w-4xl mx-auto px-4 py-4">

        <div class="bg-[#FFF500] border-l-4 border-[#FFF500] p-6 rounded shadow-xl mb-10 flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-amber-700 mb-2">ARTI LOGO PELKAT PT</h2>
                <ul class="list-disc ml-5 text-sm text-black">
                    <li><strong>Warna Kuning Telur:</strong> Memiliki arti Teruna terus berkembang.</li>
                    <li><strong>Salib:</strong> Dasar ajaran Kristus sebagai tonggak utama Pelkat Persekutuan Teruna GPIB.</li>
                    <li><strong>Gambar Anak Perempuan dan Laki-Laki:</strong> Seorang Anak Perempuan dan Laki-Laki berdampingan mengikuti kegiatan dalam wadah Pelkat Persekutuan Teruna GPIB.</li>
                </ul>
            </div>
            <div class="mt-4 md:mt-0 md:ml-6">
                <img loading="lazy" src="{{ asset('images/logo/pelkat_pt.webp') }}" alt="Logo Pelkat PT" class="w-[150px] md:w-[200px] max-w-none mx-auto">
            </div>
        </div>

        <div class="mb-10">
            <div class="bg-yellow-50 border-l-4 border-[#FFF500] px-8 py-8 text-center shadow">
            
                @foreach ($pelkat_pt as $item)
                    <div class="prose prose-sm md:prose-base prose-amber max-w-none text-justify">
                        {!! $item->deskripsi !!}
                    </div>
                @endforeach
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('css')
    <style>
        /* Tambahan style list di dalam konten CKEditor */
        .prose ol, .prose ul {
            padding-left: 1.5rem;
            list-style-type: decimal;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .prose ul {
            list-style-type: disc;
        }

        .prose li {
            margin-bottom: 0.25rem;
        }

        /* Style pendukung untuk table jika list ada di dalam table */
        td ol, td ul {
            padding-left: 1.25rem;
            list-style-type: decimal;
        }

        td li {
            margin-bottom: 0.25rem;
        }

        /* Tambahan style heading dan paragraf dari CKEditor */
        .prose h1, 
        .prose h2, 
        .prose h3, 
        .prose h4, 
        .prose h5, 
        .prose h6 {
            font-weight: 600;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
            line-height: 1.3;
            color:  #FFF500;
        }

        .prose h1 { font-size: 2.25rem; }
        .prose h2 { font-size: 1.75rem; }
        .prose h3 { font-size: 1.5rem; }
        .prose h4 { font-size: 1.25rem; }
        .prose h5 { font-size: 1.125rem; }
        .prose h6 { font-size: 1rem; }

        .prose p {
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .prose strong {
            font-weight: bold;
        }

        .prose img {
            width: 100%;
            height: auto;
            max-width: 100%;
            margin: 1rem auto;
            border-radius: 0.5rem; /* rounded */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: block;
        }

        @media (min-width: 640px) {
            .prose img { max-width: 24rem; } /* sm:max-w-sm */
        }

        @media (min-width: 768px) {
            .prose img { max-width: 28rem; } /* md:max-w-md */
        }

        @media (min-width: 1024px) {
            .prose img { max-width: 32rem; } /* lg:max-w-lg */
        }

        @media (min-width: 1280px) {
            .prose img { max-width: 36rem; } /* xl:max-w-xl */
        }

        .prose figure {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 2rem auto;
        }

        .prose figure img {
            width: 100%;
            height: auto;
            max-width: 100%;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        @media (min-width: 640px) {
            .prose figure img { max-width: 24rem; }
        }
        @media (min-width: 768px) {
            .prose figure img { max-width: 28rem; }
        }
        @media (min-width: 1024px) {
            .prose figure img { max-width: 32rem; }
        }
        @media (min-width: 1280px) {
            .prose figure img { max-width: 36rem; }
        }

        .prose figure figcaption {
            text-align: center;
            font-size: 0.875rem; /* text-sm */
            font-style: italic;
            color: #6B7280; /* Tailwind: text-gray-500 */
            margin-top: 0.5rem;
        }
    </style>
@endpush

