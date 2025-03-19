<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PHMJSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_phmj')->insert([
            [
                'pelayan_id' => 3, // Sesuaikan dengan ID di t_pelayan
                'jabatan' => 'Ketua I',
                'periode_mulai' => 2023,
                'periode_selesai' => 2025,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
