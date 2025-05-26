<?php

namespace Database\Factories;

use App\Models\BaptisModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class BaptisModelFactory extends Factory
{
    protected $model = BaptisModel::class;

    public function definition()
    {
        return [
            'nama_lengkap' => $this->faker->name,
            'tempat_lahir' => $this->faker->city,
            'tanggal_lahir' => $this->faker->date,
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'nama_ayah' => $this->faker->name('male'),
            'nama_ibu' => $this->faker->name('female'),
            'tempat_pernikahan' => $this->faker->city,
            'tanggal_pernikahan' => $this->faker->date,
            'tempat_sidi_ayah' => $this->faker->city,
            'tanggal_sidi_ayah' => $this->faker->date,
            'tempat_sidi_ibu' => $this->faker->city,
            'tanggal_sidi_ibu' => $this->faker->date,
            'alamat' => $this->faker->address,
            'nomor_telepon' => $this->faker->phoneNumber,
            'tanggal_baptis' => $this->faker->date,
            'dilayani' => $this->faker->name,
            'surat_nikah_ortu' => 'dummy_surat_nikah.jpg',
            'akta_kelahiran_anak' => 'dummy_akta_kelahiran.jpg',
            'status' => $this->faker->randomElement([0, 1, 2]), // 0: Menunggu, 1: Disetujui, 2: Ditolak
            'alasan_penolakan' => null,
        ];
    }
}

