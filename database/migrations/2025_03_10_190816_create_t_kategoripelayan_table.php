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
        Schema::create('t_kategoripelayan', function (Blueprint $table) {
            $table->id('kategoripelayan_id');
            $table->string('kategoripelayan_kode',10)->unique(); // Nama kategori pelayan
            $table->string('kategoripelayan_nama',100); // Nama kategori pelayan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_kategoripelayan');
    }
};
