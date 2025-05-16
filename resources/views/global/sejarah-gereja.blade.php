@extends('global.layouts.app')

@section('content')

    <section class="max-w-5xl mx-auto px-4 py-12 text-gray-800 leading-relaxed">
        <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow">
            Sejarah Gereja Jago
        </h1>
    
        <div class="text-center px-4 py-4">
            <img loading="lazy" src="/images/gereja/foto-1.webp" alt="Gereja Jago" class="w-full sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl mx-auto my-4 h-auto rounded shadow">
            <p class="text-sm italic text-gray-500">GPIB Immanuel Malang - Gereja Jago</p>
        </div>

        @foreach ($sejarah as $item)
            <h2 class="text-xl md:text-2xl font-semibold text-amber-700 mb-2 mt-8">
                {{ $item->judul_subbab }}
            </h2>

            {{-- <pre class="text-xs bg-gray-100 p-4 overflow-auto">
                {{ htmlentities($item->isi_konten) }}
            </pre> --}}
            
            <div class="prose prose-sm md:prose-base prose-amber max-w-none text-justify">
                {!! $item->isi_konten !!}
            </div>
            
        @endforeach

    </section>
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

@push('js')
@endpush