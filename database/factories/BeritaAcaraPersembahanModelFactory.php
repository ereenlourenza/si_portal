<?php

namespace Database\Factories;

use App\Models\BeritaAcaraPersembahanModel;
use App\Models\KategoriPersembahanModel;
use App\Models\BeritaAcaraIbadahModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeritaAcaraPersembahanModelFactory extends Factory
{
    protected $model = BeritaAcaraPersembahanModel::class;

    public function definition()
    {
        return [
            'berita_acara_ibadah_id' => BeritaAcaraIbadahModel::factory(), // Added berita_acara_ibadah_id
            'kategori_persembahan_id' => KategoriPersembahanModel::factory(),
            'jenis_input' => $this->faker->randomElement(['amplop', 'lembaran', 'langsung']), // Added jenis_input
            'total' => $this->faker->numberBetween(50000, 2000000),
            // tambahkan field lain jika ada
        ];
    }
}
