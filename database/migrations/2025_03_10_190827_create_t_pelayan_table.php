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
        Schema::create('t_pelayan', function (Blueprint $table) {
            $table->id('pelayan_id');
            $table->unsignedBigInteger('kategoripelayan_id')->index(); // Foreign Key ke kategori pelayan
            $table->string('nama')->unique();
            $table->string('foto')->nullable(); // Nama file foto
            $table->year('masa_jabatan_mulai'); // Tahun mulai jabatan
            $table->year('masa_jabatan_selesai'); // Tahun selesai jabatan (opsional)
            $table->string('keterangan')->nullable(); 
            $table->timestamps();
            
            // Relasi ke kategori pelayan
            $table->foreign('kategoripelayan_id')->references('kategoripelayan_id')->on('t_kategoripelayan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pelayan');
    }
};
