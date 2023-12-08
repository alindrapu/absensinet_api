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
            $table->unsignedBigInteger('user_id')->unique(); // from users
            $table->string('kd_akses')->nullable()->unique(); // from users
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('nik');
            $table->string('telp');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->text('alamat');
            $table->integer('is_admin');
            $table->integer('kd_agama'); // from master_agamas
            $table->string('kd_jabatan'); // from master_jabatans
            $table->integer('sts_kepeg');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->unsigned()->cascadeOnDelete();
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
