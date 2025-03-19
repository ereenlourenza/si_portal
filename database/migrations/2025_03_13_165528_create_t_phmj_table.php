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
        Schema::create('t_phmj', function (Blueprint $table) {
            $table->id('phmj_id'); // Primary Key
            $table->unsignedBigInteger('pelayan_id')->index(); // Foreign Key ke t_pelayan
            $table->string('jabatan'); // Ketua, Sekretaris, dll.
            $table->year('periode_mulai'); // Kapan mulai menjabat
            $table->year('periode_selesai')->nullable(); // Kapan selesai (bisa null jika masih aktif)
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
        Schema::dropIfExists('t_phmj');
    }
};
