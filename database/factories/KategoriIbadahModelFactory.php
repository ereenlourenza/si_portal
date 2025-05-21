<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriIbadahModel>
 */
class KategoriIbadahModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategoriibadah_kode' => strtoupper($this->faker->unique()->lexify('IBD???')),
            'kategoriibadah_nama' => $this->faker->words(2, true),
        ];
    }
}
