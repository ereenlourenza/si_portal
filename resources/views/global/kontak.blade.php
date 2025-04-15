@extends('global.layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-12 leading-relaxed">
    {{-- Judul --}}
    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-4 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Kontak
    </h1>

    {{-- Konten utama --}}
    <div class="grid md:grid-cols-2 gap-8 p-6 rounded-lg">
        
        {{-- Informasi kontak --}}
        <div>
            <h2 class="text-xl font-semibold text-amber-700 mb-4">Hubungi Kami</h2>
            <ul class="space-y-4 text-gray-700">
                <li class="flex items-start gap-3">
                    <i class="fas fa-map-marker-alt w-6 h-6 text-amber-500"></i>
                    <span>Jl. Arif Rahman Hakim no. 1 Malang 65119</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-envelope w-6 h-6 text-amber-500"></i>
                    <span>Email: gpibimmanuelmalang@gmail.com</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-phone-alt w-6 h-6 text-amber-500"></i>
                    <span>Telepon: (0341) 325850</span>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fas fa-clock w-6 h-6 text-amber-500"></i>
                    <span>Jam Pelayanan: Senin - Jumat, 08.00 - 16.00 WIB (Senin Libur)</span>
                </li>
            </ul>
        </div>

        {{-- Google Maps Embed --}}
        <div>
            <h2 class="text-xl font-semibold text-amber-700 mb-4">Lokasi</h2>
            <div class="aspect-w-16 aspect-h-9">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d349.2372641971297!2d112.62976035464443!3d-7.9815168833801975!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd629def03fe075%3A0x4fff02dadc396940!2sGPIB%20Immanuel%20Malang%20(%20Gereja%20Concordia%20)!5e0!3m2!1sid!2sid!4v1744705266526!5m2!1sid!2sid" 
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>

</div>

@endsection
