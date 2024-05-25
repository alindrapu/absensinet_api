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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); //be
            $table->string('kd_akses'); //fe
            $table->string('latitude_masuk'); //fe
            $table->string('longitude_masuk'); //fe
            $table->string('latitude_keluar')->nullable(); //fe
            $table->string('longitude_keluar')->nullable(); //fe
            $table->date('tanggal_presensi'); //be
            $table->string('jam_masuk'); //be
            $table->string('jam_keluar')->nullable(); //be
            $table->string('status_lokasi_masuk'); //fe
            $table->string('status_lokasi_keluar')->nullable(); //fe
            $table->integer('kd_jenis_presensi'); //fe
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->unsigned()->cascadeOnDelete();
            $table->foreign('kd_jenis_presensi')->references('kd_jenis_presensi')->on('jenis_presensis')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
