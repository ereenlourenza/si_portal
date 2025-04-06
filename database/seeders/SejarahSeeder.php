<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SejarahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_sejarah')->insert([
            [
                'judul_subbab' => 'Pendahuluan',
                'isi_konten' => '<p>"Gereja Jago" adalah sebutan khas masyarakat Malang bagi GPIB Jemaat Immanuel Malang. Sebuah gereja bersejarah yang menjadi saksi perjalanan iman Kristen di kota ini.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul_subbab' => 'Berdirinya Gereja Jago',
                'isi_konten' => '<p>Pada tahun 1900-an, bangunan gereja bergaya arsitektur Eropa ini mulai berdiri dan digunakan sebagai tempat peribadahan orang-orang Belanda di Malang.</p><p><img src="/storage/sejarah/gedung_lama.jpg" alt="Gedung Gereja Lama" style="max-width:100%"></p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul_subbab' => 'Gereja Jago Saat Ini',
                'isi_konten' => '<p>Setelah Indonesia merdeka, gereja ini menjadi bagian dari GPIB (Gereja Protestan di Indonesia bagian Barat). Saat ini gereja aktif dalam pelayanan ibadah dan sosial masyarakat.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
