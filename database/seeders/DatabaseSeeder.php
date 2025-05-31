<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\TataIbadahModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            LevelSeeder::class,
            UserSeeder::class,
            KategoriPersembahanSeeder::class,
            PersembahanSeeder::class,
            KategoriIbadahSeeder::class,
            IbadahSeeder::class,
            KategoriGaleriSeeder::class,
            GaleriSeeder::class,
            PelkatSeeder::class,
            KategoriPelayanSeeder::class,
            PelayanSeeder::class,
            RuanganSeeder::class,
            TataIbadahSeeder::class,
            WartaJemaatSeeder::class,
            SejarahSeeder::class,
            SektorSeeder::class,
            KomisiSeeder::class,
            PeminjamanRuanganSeeder::class,
            PHMJSeeder::class,
        ]);
    }
}
