@extends('global.layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12 leading-relaxed">
    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-8 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Pelkat Pelayanan Anak
    </h1>

    <div class="max-w-4xl mx-auto px-4 py-4">
        <div class="mb-10">
            <h2 class="text-xl text-amber-700 font-semibold mb-4">PENJELASAN</h2>
            <p class="mb-2">
                Pelayanan Kategori Pelayanan Anak (Pelkat PA) adalah bagian dari unit misioner GPIB dengan tugas utamanya untuk membina dan melayani warga GPIB dalam kategori usia 0 – 12 tahun.
            </p>
            <p class="mb-2">
                Pelkat PA dibagi menjadi 3 kategori berdasarkan usia:
                <ol class="list-decimal ml-6">
                    <li>Kelas TK (0-5 tahun / Playgroup-TK)</li>
                    <li>Kelas Kecil (1-3 SD)</li>
                    <li>Kelas Tanggung (4-6 SD)</li>
                </ol>
            </p>
            <p class="mt-2">
                IHM PA dilaksanakan di 2 tempat yaitu:
                <ol class="list-decimal ml-6">
                    <li>GPIB Immanuel Malang PK. 08.00 di Ruang PA</li>
                    <li>Pos Pakisaji PK. 09.00 di Ruang PA</li>
                </ol>
            </p>
        </div>

        <div class="bg-[#6CC066] border-l-4 border-[#6CC066] p-6 rounded shadow-xl mb-10 flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-green-800 mb-2">ARTI LOGO PELKAT PA</h2>
                <ul class="list-disc ml-5 text-sm text-black">
                    <li><strong>Warna Hijau Muda:</strong> Hijau muda yang menggambarkan muda belia tumbuh berkembang.</li>
                    <li><strong>Salib:</strong> Dasar ajaran Kristus sebagai tonggak utama Pelkat Pelayanan Anak GPIB.</li>
                    <li><strong>Gambar Anak Perempuan dan Laki-Laki:</strong> Seorang Anak Perempuan dan Laki-Laki berdampingan mengikuti kegiatan dalam wadah Pelkat Pelayanan Anak GPIB.</li>
                </ul>
            </div>
            <div class="mt-4 md:mt-0 md:ml-6">
                <img src="{{ asset('images/logo/pelkat_pa.png') }}" alt="Logo Pelkat PA" class="w-54">
            </div>
        </div>

        <div class="bg-white px-6 py-6 rounded shadow">
            <h2 class="text-xl text-amber-700 font-semibold mb-4">PENGURUS PELKAT PA</h2>
            <ul class="mb-4 space-y-1 text-gray-800">
                <li><strong>Ketua Pelkat PA:</strong> Selvi Soplant</li>
                <li><strong>Sekretaris:</strong> Isaura Divna F. Pardede</li>
                <li><strong>Bendahara:</strong> Novi</li>
                <li><strong>Bidang TEOLOGI & PPSDI – PPK:</strong> .....</li>
                <li><strong>Bidang PELKES & GERMASA:</strong> .....</li>
                <li><strong>Bidang PEG & INFOKOM:</strong> .....</li>
                <li><strong>Pelayan PA:</strong> .....</li>
            </ul>

            <div class="mt-6">
                <img src="{{ asset('images/foto/pengurus_pa.png') }}" alt="Foto Pengurus Pelkat PA" class="rounded shadow-md w-full md:w-2/3 mx-auto">
            </div>
        </div>
        
    </div>
</div>
@endsection
