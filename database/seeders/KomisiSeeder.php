<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KomisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'komisi_id' => 1,
                'komisi_nama' => 'Komisi Teologi', 
                'deskripsi' => 'Ketua Komisi: 
                Sekretaris Komisi:
                Bendahara Komisi:
                Anggota Komisi:',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'komisi_id' => 2, 
                'komisi_nama' => 'Komisi Pelkes', 
                'deskripsi' => 'Ketua Komisi: 
                Sekretaris Komisi:
                Bendahara Komisi:
                Anggota Komisi:',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'komisi_id' => 3, 
                'komisi_nama' => 'Komisi PEG', 
                'deskripsi' => 'Ketua Komisi: 
                Sekretaris Komisi:
                Bendahara Komisi:
                Anggota Komisi:',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'komisi_id' => 4, 
                'komisi_nama' => 'Komisi Germasa', 
                'deskripsi' => 'Ketua Komisi: 
                Sekretaris Komisi:
                Bendahara Komisi:
                Anggota Komisi:',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'komisi_id' => 5, 
                'komisi_nama' => 'Komisi PPSDI-PPK', 
                'deskripsi' => 'Ketua Komisi: 
                Sekretaris Komisi:
                Bendahara Komisi:
                Anggota Komisi:',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'komisi_id' => 6, 
                'komisi_nama' => 'Komisi Inforkom-Litbang', 
                'deskripsi' => 'Ketua Komisi: 
                Sekretaris Komisi:
                Bendahara Komisi:
                Anggota Komisi:',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ];
        DB::table('t_komisi')->insert($data);
    }
}
