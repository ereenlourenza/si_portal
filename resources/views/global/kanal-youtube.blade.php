@extends('global.layouts.app')

@section('content')
<div class="container-fluid py-12 space-y-6 px-4">

    <h1 class="max-w-5xl mx-auto text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow">
        Kanal Youtube
    </h1>

    <!-- Jadwal + Kanal -->
    <div class="max-w-5xl mx-auto rounded-lg px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 ">
        <div class="text-center md:text-left w-full md:w-auto">
            <h2 class="font-semibold text-gray-800 text-lg mb-2">JADWAL LIVE STREAMING</h2>
            <p class="text-sm text-gray-700">Ibadah Minggu Pk 08.00<br>Ibadah Keluarga Gabungan Pk 17.00</p>
        </div>
        <a href="https://www.youtube.com/@GPIBImmanuelMalang/streams" class="w-full md:w-auto">
            <div class="flex items-center border py-2 px-3 shadow-lg border-[#614D24] gap-3 rounded hover:bg-[#614D24] hover:text-white transition">
                <img loading="lazy" src="{{ asset('images/global/youtube-icon.webp') }}" alt="YouTube Icon" class="w-6 h-6">
                <div>
                    <p class="text-sm font-semibold">GPIB Immanuel Malang</p>
                    <p class="text-xs">@gpibimmanuelmalang</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Sambutan -->
    <div class="max-w-5xl mx-auto text-center text-sm md:text-base text-gray-800 border-t border-b border-t-[#614D24] border-b-[#614D24] py-4 px-4">
        <p>Selamat beribadah bagi jemaat yang mengikuti secara live streaming.<br>
        Mari beribadah dengan penuh sukacita, karena Tuhan hadir di mana pun kita berada!</p>
    </div>

    <!-- Embed video terbaru -->
    @if ($videoId)
        <div class="max-w-5xl px-4 mx-auto aspect-video rounded-lg overflow-hidden">
            <iframe 
                class="w-full h-full"
                src="https://www.youtube.com/embed/{{ $videoId }}"
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        </div>
    @else
        <p class="text-center text-gray-500">Belum ada video terbaru.</p>
    @endif


</div>
@endsection
