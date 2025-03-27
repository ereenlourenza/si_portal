<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeminjamanRuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'peminjam_nama' => 'Loli',
                'peminjam_telepon' => '08123456789',
                'tanggal' => '2025-03-25',
                'waktu_mulai' => '15:00:00',
                'waktu_selesai' => '18:00:00',
                'ruangan_id' => 1,
                'keperluan' => 'Latihan Band Minggu Sore',
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'peminjam_nama' => 'Gery',
                'peminjam_telepon' => '08123456789',
                'tanggal' => '2025-03-25',
                'waktu_mulai' => '18:00:00',
                'waktu_selesai' => '19:00:00',
                'ruangan_id' => 2,
                'keperluan' => 'Latihan Paduan Suara Sektor 5',
                'status' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'peminjam_nama' => 'Poni',
                'peminjam_telepon' => '08123456789',
                'tanggal' => '2025-03-25',
                'waktu_mulai' => '19:00:00',
                'waktu_selesai' => '21:00:00',
                'ruangan_id' => 1,
                'keperluan' => 'Ibadah Gabungan',
                'status' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'peminjam_nama' => 'Rudi',
                'peminjam_telepon' => '08123456789',
                'tanggal' => '2025-03-25',
                'waktu_mulai' => '16:30:00',
                'waktu_selesai' => '18:00:00',
                'ruangan_id' => 3,
                'keperluan' => 'Ibadah Gabungan',
                'status' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('t_peminjamanruangan')->insert($data);
    }
}
