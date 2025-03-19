<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TataIbadahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'tataibadah_id' => 1,
                'tanggal' => '2025-03-09', // Format DD-MM-YYYY
                'judul' => 'Tata Ibadah 09 Maret 2025', 
                'deskripsi' => 'Tata Ibadah Khusus',
                'file' => 'TAIB  13 Okt  2024  ke-21 Sesudah Pentakosta.pdf',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'tataibadah_id' => 2,
                'tanggal' => '2025-03-09', // Format DD-MM-YYYY
                'judul' => 'Tata Ibadah 16 Maret 2025', 
                'deskripsi' => 'Tata Ibadah Regular',
                'file' => '',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ];
        DB::table('t_tataibadah')->insert($data);
    }
}
