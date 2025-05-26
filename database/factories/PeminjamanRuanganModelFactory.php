<?php

namespace Database\Factories;

use App\Models\PeminjamanRuanganModel;
use App\Models\RuanganModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class PeminjamanRuanganModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PeminjamanRuanganModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $waktuMulai = $this->faker->time('H:i:00');
        $waktuSelesai = Carbon::parse($waktuMulai)->addHours($this->faker->numberBetween(1, 3))->format('H:i:00');

        return [
            'peminjam_nama' => $this->faker->name(),
            'peminjam_telepon' => $this->faker->phoneNumber(),
            'tanggal' => $this->faker->dateTimeBetween('+0 days', '+1 month')->format('Y-m-d'),
            'waktu_mulai' => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'ruangan_id' => RuanganModel::factory(), // Assumes RuanganModelFactory exists or creates one on the fly
            'keperluan' => $this->faker->sentence(),
            'status' => $this->faker->randomElement([0, 1, 2]), // 0: Menunggu, 1: Disetujui, 2: Ditolak
            'alasan_penolakan' => function (array $attributes) {
                return $attributes['status'] == 2 ? $this->faker->sentence() : null;
            },
        ];
    }
}
