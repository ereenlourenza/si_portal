<?php

namespace Database\Factories;

use App\Models\PersembahanModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersembahanModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersembahanModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'persembahan_nama' => $this->faker->words(2, true), // e.g., "Persembahan Syukur"
            'nomor_rekening' => $this->faker->numerify('##########'), // 10-digit number string
            'atas_nama' => $this->faker->name,
            'barcode' => 'dummy.jpg'
        ];
    }
}
