<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'ruangan_nama' => 'Ruang Ibadah',
                'deskripsi' => 'Fasilitas peralatan musik, multimedia, sound sistem, AC',
                'foto' => 'ruang-ibadah.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ruangan_nama' => 'Ruang Pertemuan Atas',
                'deskripsi' => 'Fasilitas keyboard korg, proyektor, kursi',
                'foto' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ruangan_nama' => 'Ruang Perpustakaan',
                'deskripsi' => 'Fasilitas meja, kursi, kipas angin',
                'foto' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ruangan_nama' => 'Ruang PT',
                'deskripsi' => 'Fasilitas meja, kursi, AC',
                'foto' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ruangan_nama' => 'Konsistori',
                'deskripsi' => 'Fasilitas meja, kursi, AC',
                'foto' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ruangan_nama' => 'Ruang Kolintang',
                'deskripsi' => 'Fasilitas kursi, peralatan kolintang, angklung, kipas angin',
                'foto' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('t_ruangan')->insert($data);
    }
}
