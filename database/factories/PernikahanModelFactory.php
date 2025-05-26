<?php

namespace Database\Factories;

use App\Models\PernikahanModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PernikahanModelFactory extends Factory
{
    protected $model = PernikahanModel::class;

    public function definition()
    {
        return [
            'nama_lengkap_pria' => $this->faker->name('male'),
            'tempat_lahir_pria' => $this->faker->city,
            'tanggal_lahir_pria' => $this->faker->date,
            'tempat_sidi_pria' => $this->faker->city,
            'tanggal_sidi_pria' => $this->faker->date,
            'pekerjaan_pria' => $this->faker->jobTitle,
            'alamat_pria' => $this->faker->address,
            'nomor_telepon_pria' => $this->faker->phoneNumber,
            'nama_ayah_pria' => $this->faker->name('male'),
            'nama_ibu_pria' => $this->faker->name('female'),
            'nama_lengkap_wanita' => $this->faker->name('female'),
            'tempat_lahir_wanita' => $this->faker->city,
            'tanggal_lahir_wanita' => $this->faker->date,
            'tempat_sidi_wanita' => $this->faker->city,
            'tanggal_sidi_wanita' => $this->faker->date,
            'pekerjaan_wanita' => $this->faker->jobTitle,
            'alamat_wanita' => $this->faker->address,
            'nomor_telepon_wanita' => $this->faker->phoneNumber,
            'nama_ayah_wanita' => $this->faker->name('male'),
            'nama_ibu_wanita' => $this->faker->name('female'),
            'tanggal_pernikahan' => $this->faker->date,
            'waktu_pernikahan' => $this->faker->time,
            'dilayani' => $this->faker->name,
            'ktp' => 'dummy_ktp.pdf',
            'kk' => 'dummy_kk.pdf',
            'surat_sidi' => 'dummy_surat_sidi.pdf',
            'akta_kelahiran' => 'dummy_akta_kelahiran.pdf',
            'sk_nikah' => 'dummy_sk_nikah.pdf',
            'sk_asalusul' => 'dummy_sk_asalusul.pdf',
            'sp_mempelai' => 'dummy_sp_mempelai.pdf',
            'sk_ortu' => 'dummy_sk_ortu.pdf',
            'akta_perceraian_kematian' => null,
            'si_kawin_komandan' => null,
            'sp_gereja_asal' => null,
            'foto' => 'dummy_foto.jpg',
            'biaya' => $this->faker->numberBetween(500000, 2000000),
            'status' => $this->faker->randomElement([0, 1, 2]), // 0: Menunggu, 1: Disetujui, 2: Ditolak
            'alasan_penolakan' => null,
        ];
    }
}
