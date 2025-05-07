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
        Schema::create('t_kategori_persembahan', function (Blueprint $table) {
            $table->id('kategori_persembahan_id');
            $table->string('kategori_persembahan_nama'); // contoh: Kolekte, Persembahan Syukur, dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kategori_persembahan');
    }
};
