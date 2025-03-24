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
        Schema::create('t_persembahan', function (Blueprint $table) {
            $table->id('persembahan_id');
            $table->string('persembahan_nama')->unique();
            $table->string('nomor_rekening',30);
            $table->string('atas_nama',100);
            $table->string('barcode');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_persembahan');
    }
};
