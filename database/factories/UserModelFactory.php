<?php

namespace Database\Factories;

use App\Models\LevelModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserModel>
 */
class UserModelFactory extends Factory
{
    protected $model = \App\Models\UserModel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'password' => bcrypt('password123'), // password default uji coba
            'level_id' => LevelModel::factory(), // Sesuaikan dengan ID level di sistemmu
        ];
    }
}
