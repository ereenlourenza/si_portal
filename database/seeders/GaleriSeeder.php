<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategorigaleri_id' => 1,
                'judul' => 'LENTERA',
                'deskripsi' => 'Perayaan Natal 2022',
                'foto' => 'GPIB NATALAN-76.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategorigaleri_id' => 2,
                'judul' => 'Ibadah Tutup Tahun 2022',
                'deskripsi' => 'Gerakan Pemuda',
                'foto' => 'Ibadah Tutup Tahun.jpeg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('t_galeri')->insert($data);
    }
}
