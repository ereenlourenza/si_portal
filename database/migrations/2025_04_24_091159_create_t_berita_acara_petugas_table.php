<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_berita_acara_petugas', function (Blueprint $table) {
            $table->id('berita_acara_petugas_id');
            $table->unsignedBigInteger('berita_acara_ibadah_id')->index(); // Foreign Key ke kategori galeri
            $table->string('peran'); // contoh: pelayan_1, pelayan_2, kolektan, dll
            $table->unsignedBigInteger('pelayan_id_jadwal')->nullable();
            $table->unsignedBigInteger('pelayan_id_hadir')->nullable();
            $table->timestamps();

            $table->foreign('berita_acara_ibadah_id')->references('berita_acara_ibadah_id')->on('t_berita_acara_ibadah')->onDelete('cascade');
            $table->foreign('pelayan_id_jadwal')->references('pelayan_id')->on('t_pelayan')->onDelete('cascade'); // Foreign key untuk pelayan_id_jadwal
            $table->foreign('pelayan_id_hadir')->references('pelayan_id')->on('t_pelayan')->onDelete('cascade'); // Foreign key untuk pelayan_id_hadir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_berita_acara_petugas');
    }
};
