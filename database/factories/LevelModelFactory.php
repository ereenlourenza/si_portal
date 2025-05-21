<?php

namespace Database\Factories;

use App\Models\LevelModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LevelModel>
 */
class LevelModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = LevelModel::class;

    public function definition(): array
    {
        return [
            'level_kode' => strtoupper($this->faker->unique()->lexify('L??')),
            'level_nama' => $this->faker->jobTitle,
        ];
    }
}
