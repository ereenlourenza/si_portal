<?php

namespace Database\Factories;

use App\Models\PelayanModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PHMJModel>
 */
class PHMJModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pelayan_id' => PelayanModel::factory(), // otomatis membuat PelayanModel
            'jabatan' => $this->faker->randomElement(['Ketua Majelis Jemaat', 'sekretaris', 'Bendahara', 'Ketua 1', 'Ketua 2', 'Ketua 3']),
            'periode_mulai' => $this->faker->year($max = 'now'),
            'periode_selesai' => $this->faker->year($max = 'now'),
        ];
    }
}
