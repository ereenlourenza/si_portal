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
            'pelkat_id' => PelkatModel::factory(), // relasi
            'masa_jabatan_mulai' => 2022,
            'masa_jabatan_selesai' => 2024,
            'foto' => null, // default null kecuali diset manual di test
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
