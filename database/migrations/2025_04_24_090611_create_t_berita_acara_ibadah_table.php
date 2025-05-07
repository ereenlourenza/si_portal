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
        Schema::create('t_berita_acara_ibadah', function (Blueprint $table) {
            $table->id('berita_acara_ibadah_id');
            $table->unsignedBigInteger('ibadah_id')->index()->unique();            
            $table->string('bacaan_alkitab');
            $table->integer('jumlah_kehadiran');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('ttd_pelayan_1_id');
            $table->unsignedBigInteger('ttd_pelayan_4_id');
            $table->string('ttd_pelayan_1_img')->nullable();
            $table->string('ttd_pelayan_4_img')->nullable();
            $table->timestamps();

            $table->foreign('ibadah_id')->references('ibadah_id')->on('t_ibadah');
            // relasi ke t_pelayan
            $table->foreign('ttd_pelayan_1_id')->references('pelayan_id')->on('t_pelayan');
            $table->foreign('ttd_pelayan_4_id')->references('pelayan_id')->on('t_pelayan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_berita_acara_ibadah');
    }
};
