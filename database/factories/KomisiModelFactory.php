<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KomisiModel>
 */
class KomisiModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'komisi_nama' => $this->faker->unique()->words(2, true),
            'deskripsi' => '<p>' . $this->faker->paragraph(3) . '</p>',
        ];
    }
}
