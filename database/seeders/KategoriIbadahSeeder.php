<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriIbadahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategoriibadah_kode' => 'MNG',
                'kategoriibadah_nama' => 'Ibadah Minggu',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoriibadah_kode' => 'KLG',
                'kategoriibadah_nama' => 'Ibadah Keluarga',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoriibadah_kode' => 'PSY',
                'kategoriibadah_nama' => 'Ibadah Pengucapan Syukur',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoriibadah_kode' => 'DKA',
                'kategoriibadah_nama' => 'Ibadah Diakonia',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoriibadah_kode' => 'PLK',
                'kategoriibadah_nama' => 'Ibadah Pelkat',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('t_kategoriibadah')->insert($data);
    }
}
