<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('t_berita_acara_ibadah', function (Blueprint $table) {
            $table->longText('ttd_pelayan_1_img')->nullable()->change();
            $table->longText('ttd_pelayan_4_img')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('t_berita_acara_ibadah', function (Blueprint $table) {
            $table->string('ttd_pelayan_1_img')->nullable()->change();
            $table->string('ttd_pelayan_4_img')->nullable()->change();
        });
    }
};
