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
        Schema::create('t_peminjamanruangan', function (Blueprint $table) {
            $table->id('peminjamanruangan_id');
            $table->string('peminjam_nama',100);
            $table->string('peminjam_telepon',20);
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->unsignedBigInteger('ruangan_id')->index(); // Foreign Key ke kategori galeri
            $table->string('keperluan');
            $table->boolean('status')->default(0);
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            $table->foreign('ruangan_id')->references('ruangan_id')->on('t_ruangan')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_peminjamanruangan');
    }
};
