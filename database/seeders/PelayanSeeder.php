<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelayanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'pelayan_id' => 1, 
                'kategoripelayan_id' => 1, // Pendeta
                'pelkat_id' => null,
                'nama' => 'Pdt. Yohanes Samosir',
                'foto' => 'avatar-8.jpg',
                'masa_jabatan_mulai' => 2020,
                'masa_jabatan_selesai' => 2024,
                'keterangan' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pelayan_id' => 2, 
                'kategoripelayan_id' => 2, // Vikaris
                'pelkat_id' => null,
                'nama' => 'Vikaris Maria Hutagalung',
                'foto' => 'avatar-5.jpg',
                'masa_jabatan_mulai' => 2024,
                'masa_jabatan_selesai' => 2025,
                'keterangan' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pelayan_id' => 3, 
                'kategoripelayan_id' => 3, // Diaken
                'pelkat_id' => null,
                'nama' => 'Diaken Samuel Tobing',
                'foto' => 'avatar-6.jpg',
                'masa_jabatan_mulai' => 2020,
                'masa_jabatan_selesai' => 2025,
                'keterangan' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pelayan_id' => 4, 
                'kategoripelayan_id' => 4, // Penatua
                'pelkat_id' => null,
                'nama' => 'Penatua Lina Manurung',
                'foto' => 'avatar-4.jpg',
                'masa_jabatan_mulai' => 2020,
                'masa_jabatan_selesai' => 2025,
                'keterangan' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pelayan_id' => 6, 
                'kategoripelayan_id' => 6, // Pengurus Pelkat
                'pelkat_id' => 1,
                'nama' => 'Sdr. Denny Sitompul',
                'foto' => 'avatar-7.jpg',
                'masa_jabatan_mulai' => 2023,
                'masa_jabatan_selesai' => 2025,
                'keterangan' => 'Ketua',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pelayan_id' => 7, 
                'kategoripelayan_id' => 7, // Pelayan Pelkat PT
                'pelkat_id' => 2,
                'nama' => 'Sdr. Rina Siregar',
                'foto' => 'avatar-9.jpg',
                'masa_jabatan_mulai' => 2023,
                'masa_jabatan_selesai' => 2025,
                'keterangan' => 'Sekretaris',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('t_pelayan')->insert($data);
    }
}
