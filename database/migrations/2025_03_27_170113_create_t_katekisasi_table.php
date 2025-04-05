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
        Schema::create('t_katekisasi', function (Blueprint $table) {
            $table->id('katekisasi_id');
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('alamat_katekumen');
            $table->string('nomor_telepon_katekumen');
            $table->string('pendidikan_terakhir');
            $table->string('pekerjaan');
            $table->string('is_baptis');
            $table->string('tempat_baptis')->nullable();
            $table->string('no_surat_baptis')->nullable();
            $table->date('tanggal_surat_baptis')->nullable();
            $table->string('dilayani')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('alamat_ortu')->nullable();
            $table->string('nomor_telepon_ortu')->nullable();
            $table->string('akta_kelahiran');
            $table->string('surat_baptis');
            $table->string('pas_foto');
            $table->boolean('status')->default(0);
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_katekisasi');
    }
};
