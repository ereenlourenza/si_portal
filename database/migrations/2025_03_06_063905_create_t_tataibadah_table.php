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
        Schema::create('t_tataibadah', function (Blueprint $table) {
            $table->id('tataibadah_id'); // Primary Key
            $table->date('tanggal'); // Tanggal ibadah
            $table->string('judul', 255)->unique(); // Judul tata ibadah
            $table->text('deskripsi')->nullable(); // Deskripsi tata ibadah
            $table->string('file')->nullable(); // Nama file tata ibadah yang diunggah
            $table->timestamps(); // created_at (waktu upload), updated_at (waktu terakhir diupdate)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_tataibadah');
    }
};
