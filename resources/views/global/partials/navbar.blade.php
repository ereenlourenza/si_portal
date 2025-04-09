{{-- Navbar responsive pakai Alpine.js --}}
<div x-data="{ open: false, dropdown: null }">

    {{-- Top Bar --}}
    <div class="bg-white text-sm text-[#231C0D] border-b-[0.5px]  border-[#231C0D]/50">
      <div class="max-w-7xl mx-auto flex justify-between items-center py-1 px-4">
        
        {{-- Informasi Kontak --}}
        <div class="flex flex-wrap gap-6">

          <!-- Desktop: tampil lengkap -->
          <div class="hidden md:inline-flex gap-6">
            <span><i class="bi bi-telephone-fill mr-1"></i> (0341) 325850</span>
            <span><i class="bi bi-geo-alt-fill mr-1"></i> Jl. Merdeka Barat No. 9, Malang</span>
            <span>
              <i class="bi bi-clock-fill mr-1"></i>
              <span class="ml-1">Kantor Sekretariat:</span>
              <b class="ml-1">08.00 - 16.00 WIB</b>
              <span class="ml-1">(Senin Libur)</span>
            </span>
          </div>

          {{-- Icon 1 (kiri): pakai left-0
          Icon 2 (tengah): tetap pakai left-1/2 -translate-x-1/2
          Icon 3 (kanan): pakai right-0 --}}
        
          <!-- Mobile: hanya icon + info klik -->
          <div class="flex md:hidden gap-0" x-data="{ info: null }">
            
            <!-- Telepon -->
            <div x-data="{ info: null }" class="relative">
              <i class="bi bi-telephone-fill cursor-pointer w-8 h-8 rounded-full flex items-center justify-center text-base hover:bg-[#231C0D] hover:text-white transition" 
                  @click="info = (info === 'tel' ? null : 'tel')" ></i>
              <div x-show="info === 'tel'" x-transition
                  @click.away="info = null"
                  class="absolute bg-white text-[#231C0D] shadow px-3 py-1 text-sm rounded mt-2 left-0 z-60 min-w-[150px] whitespace-nowrap text-center">
                (0341) 325850
              </div>
            </div>

            <!-- Alamat -->
            <div x-data="{ info: null }" class="relative">
              <i class="bi bi-geo-alt-fill cursor-pointer w-8 h-8 rounded-full flex items-center justify-center text-base hover:bg-[#231C0D] hover:text-white transition" 
                  @click="info = (info === 'addr' ? null : 'addr')"></i>
              <div x-show="info === 'addr'"  x-transition
                  @click.away="info = null"
                  class="absolute bg-white text-[#231C0D] shadow px-3 py-1 text-sm rounded mt-2 left-0 z-60 min-w-[150px] whitespace-nowrap text-center">
                Jl. Merdeka Barat No. 9, Malang
              </div>
            </div>

            <!-- Jam -->
            <div x-data="{ info: null }" class="relative">
              <i class="bi bi-clock-fill cursor-pointer w-8 h-8 rounded-full flex items-center justify-center text-base hover:bg-[#231C0D] hover:text-white transition" 
                  @click="info = (info === 'time' ? null : 'time')"></i>
              <div x-show="info === 'time'" x-transition
                  @click.away="info = null"
                  class="absolute bg-white text-[#231C0D] shadow px-3 py-1 text-sm rounded mt-2 left-0 z-60 whitespace-nowrap text-center">
                  Kantor Sekretariat : 08.00 - 16.00 WIB <br> (Senin Libur)
              </div>
            </div>
          </div>
        </div>
        
        {{-- Sosial Media --}}
        <div class="flex gap-0">
          <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center text-base hover:bg-[#231C0D] hover:text-white transition">
            <i class="fas fa-envelope"></i>
          </a>
          <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center text-base hover:bg-[#231C0D] hover:text-white transition">
            <i class="fab fa-whatsapp"></i>
          </a>
          <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center text-base hover:bg-[#231C0D] hover:text-white transition">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center text-base hover:bg-[#231C0D] hover:text-white transition">
            <i class="fab fa-youtube"></i>
          </a>
        </div>
      </div>
    </div>
  
    <header class="bg-white shadow ">
      {{-- Baris atas: logo dan sign in --}}
      <div class="max-w-6xl mx-auto px-4 md:px-8 py-4 md:py-6 flex justify-between items-center">
        
        {{-- Logo --}}
        <a href="/">
          <div class="flex items-center space-x-2">
              <img src="{{ asset('images/logo-gpib-full.png') }}" alt="Logo" class="w-14 h-14 md:w-22 md:h-22">
          
              <div class="ml-2 md:ml-6">
                  <div class="text-lg md:text-3xl" style="font-family: 'Playfair Display', serif; font-weight: 700;">
                      GPIB IMMANUEL MALANG
                  </div>
                  <div class="text-sm md:text-lg tracking-wide md:tracking-[2.4px]" style="font-family: 'Platypi', serif;">
                      GEREJA JAGO
                  </div>
              </div>
          </div>
        </a>
    
        <!-- Tombol Sign In (Desktop) -->
        <a href="{{ route('login.index') }}"
          class="hidden md:inline-block bg-amber-600 text-white px-6 py-2 rounded-full text-sm md:text-base tracking-wide shadow-md hover:bg-[#231C0D] hover:shadow-lg transition duration-300 ease-in-out">
          SIGN IN
        </a>
    
        {{-- Hamburger untuk mobile --}}
        <div class="md:hidden">
            <button @click="open = !open">
                <svg class="w-6 h-6 text-[#231C0D]" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
      </div>
    
    
    
      {{-- Baris bawah: Menu navigasi --}}
      <nav class="hidden sticky top-0 z-50 md:flex justify-center space-x-6 lg:space-x-12 xl:space-x-16 items-center text-sm" style="font-family: 'Lato', sans-serif; font-size:11px" 
      x-data="{ open: false, dropdown: null, dropdownTimeout: null }">
        
        <a href="{{ route('beranda') }}" 
          class="px-2 py-3 md:tracking-[2.4px] rounded transition
                  {{ request()->is('beranda') ? 'bg-[#231C0D] text-white' : 'hover:bg-[#231C0D] hover:text-white' }}">
          BERANDA
        </a>

        @php
          $isProfilActive = request()->is('tentang-gpib') || request()->is('visi-misi') || request()->is('pemahaman-iman') || request()->is('simbol-tahun-gereja') || request()->is('sejarah-gereja') || request()->is('wilayah-pelayanan');
        @endphp

        <!-- Dropdown PROFIL -->
        <div class="relative group" 
           @mouseenter="clearTimeout(dropdownTimeout); dropdown = 'profil'"
           @mouseleave="dropdownTimeout = setTimeout(() => dropdown = null, 200)"
          >
          <button 
            @click="dropdown === 'profil' ? dropdown = null : dropdown = 'profil'"
            class="px-2 py-3 md:tracking-[2.4px] rounded transition 
              {{ $isProfilActive ? 'bg-[#231C0D] text-white' : 'hover:bg-[#231C0D] hover:text-white' }}"
          >
            PROFIL
          </button>

          <!-- Dropdown satu kotak, isinya dua kolom -->
          <div 
            x-show="dropdown === 'profil'" 
            x-transition 
            @mouseenter="clearTimeout(dropdownTimeout)"
            @mouseleave="dropdownTimeout = setTimeout(() => dropdown = null, 200)"
            class="absolute mt-2 bg-white shadow rounded p-4 z-10 w-[400px] grid grid-cols-2 gap-6"
          >
            <!-- Kolom GPIB -->
            <div>
              <div class="font-semibold text-[#231C0D] mb-2" style="font-size:13px">GPIB</div>
              <a href="{{ route('tentang-gpib') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Tentang GPIB</a>
              <a href="{{ route('visi-misi') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Visi Misi</a>
              <a href="{{ route('pemahaman-iman') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Pemahaman Iman</a>
              <a href="{{ route('simbol-tahun-gereja') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Simbol Tahun Gereja</a>
            </div>

            <!-- Kolom Immanuel Malang -->
            <div class="border-l border-gray-300 pl-4">
              <div class="font-semibold text-[#231C0D] mb-2" style="font-size:13px">Immanuel Malang</div>
              <a href="{{ route('sejarah-gereja') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Sejarah Gereja</a>
              <a href="{{ route('wilayah-pelayanan') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Wilayah Pelayanan</a>
            </div>
          </div>
        </div>

        @php
          $isPelayananActive = request()->is('ibadah-rutin') || request()->is('persembahan') || request()->is('kanal-youtube') || request()->is('baptisan') || request()->is('katekisasi') || request()->is('pemberkatan-nikah') || request()->is('peminjaman-ruangan');
        @endphp
      
        <!-- Dropdown PELAYANAN -->
        <div class="relative group" 
           @mouseenter="clearTimeout(dropdownTimeout); dropdown = 'pelayanan'"
           @mouseleave="dropdownTimeout = setTimeout(() => dropdown = null, 200)"
          >
          <button 
            @click="dropdown === 'pelayanan' ? dropdown = null : dropdown = 'pelayanan'"
            class="px-2 py-3 md:tracking-[2.4px] rounded transition 
              {{ $isPelayananActive ? 'bg-[#231C0D] text-white' : 'hover:bg-[#231C0D] hover:text-white' }}"
          >
            PELAYANAN
          </button>
          

          <!-- Dropdown satu kotak, isinya dua kolom -->
          <div 
            x-show="dropdown === 'pelayanan'" 
            x-transition 
            @mouseenter="clearTimeout(dropdownTimeout)"
            @mouseleave="dropdownTimeout = setTimeout(() => dropdown = null, 200)"
            class="absolute mt-2 bg-white shadow rounded p-4 z-10 w-[400px] grid grid-cols-2 gap-6"
          >
            <!-- Kolom Kegiatan Ibadah -->
            <div>
              <div class="font-semibold text-[#231C0D] mb-2" style="font-size:13px">Kegiatan Ibadah</div>
              <a href="{{ route('ibadah-rutin') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Ibadah Rutin</a>
              <a href="{{ route('persembahan') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Persembahan</a>
              <a href="{{ route('kanal-youtube') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Kanal Youtube</a>
            </div>

            <!-- Kolom Pelayanan Jemaat -->
            <div class="border-l border-gray-300 pl-4">
              <div class="font-semibold text-[#231C0D] mb-2" style="font-size:13px">Pelayanan Jemaat</div>
              <a href="{{ route('baptisan') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Baptisan</a>
              <a href="{{ route('katekisasi') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Katekisasi</a>
              <a href="{{ route('pemberkatan-nikah') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Pemberkatan Nikah</a>
              <a href="{{ route('peminjaman-ruangan') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Peminjaman Ruangan</a>
            </div>
          </div>
          
        </div>

        @php
          $isFungsionarisActive = request()->is('pendeta-kmj') || request()->is('vikaris') || request()->is('phmj') || request()->is('majelis-jemaat') || request()->is('pa') || request()->is('pt') || request()->is('gp') || request()->is('pkp') || request()->is('pkb') || request()->is('pklu') || request()->is('teologi') || request()->is('pelkes') || request()->is('peg') || request()->is('germasa') || request()->is('ppsdi-ppk') || request()->is('inforkom-litbang') || request()->is('bppj') || request()->is('kantor-sekretariat');
        @endphp

        <!-- Dropdown FUNGSIONARIS -->
        <div class="relative group" 
           @mouseenter="clearTimeout(dropdownTimeout); dropdown = 'fungsionaris'"
           @mouseleave="dropdownTimeout = setTimeout(() => dropdown = null, 200)"
          >
          <button 
            @click="dropdown === 'fungsionaris' ? dropdown = null : dropdown = 'fungsionaris'"
            class="px-2 py-3 md:tracking-[2.4px] rounded transition 
              {{ $isFungsionarisActive ? 'bg-[#231C0D] text-white' : 'hover:bg-[#231C0D] hover:text-white' }}"
          >
            FUNGSIONARIS
          </button>

          <!-- Dropdown satu kotak, isinya dua kolom -->
          <div 
            x-show="dropdown === 'fungsionaris'" 
            x-transition 
            @mouseenter="clearTimeout(dropdownTimeout)"
            @mouseleave="dropdownTimeout = setTimeout(() => dropdown = null, 200)"
            class="absolute mt-2 bg-white shadow rounded p-4 z-10 w-[800px] grid grid-cols-4 gap-6 left-1/2 -translate-x-1/2"
          >
            <!-- Kolom Fungsionaris -->
            <div>
              <div class="font-semibold text-[#231C0D] mb-2" style="font-size:13px">Fungsionaris</div>
              <a href="{{ route('pendeta-kmj') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Pendeta - KMJ</a>
              <a href="{{ route('vikaris') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Vikaris</a>
              <a href="{{ route('phmj') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">PHMJ</a>
              <a href="{{ route('majelis-jemaat') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Majelis Jemaat</a>
            </div>

            <!-- Kolom Pelkat -->
            <div class="border-l border-gray-300 pl-4">
              <div class="font-semibold text-[#231C0D] mb-2" style="font-size:13px">Pelkat</div>
              <a href="{{ route('pa') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">PA</a>
              <a href="{{ route('pt') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">PT</a>
              <a href="{{ route('gp') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">GP</a>
              <a href="{{ route('pkp') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">PKP</a>
              <a href="{{ route('pkb') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">PKB</a>
              <a href="{{ route('pklu') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">PKLU</a>
            </div>

            <!-- Kolom Komisi -->
            <div class="border-l border-gray-300 pl-4">
              <div class="font-semibold text-[#231C0D] mb-2" style="font-size:13px">Komisi</div>
              <a href="{{ route('teologi') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Teologi</a>
              <a href="{{ route('pelkes') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Pelkes</a>
              <a href="{{ route('peg') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">PEG</a>
              <a href="{{ route('germasa') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Germasa</a>
              <a href="{{ route('ppsdi-ppk') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">PPSDI-PPK</a>
              <a href="{{ route('inforkom-litbang') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Inforkom-Litbang</a>
            </div>

            <!-- Kolom Lain-Lain -->
            <div class="border-l border-gray-300 pl-4">
              <div class="font-semibold text-[#231C0D] mb-2" style="font-size:13px">Lain-Lain</div>
              <a href="{{ route('bppj') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">BPPJ</a>
              <a href="{{ route('kantor-sekretariat') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Kantor Sekretariat</a>
            </div>
          </div>
          
        </div>

        @php
          $isDokumenActive = request()->is('tata-ibadah') || request()->is('warta-jemaat');
        @endphp

        <!-- Dropdown DOKUMEN -->
        <div class="relative group" 
           @mouseenter="clearTimeout(dropdownTimeout); dropdown = 'dokumen'"
           @mouseleave="dropdownTimeout = setTimeout(() => dropdown = null, 200)"
          >
          <button 
            @click="dropdown === 'dokumen' ? dropdown = null : dropdown = 'dokumen'"
            class="px-2 py-3 md:tracking-[2.4px] rounded transition 
              {{ $isDokumenActive ? 'bg-[#231C0D] text-white' : 'hover:bg-[#231C0D] hover:text-white' }}"
          >
            DOKUMEN
          </button>

          <!-- Dropdown satu kotak, isinya dua kolom -->
          <div 
            x-show="dropdown === 'dokumen'" 
            x-transition 
            @mouseenter="clearTimeout(dropdownTimeout)"
            @mouseleave="dropdownTimeout = setTimeout(() => dropdown = null, 200)"
            class="absolute mt-2 bg-white shadow rounded p-4 z-10 w-[200px] grid grid-cols-1 gap-6"
          >
            <!-- Kolom Dokumen -->
            <div>
              <div class="font-semibold text-[#231C0D] mb-2" style="font-size:13px">Dokumen</div>
              <a href="{{ route('tata-ibadah') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Tata Ibadah</a>
              <a href="{{ route('warta-jemaat') }}" class="block px-2 py-1 text-sm hover:bg-gray-100 rounded">Warta Jemaat</a>
            </div>

          </div>
        </div>

        <a href="{{ route('galeri') }}" class="px-2 py-3 md:tracking-[2.4px] rounded hover:bg-[#231C0D] hover:text-white transition">GALERI</a>
        <a href="{{ route('kontak') }}" class="px-2 py-3 md:tracking-[2.4px] rounded hover:bg-[#231C0D] hover:text-white transition">KONTAK</a>
      </nav>
      
    
      {{-- Mobile Menu --}}
      <div x-show="open" x-transition class="md:hidden bg-white px-4 pb-4 space-y-2">
        <a href="{{ route('beranda') }}" class="block">Beranda</a>
        <div>
          <div 
            @click="dropdown = (dropdown === 'profil' ? null : 'profil')" 
            :class="dropdown === 'profil' ? 'bg-[#231C0D] text-white font-semibold' : ''"
            class="cursor-pointer py-1 rounded"
          >Profil</div>
          <div x-show="dropdown === 'profil'" class="pl-4 space-y-2">
            <a href="{{ route('tentang-gpib') }}" class="block">Tentang GPIB</a>
            <a href="{{ route('visi-misi') }}" class="block">Visi Misi GPIB</a>
            <a href="{{ route('pemahaman-iman') }}" class="block">Pemahaman Iman GPIB</a>
            <a href="{{ route('simbol-tahun-gereja') }}" class="block">Simbol Tahun Gereja GPIB</a>
            <a href="{{ route('sejarah-gereja') }}" class="block">Sejarah Gereja Immanuel</a>
            <a href="{{ route('wilayah-pelayanan') }}" class="block">Wilayah Pelayanan Immanuel</a>
          </div>
        </div>
        <div>
          <div @click="dropdown = (dropdown === 'pelayanan' ? null : 'pelayanan')" class="cursor-pointer">Pelayanan</div>
          <div x-show="dropdown === 'pelayanan'" class="pl-4">
            <a href="/pelkat" class="block">Pelkat</a>
            <a href="/komisi" class="block">Komisi</a>
          </div>
        </div>
        <a href="/fungsionaris" class="block">Fungsionaris</a>
        <a href="/dokumen" class="block">Dokumen</a>
        <a href="/galeri" class="block">Galeri</a>
        <a href="/kontak" class="block">Kontak</a>
        <a href="{{ route('login.index') }}" class="bg-amber-600 text-white px-4 py-1 rounded inline-block">SIGN IN</a>
      </div>
    </header>
    
  </div>
  