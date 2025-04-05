<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaptisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_lengkap' => 'Gabriel Sofyan Dandelion', 
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '2025-03-16',
                'jenis_kelamin' => 'L',
                'nama_ayah' => 'Ricky',
                'nama_ibu' => 'Boni',
                'tempat_pernikahan' => 'Semarang',
                'tanggal_pernikahan' => '2024-02-12',
                'tempat_sidi_ayah' => 'Jakarta',
                'tanggal_sidi_ayah' => '2019-01-10',
                'tempat_sidi_ibu' => 'Bandung',
                'tanggal_sidi_ibu' => '2018-07-11',
                'alamat' => 'Jl. Persik',
                'nomor_telepon' => '08123456789',
                'tanggal_baptis' => '2025-03-16',
                'dilayani' => 'Pdt. Gabby D. Titiahy Manuputty',
                'surat_nikah_ortu' => 'kas-jemaat.png',
                'akta_kelahiran_anak' => 'kedukaan.png',
                'status' => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ];
        DB::table('t_baptis')->insert($data);
    }
}
