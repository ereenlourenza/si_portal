<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'level_id' => 1,
                'username' => 'majelisjemaat',
                'name' => 'Majelis Jemaat Official',
                'password' => Hash::make('majelisjemaat12345'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'level_id' => 2, 
                'username' => 'phmj',
                'name' => 'PHMJ Official', 
                'password' => Hash::make('phmj12345'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'user_id' => 3,
                'level_id' => 3, 
                'username' => 'admin',
                'name' => 'Admin Official', 
                'password' => Hash::make('admin12345'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'user_id' => 4,
                'level_id' => 4, 
                'username' => 'superadmin',
                'name' => 'Super Admin Official', 
                'password' => Hash::make('superadmin12345'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ];
        DB::table('t_user')->insert($data);
    }
}
