<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IbadahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'ibadah_id' => 1, 
                'kategoriibadah_id' => 1, // Ibadah Minggu
                'tanggal' => '2025-03-16',
                'waktu' => '09:00:00',
                'tempat' => 'Immanuel',
                'lokasi' => null,
                'sektor' => null,
                'nama_pelkat' => null,
                'ruang' => null,
                'pelayan_firman' => 'Pdt Astrid', // Pdt. Yohanes Samosir
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ibadah_id' => 2, 
                'kategoriibadah_id' => 2, // Ibadah Keluarga
                'tanggal' => '2025-03-18',
                'waktu' => '18:30:00',
                'tempat' => 'Rumah Keluarga Simanjuntak',
                'lokasi' => 'Jalan Merdeka No. 10',
                'sektor' => 3,
                'nama_pelkat' => null,
                'ruang' => null,
                'pelayan_firman' => 'Pdt. Meike', // Vikaris Maria Hutagalung
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ibadah_id' => 3, 
                'kategoriibadah_id' => 3, // Ibadah Pengucapan Syukur
                'tanggal' => '2025-03-22',
                'waktu' => '19:00:00',
                'tempat' => 'Rumah Keluarga Manurung',
                'lokasi' => 'Jalan Diponegoro No. 7',
                'sektor' => 5,
                'nama_pelkat' => null,
                'ruang' => null,
                'pelayan_firman' => 'Dkn. Nina', // Diaken Samuel Tobing
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ibadah_id' => 4,
                'kategoriibadah_id' => 4, // Ibadah Diakonia
                'tanggal' => '2025-03-25',
                'waktu' => '10:00:00',
                'tempat' => 'Panti Asuhan Kasih',
                'lokasi' => 'Jalan Veteran No. 15',
                'sektor' => null,
                'nama_pelkat' => null,
                'ruang' => null,
                'pelayan_firman' => 'Pnt. Yesaya', // Penatua Lina Manurung
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ibadah_id' => 5, 
                'kategoriibadah_id' => 5, // Ibadah Pelkat
                'tanggal' => '2025-03-20',
                'waktu' => '17:00:00',
                'tempat' => 'GPIB Immanuel Malang',
                'lokasi' => null,
                'sektor' => null,
                'nama_pelkat' => 'Pelkat GP',
                'ruang' => 'Ruang Serbaguna',
                'pelayan_firman' => 'Pdt. Gabby', // Bpk. Denny Sitompul
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('t_ibadah')->insert($data);
    }
}
