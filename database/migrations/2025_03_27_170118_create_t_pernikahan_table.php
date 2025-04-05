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
        Schema::create('t_pernikahan', function (Blueprint $table) {
            $table->id('pernikahan_id');
            $table->string('nama_lengkap_pria');
            $table->string('tempat_lahir_pria');
            $table->date('tanggal_lahir_pria');
            $table->string('tempat_sidi_pria');
            $table->date('tanggal_sidi_pria');
            $table->string('pekerjaan_pria');
            $table->string('alamat_pria');
            $table->string('nomor_telepon_pria');
            $table->string('nama_ayah_pria');
            $table->string('nama_ibu_pria');
            $table->string('nama_lengkap_wanita');
            $table->string('tempat_lahir_wanita');
            $table->date('tanggal_lahir_wanita');
            $table->string('tempat_sidi_wanita');
            $table->date('tanggal_sidi_wanita');
            $table->string('pekerjaan_wanita');
            $table->string('alamat_wanita');
            $table->string('nomor_telepon_wanita');
            $table->string('nama_ayah_wanita');
            $table->string('nama_ibu_wanita');
            $table->date('tanggal_pernikahan');
            $table->time('waktu_pernikahan');
            $table->string('dilayani');
            $table->string('ktp');
            $table->string('kk');
            $table->string('surat_sidi');
            $table->string('akta_kelahiran');
            $table->string('sk_nikah');
            $table->string('sk_asalusul');
            $table->string('sp_mempelai');
            $table->string('sk_ortu');
            $table->string('akta_perceraian_kematian')->nullable();
            $table->string('si_kawin_komandan')->nullable();
            $table->string('sp_gereja_asal')->nullable();
            $table->string('foto');
            $table->string('biaya');
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
        Schema::dropIfExists('t_pernikahan');
    }
};
