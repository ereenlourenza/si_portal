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
        Schema::create('t_sektor', function (Blueprint $table) {
            $table->id('sektor_id');
            $table->string('sektor_nama',50)->unique(); 
            $table->text('deskripsi')->nullable(); 
            $table->unsignedBigInteger('pelayan_id')->index(); 
            $table->timestamps();

            // Foreign key ke tabel t_pelayan
            $table->foreign('pelayan_id')->references('pelayan_id')->on('t_pelayan')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_sektor');
    }
};
