@extends('global.layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-4 py-12 leading-relaxed">
    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-4 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Warta Jemaat
    </h1>

    <!-- Filter Form -->
    <div class="max-w-6xl mx-auto px-4 mt-16">
        <form method="GET" class="mb-6">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <label for="tanggal" class="text-sm font-medium text-gray-700">Filter berdasarkan tanggal:</label>
                <input 
                    type="date" 
                    name="tanggal" 
                    id="tanggal"
                    value="{{ request('tanggal') }}"
                    class="border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-amber-300 focus:outline-none"
                >
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded shadow">
                    Tampilkan
                </button>
                <a href="{{ route('warta-jemaat') }}" class="border-amber-600 text-amber-700 hover:bg-amber-50 px-4 py-2 border rounded shadow transition">Reset</a>
            </div>
        </form>
    </div>

    <!-- Grid Card -->
    <div class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

        @forelse ($wartaJemaatList as $item)
            <a href="{{ asset('storage/dokumen/wartajemaat/'.$item->file) }}" target="_blank" class="transform transition-transform duration-500 hover:scale-105">
                <div class="bg-white rounded overflow-hidden shadow-lg">
                    <img loading="lazy" src="{{ asset('images/global/warta-jemaat.webp') }}" alt="Warta Jemaat" class="w-full h-auto object-cover">
                    <div class="bg-[#231C0D] text-white px-4 py-3 text-center">
                        <h3 class="text-md font-bold uppercase">Warta Jemaat</h3>
                        <p class="text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</p>
                        <p class="text-sm">{{ $item->deskripsi }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center text-gray-600 py-8">
                <p class="text-lg">Belum ada data Warta Jemaat untuk tanggal ini.</p>
            </div>
        @endforelse

    </div>
</div>

@endsection
