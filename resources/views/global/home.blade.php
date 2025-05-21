@extends('global.layouts.app')

@section('content')

    <!-- Carousel Section -->
    <div x-data="{ 
          current: 0, 
          images: [
            '/images/gereja/foto-5.webp', 
            '/images/gereja/foto-1.webp',
          ],
          startAutoSlide() {
            setInterval(() => {
              this.current = (this.current === this.images.length - 1 ? 0 : this.current + 1);
            }, 30000); // Ganti gambar setiap 25 detik
          }
        }" x-init="startAutoSlide()" class="relative flex items-center justify-center">

      <!-- Images -->
      <div class="overflow-hidden w-full md:w-full lg:w-full xl:w-full transition-transform duration-500">
        <div x-show="current === 0" class="w-full h-auto mx-auto">
          <img :src="images[0]" loading="eager" alt="Gereja" width="1200" height="600" class="w-full h-auto object-cover shadow-md" style="height: 500px; object-position: center;">
        </div>
        <div x-show="current === 1" class="w-full h-auto mx-auto">
          <img :src="images[1]" loading="eager" alt="Gereja" width="1200" height="600" class="w-full h-auto object-cover shadow-md" style="height: 500px; object-position: center;">
        </div>
      </div>

      <!-- Arrows -->
      <button @click="current = (current === 0 ? images.length - 1 : current - 1)" 
          class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-transparent text-gray-800 border-1 p-4 rounded-full shadow-md transition-transform duration-500 ease-in-out hover:bg-gray-800 hover:text-white hover:scale-110 opacity-25 hover:opacity-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button>

      <button @click="current = (current === images.length - 1 ? 0 : current + 1)" 
              class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-transparent text-gray-800 border-1 p-4 rounded-full shadow-md transition-transform duration-500 ease-in-out hover:bg-gray-800 hover:text-white hover:scale-110 opacity-25 hover:opacity-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>

    </div>

    {{-- Informasi Gereja Section --}}
    <div class="max-w-full mx-auto w-full py-20 text-center relative">
      <!-- Background Layer dengan Opacity -->
      <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/images/bg-login 2.webp'); opacity: 0.6;"></div>
    
      <!-- Konten -->
      <div class="relative z-10 mb-12">
        <!-- Welcome Section -->
        <h1 class="text-2xl md:text-3xl  font-bold">Selamat Datang di Web GPIB Immanuel Malang</h1>
        <p class="mt-4 text-gray-600">Halaman utama portal gereja</p>

      </div>
    
      <div class="max-w-7xl mx-auto px-4 py-8 relative z-10">
        <div class="flex justify-center gap-6">
    
            <!-- Card Jumlah Keluarga -->
            <div class="w-auto text-center">
              <p id="jumlah_keluarga" class="text-2xl md:text-3xl font-bold text-amber-700 text-shadow" style="font-size: 40px;">0</p>
              <div class="flex justify-center items-center gap-2 mt-4 text-amber-700">
                <i class="fas fa-users text-xl text-gray-500"></i>
                <h3 class="text-xl md:text-2xl font-semibold text-gray-700">Jumlah Keluarga Jemaat</h3>
              </div>
            </div>

            <!-- Card Jumlah Sektor -->
            <div class="w-auto text-center">
              <p id="jumlah_sektor" class="text-2xl md:text-3xl font-bold text-amber-400 text-shadow" style="font-size: 40px;">0</p>
              <div class="flex justify-center items-center gap-2 mt-4 text-amber-400">
                <i class="fas fa-layer-group text-xl text-gray-500"></i>
                <h3 class="text-xl md:text-2xl font-semibold text-gray-700">Jumlah Sektor</h3>
              </div>
            </div>
   
        </div>
      </div>
    
      <!-- Interaktif Section (Optional) -->
      <div class="max-w-4xl mx-auto space-y-6 mt-20 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 justify-center">
          <!-- Card Kontak Kami -->
          <div class="p-6 bg-[#D9C6A1] rounded-lg shadow-md hover:bg-[#B68E5F] transition-colors mx-auto">
            <h4 class="font-semibold text-lg ">Kontak Kami</h4>
            <p class="text-sm ">Hubungi kami untuk pertanyaan atau permintaan lebih lanjut tentang pelayanan kami.</p>
            <a href="{{ route('kontak') }}" class="inline-block mt-4 px-6 py-2 bg-[#614D24] text-white rounded-full hover:bg-[#4f3f1c] focus:outline-none">Kontak Kami</a>
          </div>
        
        </div>
      </div>
    </div>

    {{-- Navigasi Cepat --}} 
    <div class="bg-[#F6F5EF] max-w-full mx-auto px-4 py-8" style="font-family: 'Lato', sans-serif;">
      <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
          
          <!-- Card 1 -->
          <a href="{{ route('warta-jemaat') }}" class="transform transition-transform duration-500 hover:scale-105">
            <div class="bg-[#231C0D] rounded-lg shadow-md overflow-hidden hover:bg-[#4f3f1c]">
                <img loading="lazy" src="/images/global/warta-jemaat.webp" alt="Warta Jemaat" class="w-full h-54 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-bold text-white mb-2">WARTA JEMAAT</h3>
                    <p class="text-white">Kumpulan Informasi Selama Satu Minggu</p>
                </div>
            </div>
          </a>
  
          <!-- Card 2 -->
          <a href="{{ route('tata-ibadah') }}" class="transform transition-transform duration-500 hover:scale-105">
            <div class="bg-[#231C0D] rounded-lg shadow-md overflow-hidden hover:bg-[#4f3f1c]">
                <img loading="lazy" src="/images/global/tata-ibadah.webp" alt="Tata Ibadah" class="w-full h-54 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-bold text-white mb-2">TATA IBADAH</h3>
                    <p class="text-white">Liturgi Ibadah Umum dan Ibadah Lainnya</p>
                </div>
            </div>
          </a>
  
          <!-- Card 3 -->
          <a href="{{ route('kanal-youtube') }}" class="transform transition-transform duration-500 hover:scale-105">
            <div class="bg-[#231C0D] rounded-lg shadow-md overflow-hidden hover:bg-[#4f3f1c]">
                <img loading="lazy" src="/images/global/live-streaming.webp" alt="Live Streaming" class="w-full h-54 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-bold text-white mb-2">LIVE STREAMING</h3>
                    <p class="text-white">Ibadah Daring Melalui Kanal Youtube GPIB Immanuel Malang</p>
                </div>
            </div>
          </a>
  
          <!-- Card 4 -->
          <a href="{{ route('persembahan') }}" class="transform transition-transform duration-500 hover:scale-105">
            <div class="bg-[#231C0D] rounded-lg shadow-md overflow-hidden hover:bg-[#4f3f1c]">
                <img loading="lazy" src="/images/global/persembahan-digital.webp" alt="Persembahan Digital" class="w-full h-54 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-bold text-white mb-2">PERSEMBAHAN DIGITAL</h3>
                    <p class=" text-white">Memberikan Persembahan Melalui Platform Digital</p>
                </div>
            </div>
          </a>
  
      </div>
    </div>

    {{-- TEMA TAHUNAN --}}
    <div class="max-w-7xl mx-auto px-4 py-16">
      <h2 class="text-2xl md:text-3xl  font-bold text-center mb-12 text-[#231C0D]">Tema Tahunan GPIB</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-t-[0.5px] border-b-[0.5px] border-[#231C0D]/50">
        
        <!-- Kolom 1 -->
        <div class="p-6">
          <h3 class="text-lg font-bold text-[#4E3B1F] mb-4">TEMA SENTRAL</h3>
          <p class="text-gray-700">“Yesus Kristus Sumber Damai Sejahtera" (Yohanes 14:27)</p>
          </div>
      
          <!-- Kolom 2 -->
          <div class="p-6">
          <h3 class="text-lg font-bold text-[#4E3B1F] mb-4">TEMA JANGKA PENDEK IV (2022-2026)</h3>
          <p class="text-gray-700">“Membangun sinergi dalam hubungan gereja dan masyarakat untuk mewujudkan Kasih Allah yang meliputi seluruh ciptaan-Nya” ( Matius 22 : 37 – 39; Ulangan 6 : 5; Immamat 19 : 18 )</p>
          </div>
      
          <!-- Kolom 3 -->
          <div class="p-6">
          <h3 class="text-lg font-bold text-[#4E3B1F] mb-4">TEMA TAHUNAN (2025-2026)</h3>
          <p class="text-gray-700">“Memperteguh Panggilan dan Pengutusan Gereja secara Intergenerasional dengan Mendayagunakan Teknologi Digital untuk Mewujudkan Kasih Allah dalam Seluruh Ciptaan”
              ( Yesaya 42 : 5 – 7 )</p>
        </div>
    
      </div>
    </div>

    {{-- SEJARAH SINGKAT --}}
    <section class="max-w-full mx-auto px-6 py-16 bg-[#231C0D] text-white">
      <div class="max-w-7xl mx-auto px-4 py-8 grid md:grid-cols-2 gap-8 items-center">
        
        <!-- Konten Teks -->
        <div>
          <h3 class="text-lg text-white mb-0 uppercase italic">GPIB Immanuel Malang</h3>
          <h2 class="text-4xl font-semibold text-[#D9C6A1] mb-8 uppercase italic tracking-wide md:tracking-[2.4px]">Sejarah Singkat</h2>
          <p class="text-gray-200 leading-relaxed">
            GPIB Immanuel Malang, yang berdiri sejak 1861, merupakan gereja tertua di Kota Malang dan dikenal dengan sebutan "Gereja Jago." 
            Gereja ini telah ditetapkan sebagai cagar budaya dan memiliki tiga gedung yang berlokasi di Jalan Merdeka Barat No. 9, Jalan Pattimura No. 10, 
            serta di Pakisaji. Bangunannya telah mengalami beberapa renovasi namun tetap mempertahankan keindahan arsitektur aslinya, 
            yang menjadikannya sebagai salah satu ikon sejarah di kota ini.
          </p>
          <a href="{{ route('sejarah-gereja') }}" class="mt-4 inline-block font-semibold bg-[#614D24] text-white px-5 py-2 rounded-full focus:outline-none hover:bg-[#4f3f1c] transition">
            Baca Selengkapnya
          </a>
        </div>
    
        <!-- Gambar -->
        <div>
          <img loading="lazy" src="/images/gereja/foto-5.webp" alt="Gereja Jago" class="w-full h-auto rounded-lg shadow-md object-cover">
        </div>
    
      </div>
    </section>

    {{-- PELKAT --}}
    <section class="max-w-full mx-auto px-6 py-16 text-[#231C0D]">
      <h2 class="text-2xl md:text-3xl font-bold text-center mt-2 text-[#231C0D]">PELAYANAN KATEGORIAL</h2>

      <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Card 1 -->
        <a href="{{ route('pa') }}" class="transform transition-transform duration-500 hover:scale-105">
          <div class="flex items-start gap-4 p-4 rounded-lg shadow-md border-[0.5px] border-[#231C0D]/50">
            <img loading="lazy" src="/images/logo/pelkat_pa.webp" alt="PA" class="w-10 h-10 object-contain">
            <div>
              <h4 class="text-md font-bold text-[#6CC066]">PELAYANAN ANAK</h4>
              <p class="text-[#231C0D] text-sm">Kategori usia Pelkat PA: 0-12 tahun</p>
            </div>
          </div>
        </a>
    
        <!-- Card 2 -->
        <a href="{{ route('pt') }}" class="transform transition-transform duration-500 hover:scale-105">
          <div class="flex items-start gap-4 p-4 rounded-lg shadow-md border-[0.5px] border-[#231C0D]/50">
            <img loading="lazy" src="/images/logo/pelkat_pt.webp" alt="PT" class="w-10 h-10 object-contain">
            <div>
              <h4 class="text-md font-bold text-[#FFE100]">PERSEKUTUAN TERUNA</h4>
              <p class="text-[#231C0D] text-sm">Kategori usia Pelkat PT : 13-17 tahun</p>
            </div>
          </div>
        </a>
    
        <!-- Card 3 -->
        <a href="{{ route('gp') }}" class="transform transition-transform duration-500 hover:scale-105">
          <div class="flex items-start gap-4 p-4 rounded-lg shadow-md border-[0.5px] border-[#231C0D]/50">
            <img loading="lazy" src="/images/logo/pelkat_gp.webp" alt="GP" class="w-10 h-10 object-contain">
            <div>
              <h4 class="text-md font-bold text-[#28166F]">GERAKAN PEMUDA</h4>
              <p class="text-[#231C0D] text-sm">Kategori usia Pelkat GP : 18-35 tahun</p>
            </div>
          </div>
        </a>
    
        <!-- Card 4 -->
        <a href="{{ route('pkp') }}" class="transform transition-transform duration-500 hover:scale-105">
          <div class="flex items-start gap-4 p-4 rounded-lg shadow-md border-[0.5px] border-[#231C0D]/50">
            <img loading="lazy" src="/images/logo/pelkat_pkp.webp" alt="PKP" class="w-10 h-10 object-contain">
            <div>
              <h4 class="text-md font-bold text-[#4A2D7A]">PERSEKUTUAN KAUM PEREMPUAN</h4>
              <p class="text-[#231C0D] text-sm">Kategori usia Pelkat PKP: 36-59 tahun</p>
            </div>
          </div>
        </a>
    
        <!-- Card 5 -->
        <a href="{{ route('pkb') }}" class="transform transition-transform duration-500 hover:scale-105">
          <div class="flex items-start gap-4 p-4 rounded-lg shadow-md border-[0.5px] border-[#231C0D]/50">
            <img loading="lazy" src="/images/logo/pelkat_pkb.webp" alt="PKB" class="w-10 h-10 object-contain">
            <div>
              <h4 class="text-md font-bold text-[#73706F]">PERSEKUTUAN KAUM BAPAK</h4>
              <p class="text-[#231C0D] text-sm">Kategori usia Pelkat PKB : 36-59 tahun</p>
            </div>
          </div>
        </a>
    
        <!-- Card 6 -->
        <a href="{{ route('pklu') }}" class="transform transition-transform duration-500 hover:scale-105">
          <div class="flex items-start gap-4 p-4 rounded-lg shadow-md border-[0.5px] border-[#231C0D]/50">
            <img loading="lazy" src="/images/logo/pelkat_pklu.webp" alt="PKLU" class="w-10 h-10 object-contain">
            <div>
              <h4 class="text-md font-bold text-[#FF853E]">PERSEKUTUAN KAUM LANJUT USIA</h4>
              <p class="text-[#231C0D] text-sm">Kategori usia Pelkat PKLU : 60 tahun ke atas</p>
            </div>
          </div>
        </a>
    
      </div>
    </section>
    {{-- <section class="max-w-full mx-auto px-6 py-16 text-[#231C0D]">
      <h2 class="text-2xl md:text-3xl font-bold text-center mt-2 text-[#231C0D]">PELAYANAN KATEGORIAL</h2>
      <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> --}}

    {{-- JADWAL IBADAH --}}
    <section class="max-w-full mx-auto px-4 py-8 mb-12 bg-white">
      <!-- Judul -->
      <h2 class="text-2xl md:text-3xl font-bold text-center mb-8 text-[#231C0D]">JADWAL IBADAH RUTIN</h2>

      <!-- Grid Card -->
      <div class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-3 gap-6 md:px-20">

        <!-- Card 1: Ibadah Minggu Umum -->
        <div class="bg-gradient-to-br from-[#231C0D] to-[#614D24] rounded-2xl text-white shadow-lg p-6">
          <h3 class="text-lg font-bold mb-4 flex items-center">
            <i class="fas fa-church mr-2 text-white/80"></i> Ibadah Minggu Umum
          </h3>
          <ul class="text-sm space-y-1">
            <li><span class="font-semibold">EBED</span> - pk 06.00</li>
            <li><span class="font-semibold">IMMANUEL</span> - pk 08.00</li>
            <li><span class="font-semibold">PAKISAJI</span> - pk 09.00</li>
            <li><span class="font-semibold">IMMANUEL</span> - pk 17.00</li>
          </ul>
        </div>

        <!-- Card 2: Ibadah Keluarga -->
        <div class="bg-gradient-to-br from-[#231C0D] to-[#614D24] rounded-2xl text-white shadow-lg p-6">
          <h3 class="text-lg font-bold mb-4 flex items-center">
            <i class="fas fa-house-user mr-2 text-white/80"></i> Ibadah Keluarga (Rabu)
          </h3>
          <ul class="text-sm space-y-1">
            <li>Sektor 1–5 & 7–9 - pk 17.00</li>
            <li>Sektor 6 - pk 18.00</li>
          </ul>
        </div>

        <!-- Card 3: Ibadah Pelkat -->
        <div class="bg-gradient-to-br from-[#231C0D] to-[#614D24] rounded-2xl text-white shadow-lg p-6">
          <h3 class="text-lg font-bold mb-4 flex items-center">
            <i class="fas fa-users mr-2 text-white/80"></i> Ibadah Pelkat (IMM - Pakisaji)
          </h3>
          <ul class="text-sm space-y-1">
            <li><span class="font-semibold">Pelkat PA</span> - Minggu pk 08.00 & 09.00</li>
            <li><span class="font-semibold">Pelkat PT</span> - Minggu pk 08.00</li>
            <li><span class="font-semibold">Pelkat GP</span> - Jumat/Sabtu pk 18.30/18.00</li>
            <li><span class="font-semibold">Pelkat PKP</span> - Selasa pk 17.00</li>
            <li><span class="font-semibold">Pelkat PKB</span> - Sabtu pk 17.00</li>
            <li><span class="font-semibold">Pelkat PKLU</span> - Sabtu pk 10.00</li>
          </ul>
        </div>
      </div>

      <!-- CTA Tombol -->
      <div class="text-center mt-8 px-10">
        <p class="text-sm text-gray-600 mb-3">Jadwal Ibadah Non Rutin Lainnya Dapat Dilihat di Laman Berikut Ini</p>
        {{-- <a href="#" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-semibold px-6 py-2 rounded-full transition"> --}}
        <a href="{{ route('ibadah-rutin') }}" class="inline-block font-semibold bg-[#614D24] text-white px-5 py-2 rounded-full focus:outline-none hover:bg-[#4f3f1c] transition">
          Lihat Jadwal
        </a>
      </div>
    </section>

       
@endsection

@push('css')
@endpush

@push('js')
  <script>
    // Function to animate number
    function animateNumber(elementId, targetNumber, duration = 2000) {
        const element = document.getElementById(elementId);
        let startTime = null;

        // Function to update the number
        function updateNumber(currentTime) {
            if (!startTime) startTime = currentTime;
            const progress = currentTime - startTime;
            const number = Math.min(Math.floor(progress / duration * targetNumber), targetNumber);
            element.textContent = number;

            if (progress < duration) {
                requestAnimationFrame(updateNumber);
            }
        }

        requestAnimationFrame(updateNumber);
    }

    // Simulate fetching numbers from the database
    const jumlah_keluarga = {{ $jumlah_keluarga }}
    const jumlah_sektor = {{ $jumlah_sektor }}

    // Trigger the animations
    animateNumber('jumlah_keluarga', jumlah_keluarga);
    animateNumber('jumlah_sektor', jumlah_sektor);
  </script>
@endpush
