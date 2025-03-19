<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SektorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'sektor_id' => 1,
                'sektor_nama' => 'Sektor 1', 
                'deskripsi' => 'Sebelah Timur, berbatasan dengan GPIB Sejahtera
Sebelah Utara, berbatasan dengan Sektor 2, mulai Jl. Pucang ke Barat sampai pertigaan dengan Jl.S.Supriadi
Sebelah Barat, berbatasan dengan Sektor 3, mulai dari pertigaan Jl.Pucang dengan Jl.S.Supriadi ke Selatan sampai batas Kab.Malang dan Kab.Blitar
Sebelah Selatan, mulai dari Sendangbiru mengikuti pantai Selatan sampai batas Kabupaten Malang dan Kabupaten Blitar',
                'pelayan_id' => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'sektor_id' => 2, 
                'sektor_nama' => 'Sektor 2', 
                'deskripsi' => 'Sebelah Timur, berbatasan dengan GPIB Sejahtera sampai dengan perbatasan Sektor 1
Sebelah Utara, berbatasan dengan Sektor 8 dan Sektor 9
Sebelah Barat, berbatasan dengan Sektor 4 dan Sektor 3
Sebelah Selatan, berbatasan Sektor 1, mulai dari Jl.Pucang ke Timur sampai pertigaan Jl.Kol.Sugiono',
                'pelayan_id' => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'sektor_id' => 3, 
                'sektor_nama' => 'Sektor 3', 
                'deskripsi' => 'Sebelah Timur, berbatasan dengan Sektor 2 dan Sektor 1. Dimulai dari pertigaan Jl.Brigjen Katamso dengan Jl.Arif Margono dan Jl.Brigjen Katamso ke Barat ke Jl.Ikwan Ridwan Rais terus Jl.Jupri terus ke Jl.Bandulan sampai Jedong.
Sebelah Barat, batas Kab.Malang
Sebelah Selatan, sampai batas Kec.Wagir dan Kec.Panjen',
                'pelayan_id' => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'sektor_id' => 4, 
                'sektor_nama' => 'Sektor 4', 
                'deskripsi' => 'Sebelah Timur, berbatasan dengan Sektor 2 dan Sektor 9
Sebelah Utara, berbatasan dengan Sektor 5, dimulai dari Jembatan Jl.Kahuripan ke Barat sampai batas Kabupaten Malang
Sebelah Barat, sampai batas Kabupaten Malang
Sebelah Selatan, berbatasan dengan Sektor 3, mulai dari pertigaan Jl.Arif Margono dan Jl.Brigjen Katamso ke Barat sampai Jedong',
                'pelayan_id' => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'sektor_id' => 5, 
                'sektor_nama' => 'Sektor 5', 
                'deskripsi' => 'Sebelah Timur, berbatasan dengan Sektor 9, mulai dari perempatan Jl.Semeru dan Jl.Basuki Rachmad ke Utara sampai jembatan Jl.Jaksa Agung Suprapto
Sebelah Utara, berbatasan dengan Sektor 7 dan Sektor 6, mulai dari jembatan Jl.Jaksa Agung Suprapto ke Barat sampai perbatasan Kabupaten Malang dan Kota Batu
Sebelah Barat, sampai perbatasan Kabupaten Malang dan Kabupaten Blitar
Sebelah Selatan, berbatasan dengan Sektor 4, mulai dari perempatan Jl.Basuki Rachmad dan Jl.Semeru terus ke sampai batas Kabupaten Malang dan Kabupaten Blitar',
                'pelayan_id' => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'sektor_id' => 6, 
                'sektor_nama' => 'Sektor 6', 
                'deskripsi' => 'Sebelah Timur, berbatasan dengan Sektor 7, mulai ujung Jl.Lembang terus ke Jl.mawar, ke Jl.Bungur sampai perempatan Jl.Kalpataru
Sebelah Utara, berbatasan dengan GPIB Getsemani mulai dari perempatan Jl.Bungur dan Jl.Kalpataru ke Barat sampai S.Brantas.
Sebelah Barat/Selatan, mulai dari pertemuan Jl.Pisang Kipas dan S.Brantas ke Selatan sampai pertemuan antara S.Brantas dengan Jl.Lembang',
                'pelayan_id' => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'sektor_id' => 7, 
                'sektor_nama' => 'Sektor 7', 
                'deskripsi' => 'Sebelah Timur, berbatasan dengan Sektor 9 dan GPIB Getsemani, sampai pertigaan Jl.Ciliwung
Sebelah Utara, berbatasan dengan GPIB Getsemani mulai dari ujung Jl.Ciliwung ke Barat sampai perempatan Jl.Cengger Ayam
Sebelah Barat, berbatasan dengan Sektor 6, mulai dari perempatan Jl.Kalpataru dan Jl.Cengger Ayam ke Selatan sampai S.Brantas
Sebelah Selatan, berbatasan dengan Sektor 5, mjulai pertemuan Jl.Lembang dengan S.Brantas ke Timur sampai jembatan Jl.Jaksa Agung Suprapto',
                'pelayan_id' => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'sektor_id' => 8, 
                'sektor_nama' => 'Sektor 8', 
                'deskripsi' => 'Sebelah Timur, berbatasan dengan GPIB Sejahtera mulai dari pertemuan kali kecil dan S.Brantas ke Utara sampai pertemuan dengan kali kecil
Sebelah Utara, berbatasan dengan GPIB Getsemani mulai dari pertemuan kali kecil dengan Sungai Bangau ke Barat sampai Jl.Letjen Sunandar Priyosudarmo
Sebelah Barat, berbatasan dengan Sektor 9 mulai dari pertemuan kali kecil dengan Jl.Letjen Sunandar Priyosudarmo ke Selatan sampai jembatan S.Brantas
Sebelah Selatan, mulai dari jembatan Brantas mengikuti sungai Brantas sampai pertemuan dengan kali kecil',
                'pelayan_id' => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'sektor_id' => 9, 
                'sektor_nama' => 'Sektor 9', 
                'deskripsi' => 'Sebelah Timur, berbatasan dengan Sektor 8 mulai dari jembatan Brantas ke Jl.P.Sudirman sampai perempatan Jl.Hamid rusdi
Sebelah Utara, berbatasan dengan Sektor 7, mulai dari perempatan Jl.P.Sudirman dan Jl.Hamid Rusdi ke Barat Jl.W.R.Supratman sampai perempatan Jl.Jaksa Agung Suprapto
Sebelah Barat, berbatasan dengan Sektor 7 dan Sektor 6 mulai dari perempatan Jl.Jaksa Agung Suprapto ke Selatan sampai perempatan Jl.Semeru dan Jl.Basuki Rachmad
Sebelah Selatan, berbatasan dengan Sektor 4 dan Sektor 2, mulai dari perempatan Jl.Semeru dan Jl.Basuki Rachmad ke Timur sampai jembatan Brantas',
                'pelayan_id' => 3,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ];
        DB::table('t_sektor')->insert($data);
    }
}
