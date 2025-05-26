<?php

namespace Database\Factories;

use App\Models\WartaJemaatModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class WartaJemaatModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WartaJemaatModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tanggal' => $this->faker->date(),
            'judul' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph(2),
            'file' => $this->faker->word . '.pdf', // Assuming file is a string like 'filename.pdf'
        ];
    }
}
