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
        Schema::table('t_tataibadah', function (Blueprint $table) {
            // Hapus unique index pada kolom judul
            $table->dropUnique(['judul']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tataibadah', function (Blueprint $table) {
            // Tambahkan kembali unique jika perlu rollback
            $table->unique('judul');
        });
    }
};
