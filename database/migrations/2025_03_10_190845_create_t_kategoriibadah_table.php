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
        Schema::create('t_kategoriibadah', function (Blueprint $table) {
            $table->id('kategoriibadah_id');
            $table->string('kategoriibadah_kode',10)->unique(); // Nama kategori ibadah
            $table->string('kategoriibadah_nama',100); // Nama kategori ibadah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kategoriibadah');
    }
};
