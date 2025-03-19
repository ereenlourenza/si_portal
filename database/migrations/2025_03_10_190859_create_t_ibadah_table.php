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
        Schema::create('t_ibadah', function (Blueprint $table) {
            $table->id('ibadah_id');
            $table->unsignedBigInteger('kategoriibadah_id')->index(); // Foreign Key ke kategori ibadah
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('tempat', 100);
            $table->string('lokasi')->nullable(); // Untuk ibadah keluarga, pengucapan syukur, diakonia
            $table->integer('sektor')->nullable(); // Untuk ibadah keluarga (1-9)
            $table->string('nama_pelkat')->nullable(); // Untuk ibadah pelkat
            $table->string('ruang')->nullable(); // Untuk ibadah pelkat
            $table->string('pelayan_firman'); // Untuk ibadah pelkat
            $table->timestamps();

            // Relasi ke kategori ibadah
            $table->foreign('kategoriibadah_id')->references('kategoriibadah_id')->on('t_kategoriibadah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_ibadah');
    }
};
