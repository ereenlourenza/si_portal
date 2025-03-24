<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersembahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'persembahan_nama' => 'PENGUCAPAN SYUKUR',
                'nomor_rekening' => 'BRI 0051-01-000601-30-6',
                'atas_nama' => 'Majelis Jemaat GPIB Immanuel Malang',
                'barcode' => 'kas-jemaat.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'persembahan_nama' => 'DANA KEDUKAAN',
                'nomor_rekening' => 'BRI 0051-01-001861-30-7',
                'atas_nama' => 'Majelis Jemaat GPIB Immanuel Malang',
                'barcode' => 'kedukaan.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'persembahan_nama' => 'H2RG',
                'nomor_rekening' => 'BRI 0051-01-004224-30-0',
                'atas_nama' => 'Majelis Jemaat GPIB Immanuel Malang',
                'barcode' => 'h2rg.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'persembahan_nama' => 'PANITIA RESTORASI',
                'nomor_rekening' => 'BRI 0051-01-001109-30-7',
                'atas_nama' => 'Majelis Jemaat GPIB Immanuel Malang',
                'barcode' => 'renovasi.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('t_persembahan')->insert($data);
    }
}
