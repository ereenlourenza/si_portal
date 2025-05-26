<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriPelayanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategoripelayan_id' => 1,
                'kategoripelayan_kode' => 'PDT',
                'kategoripelayan_nama' => 'Pendeta',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 2,
                'kategoripelayan_kode' => 'VKR',
                'kategoripelayan_nama' => 'Vikaris',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 3,
                'kategoripelayan_kode' => 'DKN',
                'kategoripelayan_nama' => 'Diaken',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 4,
                'kategoripelayan_kode' => 'PNT',
                'kategoripelayan_nama' => 'Penatua',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 5,
                'kategoripelayan_kode' => 'PHMJ',
                'kategoripelayan_nama' => 'PHMJ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 6,
                'kategoripelayan_kode' => 'PG_PA',
                'kategoripelayan_nama' => 'Pengurus Pelkat PA',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 7,
                'kategoripelayan_kode' => 'PG_PT',
                'kategoripelayan_nama' => 'Pengurus Pelkat PT',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 8,
                'kategoripelayan_kode' => 'PG_GP',
                'kategoripelayan_nama' => 'Pengurus Pelkat GP',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 9,
                'kategoripelayan_kode' => 'PG_PKP',
                'kategoripelayan_nama' => 'Pengurus Pelkat PKP',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 10,
                'kategoripelayan_kode' => 'PG_PKB',
                'kategoripelayan_nama' => 'Pengurus Pelkat PKB',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 11,
                'kategoripelayan_kode' => 'PG_PKLU',
                'kategoripelayan_nama' => 'Pengurus Pelkat PKLU',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 12,
                'kategoripelayan_kode' => 'PL_PA',
                'kategoripelayan_nama' => 'Pelayan Pelkat PA',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 13,
                'kategoripelayan_kode' => 'PL_PT',
                'kategoripelayan_nama' => 'Pelayan Pelkat PT',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategoripelayan_id' => 14,
                'kategoripelayan_kode' => 'DLL',
                'kategoripelayan_nama' => 'Lain-lain',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('t_kategoripelayan')->insert($data);
    }
}
