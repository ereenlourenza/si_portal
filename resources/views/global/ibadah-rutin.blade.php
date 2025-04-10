@extends('global.layouts.app')

@section('content')
<div class="container-fluid py-12 space-y-6">

        <h1 class="max-w-5xl mx-auto text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow">
            Jadwal Ibadah Rutin
        </h1>

        {{-- ALAMAT --}}
        <div class="max-w-4xl mx-auto gap-8 px-8 py-10">
            <div class="flex flex-col gap-3">
  
                <!-- GPIB Immanuel Malang -->
                <a href="https://maps.app.goo.gl/Stn4PP1sXgxWfdmu6" 
                    target="_blank"
                    class="block p-8 hover:-translate-y-1 bg-[#614D24] hover:bg-[#4f3f1c] text-white px-5 py-4 rounded-xl transition text-sm font-medium shadow-sm hover:shadow-2xl duration-300 space-y-2">
                    
                    <div class="flex items-center gap-4">
                        <!-- Icon -->
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-5 h-5 text-[#614D24]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7z" />
                            </svg>
                        </div>
                
                        <!-- Teks -->
                        <div>
                            <h2 class="text-sm md:text-base font-bold">GPIB Immanuel Malang</h2>
                            <p class="text-xs text-white leading-snug">
                                Jl. Merdeka Barat No. 9, Kel. Kiduldalem, Kec. Klojen, Kota Malang 65119
                            </p>
                        </div>
                    </div>
                </a>
                
        
            
                <!-- GPIB Ebed -->
                <a href="https://maps.app.goo.gl/NNUeUxSVdNNtZkV98" 
                    target="_blank"
                    class="block p-8 hover:-translate-y-1 bg-[#614D24] hover:bg-[#4f3f1c] text-white px-5 py-4 rounded-xl transition text-sm font-medium shadow-sm hover:shadow-2xl duration-300 space-y-2">

                    <div class="flex items-center gap-4">
                        <!-- Icon -->
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-5 h-5 text-[#614D24]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7z" />
                            </svg>
                        </div>
                
                        <!-- Teks -->
                        <div>
                            <h2 class="text-sm md:text-base font-bold">GPIB Ebed</h2>
                            <p class="text-xs text-white leading-snug">
                                Jl. Pattimura No. 10, Kel. Klojen, Kec. Klojen, Kota Malang 65111
                            </p>
                        </div>
                    </div>
                    
                </a>
        
            
                <!-- GPIB Pakisaji -->
                <a href="https://maps.app.goo.gl/gcz4Hc3cWGRP2Yzi6" 
                    target="_blank"
                    class="block p-8 hover:-translate-y-1 bg-[#614D24] hover:bg-[#4f3f1c] text-white px-5 py-4 rounded-xl transition text-sm font-medium shadow-sm hover:shadow-2xl duration-300 space-y-2">
                    
                    <div class="flex items-center gap-4">
                        <!-- Icon -->
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-5 h-5 text-[#614D24]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7z" />
                            </svg>
                        </div>
                
                        <!-- Teks -->
                        <div>
                            <h2 class="text-sm md:text-base font-bold">GPIB Pakisaji</h2>
                            <p class="text-xs text-white leading-snug">
                                Jl. Tambak Sari, Kedungmonggo, Karangpandan, Pakisaji, Kab. Malang 65162
                            </p>
                        </div>
                    </div>

                </a>

            </div>
          
        </div>
          

        <div class="max-w-4xl mx-auto px-8 rounded-2xl">
              
            {{-- IBADAH MINGGU UMUM --}}
            <div class="px-2 py-4">
                <h3 class="text-md md:text-lg font-semibold text-amber-700">
                    Ibadah Minggu Umum ({{ \Carbon\Carbon::parse($tanggal_minggu)->format('d F Y') }})
                </h3>
            </div>
            <div class="overflow-x-auto p-4 rounded-xl shadow-2xl border-t border-gray-300">
                <table class="min-w-full text-center rounded-lg">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="text-[13px] md:text-[15px] px-4 py-2">EBED Pk 06.00</th>
                            <th class="text-[13px] md:text-[15px] px-4 py-2">IMMANUEL Pk 08.00</th>
                            <th class="text-[13px] md:text-[15px] px-4 py-2">PAKISAJI Pk 09.00</th>
                            <th class="text-[13px] md:text-[15px] px-4 py-2">IMMANUEL Pk 17.00</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="odd:bg-gray-50">
                            <td class="text-[13px] md:text-[15px] px-4 py-2">{{ $ebed_pagi->pelayan_firman ?? '-' }}</td>
                            <td class="text-[13px] md:text-[15px] px-4 py-2">{{ $immanuel_pagi->pelayan_firman ?? '-' }}</td>
                            <td class="text-[13px] md:text-[15px] px-4 py-2">{{ $pakisaji->pelayan_firman ?? '-' }}</td>
                            <td class="text-[13px] md:text-[15px] px-4 py-2">{{ $immanuel_sore->pelayan_firman ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
        </div>

        {{-- IBADAH KELUARGA RABU --}}
        <div class="max-w-4xl px-8 mx-auto rounded-2xl">
            <div class="px-2 py-4">
                <h3 class="text-md md:text-lg text-lg font-semibold text-amber-700">
                    Ibadah Keluarga Rabu ({{ \Carbon\Carbon::parse($tanggal_rabu)->format('d F Y') }})
                </h3>
            </div>
            <div class="overflow-x-auto p-4 rounded-xl shadow-2xl border-t border-gray-300">
                <table class="min-w-full text-center border-gray-300 rounded-lg">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Sektor</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Tempat</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Lokasi</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Waktu</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Pelayan Firman</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($jadwalRabu as $item)
                            <tr class="odd:bg-gray-50">
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->sektor }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->tempat }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->lokasi }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->pelayan_firman }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- IBADAH PENGUCAPAN SYUKUR --}}
        <div class="max-w-4xl px-8 mx-auto rounded-2xl">
            <div class="px-2 py-4">
                <h3 class="text-md md:text-lg text-lg font-semibold text-amber-700">Ibadah Pengucapan Syukur</h3>
            </div>
            <div class="overflow-x-auto p-4 rounded-xl shadow-2xl border-t border-gray-300">
                <table class="min-w-full text-center rounded-lg">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Sektor</th>
                            {{-- <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Tanggal</th> --}}
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Tempat</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Lokasi</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Waktu</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Pelayan Firman</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($jadwalSyukur as $item)
                            <tr class="odd:bg-gray-50">
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->sektor }}</td>
                                {{-- <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td> --}}
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->tempat }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->lokasi }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->pelayan_firman }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-2 text-gray-500">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- IBADAH DIAKONIA --}}
        <div class="max-w-4xl px-8 mx-auto rounded-2xl">
            <div class="px-2 py-4">
                <h3 class="text-md md:text-lg text-lg font-semibold text-amber-700">Ibadah Diakonia</h3>
            </div>
            <div class="overflow-x-auto p-4 rounded-xl shadow-2xl border-t border-gray-300">
                <table class="min-w-full text-center rounded-lg">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Tanggal</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Tempat</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Lokasi</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Waktu</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Pelayan Firman</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($jadwalDiakonia as $item)
                            <tr class="odd:bg-gray-50">
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->tempat }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->lokasi }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->pelayan_firman }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-2 text-gray-500">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
                
            </div>
            <p class="text-[12px] px-2 text-gray-500 mt-4">
                <strong>Ibadah Khusus Anggota Diakonia</strong><br>
                Note: Setelah ibadah akan diadakan sumbangan rutin untuk Anggota Diakonia
            </p>
        </div>

        {{-- IBADAH PELKAT --}}
        <div class="max-w-4xl px-8 mx-auto rounded-2xl mb-10">
            <div class="px-2 py-4 ">
                <h3 class="text-md md:text-lg text-lg font-semibold text-amber-700">Ibadah Pelkat</h3>
            </div>
            <div class="overflow-x-auto p-4 rounded-xl shadow-2xl border-t border-gray-300">
                <table class="min-w-full text-center rounded-lg">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Tanggal</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Pelkat</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Tempat</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Waktu</th>
                            <th class="text-[13px] md:text-[15px]  px-4 py-2 ">Pelayan Firman</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($jadwalPelkat as $item)
                            <tr class="odd:bg-gray-50">
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->nama_pelkat }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->ruang }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}</td>
                                <td class="text-[13px] md:text-[15px]  px-4 py-2 ">{{ $item->pelayan_firman }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-2 text-gray-500">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

</div>
@endsection
