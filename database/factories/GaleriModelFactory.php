<?php

namespace Database\Factories;

use App\Models\KategoriGaleriModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GaleriModel>
 */
class GaleriModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategorigaleri_id' => KategoriGaleriModel::factory(),
            'judul' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph,
            'foto' => 'dummy.jpg',
        ];
    }
}
