<footer class="bg-[#231C0D] text-white py-12 px-4 md:px-8 lg:px-16">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 md:items-start">
  
      <!-- Kiri (Logo dan Deskripsi) -->
      <div class="lg:col-span-2 space-y-4">
        <h3 class="text-lg md:text-xl font-bold text-[#D99841]">
          GPIB JEMAAT “IMMANUEL” MALANG
        </h3>
        <div class="flex items-start gap-6">
          <img src="/images/logo-gpib-full.webp" alt="Logo GPIB" class="w-16 md:w-20 shrink-0">
          <div class="space-y-3 text-sm text-gray-300 leading-relaxed">
            <p>
              GPIB Immanuel Malang, yang berdiri sejak 1861, merupakan gereja tertua di Kota Malang dan dikenal dengan sebutan "Gereja Jago." Gereja ini telah ditetapkan sebagai cagar budaya.
            </p>
            <a href="{{ route('sejarah-gereja') }}" class="inline-block bg-[#614D24] text-white px-5 py-2 rounded-full font-semibold focus:outline-none hover:bg-[#4f3f1c] transition">
              Baca Selengkapnya
            </a>
            <!-- Sosial Media -->
            <div class="flex gap-4 mt-2 text-[#D99841] text-lg">
                <a href="#" class="hover:text-white transition" aria-label="Facebook">
                <i class="fab fa-whatsapp"></i>
                </a>
                <a href="#" class="hover:text-white transition" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="hover:text-white transition" aria-label="YouTube">
                <i class="fab fa-youtube"></i>
                </a>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Tengah: Navigasi Menu -->
      <div>
        <h4 class="text-[#D99841] font-semibold text-md mb-4">NAVIGASI MENU</h4>
        <ul class="space-y-2 text-sm text-gray-300">
          <li><a href="{{ Route('beranda') }}" class="hover:text-[#D99841]">Beranda</a></li>
          <li><a href="{{ Route('wilayah-pelayanan') }}" class="hover:text-[#D99841]">Wilayah Pelayanan</a></li>
          <li><a href="{{ Route('ibadah-rutin') }}" class="hover:text-[#D99841]">Jadwal Ibadah</a></li>
          <li><a href="{{ Route('persembahan') }}" class="hover:text-[#D99841]">Persembahan</a></li>
          <li><a href="{{ Route('kanal-youtube') }}" class="hover:text-[#D99841]">Kanal Youtube</a></li>
          <li><a href="{{ Route('tata-ibadah') }}" class="hover:text-[#D99841]">Tata Ibadah</a></li>
          <li><a href="{{ Route('warta-jemaat') }}" class="hover:text-[#D99841]">Warta Jemaat</a></li>
          <li><a href="{{ Route('galeri') }}" class="hover:text-[#D99841]">Galeri</a></li>
          <li><a href="{{ Route('kontak') }}" class="hover:text-[#D99841]">Kontak</a></li>
        </ul>
      </div>
  
      <!-- Kanan: Kontak -->
      <div>
        <h4 class="text-[#D99841] font-semibold text-md mb-4">KANTOR SEKRETARIAT</h4>
        <ul class="space-y-4 text-sm text-gray-300">
          <li class="flex items-start gap-3">
            <i class="fas fa-map-marker-alt text-[#D99841] mt-1"></i>
            <span>Jalan Arif Rahman Hakim no. 1<br>Malang 65119</span>
          </li>
          <li class="flex items-center gap-3">
            <i class="fas fa-clock text-[#D99841]"></i>
            <span>08.00 - 16.00 (Senin Libur)</span>
          </li>
          <li class="flex items-center gap-3">
            <i class="fas fa-phone-alt text-[#D99841]"></i>
            <span>(0341) 325850</span>
          </li>
          <li class="flex items-center gap-3">
            <i class="fas fa-envelope text-[#D99841]"></i>
            <span>gpibimmanuelmalang@gmail.com</span>
          </li>
        </ul>
      </div>
      
    </div>
  
    <!-- Footer Bawah -->
    <div class="mt-12 border-t border-[#3D2F1C] pt-6 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400 gap-2">
      <p>© {{ date('Y') }} <span class="font-semibold text-white">GPIB Immanuel Malang</span>. All rights reserved.</p>
      <p>Church Website by <span class="font-semibold text-white">Ereen</span></p>
    </div>
  </footer>

  {{-- <script>
    document.getElementById("currentYear").textContent = new Date().getFullYear();
  </script>
   --}}