<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'level_id' => 1,
                'level_kode' => 'MLJ', 
                'level_nama' => 'Majelis Jemaat',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'level_id' => 2, 
                'level_kode' => 'PHM', 
                'level_nama' => 'PHMJ',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'level_id' => 3, 
                'level_kode' => 'ADM', 
                'level_nama' => 'Admin',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'level_id' => 4, 
                'level_kode' => 'SAD', 
                'level_nama' => 'Super Admin',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ];
        DB::table('t_level')->insert($data);
    }
}
