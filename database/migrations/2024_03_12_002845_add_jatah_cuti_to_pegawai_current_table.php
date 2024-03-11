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
        Schema::table('pegawai_currents', function (Blueprint $table) {
            $table->integer('jatah_cuti_tahunan');
            $table->integer('jatah_cuti_kematian');
            $table->integer('jatah_cuti_menikah');
            $table->integer('jatah_cuti_melahirkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai_currents', function (Blueprint $table) {
            //
        });
    }
};
