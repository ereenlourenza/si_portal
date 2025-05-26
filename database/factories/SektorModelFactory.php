<?php

namespace Database\Factories;

use App\Models\SektorModel;
use App\Models\PelayanModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SektorModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SektorModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sektor_nama' => $this->faker->unique()->word . ' ' . $this->faker->randomElement(['Utara', 'Selatan', 'Timur', 'Barat', 'Tengah']),
            'deskripsi' => $this->faker->sentence,
            'jumlah_jemaat' => $this->faker->numberBetween(50, 200),
            'pelayan_id' => PelayanModel::factory(), // Assumes PelayanModelFactory exists and creates a PelayanModel
        ];
    }
}
