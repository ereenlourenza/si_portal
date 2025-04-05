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
        Schema::create('t_baptis', function (Blueprint $table) {
            $table->id('baptis_id');
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('tempat_pernikahan');
            $table->date('tanggal_pernikahan');
            $table->string('tempat_sidi_ayah');
            $table->date('tanggal_sidi_ayah');
            $table->string('tempat_sidi_ibu');
            $table->date('tanggal_sidi_ibu');
            $table->string('alamat');
            $table->string('nomor_telepon');
            $table->date('tanggal_baptis');
            $table->string('dilayani');
            $table->string('surat_nikah_ortu');
            $table->string('akta_kelahiran_anak');
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
        Schema::dropIfExists('t_baptis');
    }
};
