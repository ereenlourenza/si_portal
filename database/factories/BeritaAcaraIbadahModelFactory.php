<?php

namespace Database\Factories;

use App\Models\BeritaAcaraIbadahModel;
use App\Models\IbadahModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeritaAcaraIbadahModelFactory extends Factory
{
    protected $model = BeritaAcaraIbadahModel::class;

    public function definition()
    {
        return [
            'ibadah_id' => IbadahModel::factory(),
            'jumlah_kehadiran' => $this->faker->numberBetween(20, 300),
            'bacaan_alkitab' => $this->faker->sentence,
            'catatan' => $this->faker->paragraph,
            'ttd_pelayan_1_id' => \App\Models\PelayanModel::factory(),
            'ttd_pelayan_4_id' => \App\Models\PelayanModel::factory(), // Added ttd_pelayan_4_id
            'ttd_pelayan_1_img' => $this->faker->filePath(), // Added ttd_pelayan_1_img
            'ttd_pelayan_4_img' => $this->faker->filePath(), // Added ttd_pelayan_4_img
            // tambahkan field lain jika ada
        ];
    }
}
