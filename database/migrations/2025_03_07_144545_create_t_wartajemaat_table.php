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
        Schema::create('t_wartajemaat', function (Blueprint $table) {
            $table->id('wartajemaat_id'); // Primary Key
            $table->date('tanggal'); // Tanggal ibadah
            $table->string('judul', 255)->unique(); // Judul warta jemaat
            $table->text('deskripsi')->nullable(); // Deskripsi warta jemaat
            $table->string('file')->nullable(); // Nama file warta jemaat yang diunggah
            $table->timestamps(); // created_at (waktu upload), updated_at (waktu terakhir diupdate)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_wartajemaat');
    }
};
