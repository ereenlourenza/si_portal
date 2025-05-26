<?php

namespace Database\Factories;

use App\Models\RuanganModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RuanganModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RuanganModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ruangan_nama' => $this->faker->words(3, true), // Generates a string of 3 random words
            'deskripsi' => $this->faker->sentence,
            'foto' => 'ruangan/' . $this->faker->image('storage/app/public/images/ruangan', 640, 480, 'cats', false), // Generates a fake image and stores its relative path
        ];
    }
}
