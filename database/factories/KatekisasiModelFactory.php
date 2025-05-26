<?php

namespace Database\Factories;

use App\Models\KatekisasiModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class KatekisasiModelFactory extends Factory
{
    protected $model = KatekisasiModel::class;

    public function definition()
    {
        return [
            'nama_lengkap' => $this->faker->name,
            'tempat_lahir' => $this->faker->city,
            'tanggal_lahir' => $this->faker->date,
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'alamat_katekumen' => $this->faker->address,
            'nomor_telepon_katekumen' => $this->faker->phoneNumber,
            'pendidikan_terakhir' => $this->faker->randomElement(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3']),
            'pekerjaan' => $this->faker->jobTitle,
            'is_baptis' => $this->faker->boolean,
            'tempat_baptis' => $this->faker->city,
            'no_surat_baptis' => $this->faker->uuid,
            'tanggal_surat_baptis' => $this->faker->date,
            'dilayani' => $this->faker->name,
            'nama_ayah' => $this->faker->name('male'),
            'nama_ibu' => $this->faker->name('female'),
            'alamat_ortu' => $this->faker->address,
            'nomor_telepon_ortu' => $this->faker->phoneNumber,
            'akta_kelahiran' => 'dummy_akta_kelahiran.pdf',
            'surat_baptis' => 'dummy_surat_baptis.pdf',
            'pas_foto' => 'dummy_pas_foto.jpg',
            'status' => $this->faker->randomElement([0, 1, 2]), // 0: Menunggu, 1: Disetujui, 2: Ditolak
            'alasan_penolakan' => null,
        ];
    }
}
