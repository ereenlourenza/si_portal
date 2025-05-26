<?php

namespace Database\Factories;

use App\Models\KategoriPelayanModel;
use App\Models\PelkatModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PelayanModel>
 */
class PelayanModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'kategoripelayan_id' => KategoriPelayanModel::factory(), // relasi
            'pelkat_id' => PelkatModel::factory(), // Ensure PelkatModel is always created
            'masa_jabatan_mulai' => $this->faker->numberBetween(2020, 2023),
            'masa_jabatan_selesai' => $this->faker->numberBetween(2024, 2026),
            // 'masa_jabatan_mulai' => $this->faker->numberBetween(2020, 2023),
            // 'masa_jabatan_selesai' => $this->faker->numberBetween(2024, 2026),
            'foto' => null, // default null kecuali diset manual di test
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
