<?php

namespace Database\Factories;

use App\Models\SejarahModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SejarahModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SejarahModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'judul_subbab' => $this->faker->sentence(3),
            'isi_konten' => '<p>' . $this->faker->paragraphs(3, true) . '</p>', // Generates HTML content
        ];
    }
}
