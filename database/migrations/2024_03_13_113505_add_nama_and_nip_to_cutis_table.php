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
        Schema::table('cutis', function (Blueprint $table) {
            $table->string('nama_atasan_1')->nullable()->after('tanggal_approve_atasan_1');
            $table->integer('kd_akses_atasan_1')->nullable()->after('nama_atasan_1');
            $table->string('nama_atasan_2')->nullable()->after('kd_akses_atasan_1');
            $table->integer('kd_akses_atasan_2')->nullable()->after('nama_atasan_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cutis', function (Blueprint $table) {
          $table->dropIfExists('nama_atasan_1');
          $table->dropIfExists('kd_akses_atasan_1');
          $table->dropIfExists('nama_atasan_2');
          $table->dropIfExists('kd_akses_atasan_2');
        });
    }
};
