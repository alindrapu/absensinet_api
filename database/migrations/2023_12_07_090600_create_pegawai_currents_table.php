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
        Schema::create('pegawai_currents', function (Blueprint $table) {
            $table->id();
            $table->int('user_id')->unique(); // from users
            $table->string('kd_akses')->nullable()->unique(); // from users
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('nik');
            $table->string('telp');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->integer('is_admin'); // from users
            $table->integer('kd_agama'); // from master_agamas
            $table->integer('kd_jabatan'); // from master_jabatans
            $table->integer('sts_kepeg');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('kd_akses')->references('kd_akses')->on('users');
            $table->foreign('kd_agama')->references('kd_agama')->on('master_agamas');
            $table->foreign('kd_jabatan')->references('kd_jabatan')->on('master_jabatans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_currents');
    }
};
