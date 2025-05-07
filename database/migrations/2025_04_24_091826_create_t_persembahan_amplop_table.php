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
        Schema::create('t_persembahan_amplop', function (Blueprint $table) {
            $table->id('persembahan_amplop_id');
            $table->unsignedBigInteger('berita_acara_persembahan_id')->index(); // Foreign Key ke kategori galeri

            $table->string('no_amplop')->nullable();
            $table->string('nama_pengguna_amplop')->nullable();
            $table->decimal('jumlah', 15, 2);

            $table->timestamps();

            $table->foreign('berita_acara_persembahan_id')->references('berita_acara_persembahan_id')->on('t_berita_acara_persembahan')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_persembahan_amplop');
    }
};
