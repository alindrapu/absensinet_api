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
            $table->integer('jatah_cuti_tahunan')->default(12);
            $table->integer('jatah_cuti_kematian')->default(3);
            $table->integer('jatah_cuti_menikah')->default(3);
            $table->integer('jatah_cuti_melahirkan')->default(3);
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
