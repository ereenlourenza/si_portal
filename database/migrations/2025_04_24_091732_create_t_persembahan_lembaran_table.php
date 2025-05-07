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
        Schema::create('t_persembahan_lembaran', function (Blueprint $table) {
            $table->id('persembahan_lembaran_id');
            $table->unsignedBigInteger('berita_acara_ibadah_id')->index(); // Foreign Key ke kategori galeri
            $table->unsignedBigInteger('kategori_persembahan_id')->index(); // Foreign Key ke kategori galeri
            $table->unsignedInteger('jumlah_100')->default(0);
            $table->unsignedInteger('jumlah_200')->default(0);
            $table->unsignedInteger('jumlah_500')->default(0);
            $table->unsignedInteger('jumlah_1000')->default(0);
            $table->unsignedInteger('jumlah_2000')->default(0);
            $table->unsignedInteger('jumlah_5000')->default(0);
            $table->unsignedInteger('jumlah_10000')->default(0);
            $table->unsignedInteger('jumlah_20000')->default(0);
            $table->unsignedInteger('jumlah_50000')->default(0);
            $table->unsignedInteger('jumlah_100000')->default(0);
            $table->decimal('total_persembahan', 15, 2); // dari frontend
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
        Schema::dropIfExists('t_persembahan_lembaran');
    }
};
