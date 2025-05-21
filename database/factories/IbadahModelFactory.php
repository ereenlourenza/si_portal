<?php

namespace Database\Factories;

use App\Models\KategoriIbadahModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IbadahModel>
 */
class IbadahModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategoriibadah_id' => KategoriIbadahModel::factory(), // relasi FK
            'tanggal' => $this->faker->date(),
            'waktu' => $this->faker->time('H:i'),
            'tempat' => $this->faker->randomElement(['Gereja Immanuel', 'Gereja Ebed', 'Rumah']),
            'lokasi' => $this->faker->address(),
            'sektor' => $this->faker->numberBetween(1, 10),
            'nama_pelkat' => $this->faker->randomElement(['PA', 'PT', 'GP', 'PKP', 'PKB', 'PKLU']),
            'ruang' => $this->faker->randomElement(['Ruang Ibadah', 'Ruang A', 'Ruang B']),
            'pelayan_firman' => $this->faker->name()
        ];
    }
}
