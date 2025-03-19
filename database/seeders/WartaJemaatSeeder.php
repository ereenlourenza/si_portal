<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WartaJemaatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'wartajemaat_id' => 1,
                'tanggal' => '2025-03-09', // Format DD-MM-YYYY
                'judul' => 'Warta Jemaat 09 Maret 2025', 
                'deskripsi' => 'Warta Jemaat Khusus',
                'file' => 'warta_immanuel_2-2-25.pdf',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'wartajemaat_id' => 2,
                'tanggal' => '2025-03-09', // Format DD-MM-YYYY
                'judul' => 'Warta Jemaat 16 Maret 2025', 
                'deskripsi' => 'Warta Jemaat Regular',
                'file' => '',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ];
        DB::table('t_wartajemaat')->insert($data);
    }
}
