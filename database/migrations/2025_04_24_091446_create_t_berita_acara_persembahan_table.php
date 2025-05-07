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
        Schema::create('t_berita_acara_persembahan', function (Blueprint $table) {
            $table->id('berita_acara_persembahan_id');
            $table->unsignedBigInteger('berita_acara_ibadah_id')->index(); // Foreign Key ke kategori galeri
            $table->unsignedBigInteger('kategori_persembahan_id')->index(); // Foreign Key ke kategori galeri
            $table->enum('jenis_input', ['langsung','lembaran', 'amplop']); // inputan berdasarkan jenis
            $table->decimal('total', 15, 2)->default(0); // hasil perhitungan dari detailnya
            $table->timestamps();

            $table->foreign('berita_acara_ibadah_id')->references('berita_acara_ibadah_id')->on('t_berita_acara_ibadah')->onDelete('cascade');
            $table->foreign('kategori_persembahan_id')->references('kategori_persembahan_id')->on('t_kategori_persembahan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_berita_acara_persembahan');
    }
};
