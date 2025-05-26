<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'pelkat_id' => 1, // Added explicit ID
                'pelkat_nama' => 'Pelkat PA', 
                'deskripsi' => 'Pelayanan Kategorial Pelayanan Anak (Pelkat PA) adalah bagian dari unit misioner GPIB dengan tugas utamanya untuk membina dan melayani  warga GPIB dalam kategori usia 0 – 12 tahun.

Pelkat PA dibagi menjadi 3 kategori berdasarkan usia:
1.Kelas TK (0-5 tahun/Playgroup-TK)
2.Kelas Kecil (1-3 SD), dan
3.Kelas Tanggung (4-6 SD)

IHM PA dilaksanakan di 2 tempat yaitu:
1.GPIB Immanuel Malang Pk. 08.00 di Ruang PA
2.Pos Pakisaji Pk. 09.00 di Ruang PA',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pelkat_id' => 2, // Added explicit ID
                'pelkat_nama' => 'Pelkat PT', 
                'deskripsi' => 'Pelayanan Kategorial Persekutuan Teruna (Pelkat PT) adalah bagian dari unit misioner GPIB dengan tugas utamanya untuk membina dan melayani warga GPIB dalam kategori usia 13-17 tahun.

Kelompok jemaat pada usia ini adalah para remaja yang sedang memasuki “kedewasaan” baik secara biologis maupun psikologis dengan dinamika yang amat sangat unik, kreatif, dan perlu tingkat konsentrasi dan kewaspadaan yang tinggi.

IHM PT dilaksanakan di:
1.GPIB Immanuel Malang Pk. 08.00 di Ruang Pertemuan Atas',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pelkat_id' => 3, // Added explicit ID
                'pelkat_nama' => 'Pelkat GP', 
                'deskripsi' => 'Pelayanan Kategorial Gerakan Pemuda (Pelkat GP) adalah bagian dari unit misioner GPIB dengan tugas utamanya untuk membina dan melayani  warga GPIB dalam kategori usia 17 – 35 tahun.

Ibadah GP dilaksanakan di:
GPIB Immanuel Malang Setiap Hari Jumat Pk. 18.30 di Ruang Pertemuan Atas',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pelkat_id' => 4, // Added explicit ID
                'pelkat_nama' => 'Pelkat PKP', 
                'deskripsi' => 'Pelayanan Kategorial Persekutuan Kaum Perempuan (Pelkat PKP) adalah bagian dari unit misioner GPIB dengan tugas utamanya untuk membina dan melayani  warga GPIB dalam kategori usia 36 – 59 tahun atau sebelum usia 36 tahun namun sudah menikah.

Ibadah Gabungan PKP dilaksanakan di:
1.GPIB Immanuel Malang Hari Selasa Minggu Ke-2 Pk. 17.00 di Ruang Ibadah Gereja',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pelkat_id' => 5, // Added explicit ID
                'pelkat_nama' => 'Pelkat PKB', 
                'deskripsi' => 'Pelayanan Kategorial Persekutuan Kaum Bapak (Pelkat PKB) adalah bagian dari unit misioner GPIB dengan tugas utamanya untuk membina dan melayani  warga GPIB dalam kategori usia 35 – 59 tahun atau sebelum usia 35 tahun namun sudah menikah.

Ibadah Gabungan PKB dilaksanakan di:
1.GPIB Immanuel Malang Hari Sabtu Minggu Ke-Menyesuaikan Pk. 17.00 di Ruang Menyesuaikan',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'pelkat_id' => 6, // Added explicit ID
                'pelkat_nama' => 'Pelkat PKLU', 
                'deskripsi' => 'Pelayanan Kategorial Persekutuan Kaum Lanjut Usia (Pelkat PKLU) adalah bagian dari unit misioner GPIB dengan tugas utamanya untuk membina dan melayani  warga GPIB dalam kategori usia 60 tahun keatas.

Ibadah Gabungan PKB dilaksanakan di:
1.GPIB Immanuel Malang Hari Sabtu Minggu Ke-2 Pk. 10.00 di Ruang Ibadah Gereja',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ];
        DB::table('t_pelkat')->insert($data);
    }
}
