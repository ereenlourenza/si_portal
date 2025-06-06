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
        Schema::create('t_sejarah', function (Blueprint $table) {
            $table->id('sejarah_id');
            $table->string('judul_subbab');
            $table->longText('isi_konten'); // Bisa HTML
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_sejarah_gereja');
    }
};
