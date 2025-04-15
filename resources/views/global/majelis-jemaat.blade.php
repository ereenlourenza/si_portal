@extends('global.layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-4 py-12 leading-relaxed">
    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-8 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Majelis Jemaat
    </h1>

    <div class="max-w-4xl mx-auto px-4 py-4">
        <h2 class="text-xl text-amber-700 font-semibold mt-10 mb-2">Penjelasan</h2>
        <p class="mb-4">
            Majelis Jemaat adalah persekutuan kerja para Presbiter (Pendeta, Diaken, Penatua) yang merupakan pimpinan GPIB di lingkup Jemaat. Persekutuan kerja ini adalah perwujudan dari Sistem Presbyterial Sinodal yang tampak dalam Sidang Majelis Jemaat.
        </p>

        <p class="mb-4">Tugas Majelis Jemaat adalah:</p>
        <ol class="list-decimal list-inside mb-6 space-y-1">
            <li>Menjalankan keputusan dan ketetapan Persidangan Sinode GPIB dan tugas-tugas yang dipercayakan oleh Majelis Sinode.</li>
            <li>Membuat rencana kerja anggaran dan menetapkan Program Kerja dan Anggaran (PKA) yang mengacu pada KUPPG.</li>
            <li>Membuat laporan tahunan kepada Majelis Sinode GPIB.</li>
            <li>Menetapkan penatalayanan jemaat dan mengawasi pelaksanaannya.</li>
            <li>Memberdayakan unit-unit misioner.</li>
            <li>Menjaga kemurnian ajaran GPIB.</li>
        </ol>

        <p class="mb-4">Wewenang Majelis Jemaat adalah:</p>
        <ol class="list-decimal list-inside mb-6 space-y-1">
            <li>Memilih Pelaksana Harian Majelis Jemaat (PHMJ).</li>
            <li>Menetapkan langkah-langkah dan tindakan disiplin terhadap warga jemaat.</li>
            <li>Mengangkat dan memberhentikan Pengurus Unit Misioner di lingkup Jemaat.</li>
            <li>Mengajukan nama calon anggota PPMJ kepada SMJ.</li>
            <li>Menetapkan kebijakan penggembalaan dan pemberitaan Injil.</li>
            <li>Menetapkan dan mengusulkan Presbiter ke Persidangan Sinode.</li>
        </ol>

        <h2 class="text-xl text-amber-700 font-semibold mt-10 mb-2">SMJ</h2>
        <p class="mb-6">
            Sidang Majelis Jemaat (SMJ) adalah perwujudan presbiterial sinodal dan merupakan wadah pengambilan keputusan serta kebijakan di Jemaat. Sidang ini dilaksanakan setiap 3 (tiga) bulan.
        </p>

        <form method="GET" action="{{ route('majelis-jemaat') }}" class="mb-6 mt-16">
            <label for="periode" class="block font-semibold text-sm text-gray-700 mb-2">Pilih Periode Majelis</label>
            <select name="periode" id="periode" class="w-full md:w-64 border-gray-300 rounded shadow px-4 py-2" onchange="this.form.submit()">
                @foreach($groupedPeriode as $periode => $data)
                    <option value="{{ $periode }}" {{ $selectedPeriode == $periode ? 'selected' : '' }}>
                        {{ $periode }}
                    </option>
                @endforeach
            </select>
        </form>
        
        <h2 class="text-xl font-semibold text-amber-700 mb-4">
            Masa Tugas {{ $selectedPeriode }}
        </h2>

        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-amber-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">No</th>
                        <th class="px-4 py-2 text-left font-semibold">Nama</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php
                        $no = 1; // Mulai nomor urut dari 1
                    @endphp
                    @foreach($periode_terpilih as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $no++ }}</td>  <!-- Increment nomor urut untuk setiap item -->
                            <td class="px-4 py-2">{{ $item->nama }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
</div>

@endsection
