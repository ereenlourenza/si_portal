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
        Schema::create('t_galeri', function (Blueprint $table) {
            $table->id('galeri_id');
            $table->unsignedBigInteger('kategorigaleri_id')->index(); // Foreign Key ke kategori galeri
            $table->string('judul',100)->unique(); // Nama kategori galeri
            $table->string('deskripsi')->nullable(); // Nama kategori galeri
            $table->string('foto'); // Nama kategori galeri
            $table->timestamps();

            // Relasi ke kategori galeri
            $table->foreign('kategorigaleri_id')->references('kategorigaleri_id')->on('t_kategorigaleri')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_galeri');
    }
};
