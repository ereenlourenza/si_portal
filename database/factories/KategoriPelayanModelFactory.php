<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriPelayanModel>
 */
class KategoriPelayanModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategoripelayan_kode' => strtoupper($this->faker->unique()->lexify('PEL???')),
            'kategoripelayan_nama' => $this->faker->words(2, true),
        ];
    }
}
