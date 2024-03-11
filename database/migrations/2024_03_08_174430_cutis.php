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
    Schema::create('cutis', function (Blueprint $table) {
      $table->id();
      $table->string('kd_akses'); // from users
      $table->string('nama');
      $table->string('kd_jabatan'); // from master_jabatans
      $table->string('alasan_cuti');
      $table->integer('kd_jenis_cuti'); // from jenis_cutis
      $table->integer('lama_cuti');
      $table->date('tanggal_mulai');
      $table->date('tanggal_selesai');
      $table->date('tanggal_buat');
      $table->date('tanggal_ubah')->nullable();
      $table->integer('kd_status_permohonan'); // from master_status_permohonan_cutis
      $table->date('tanggal_approve_atasan_1')->nullable();
      $table->date('tanggal_approve_atasan_2')->nullable();
      $table->string('alasan_pembatalan')->nullable();
      $table->string('alasan_penolakan')->nullable();

      $table->foreign('kd_akses')->references('kd_akses')->on('users')->unsigned()->cascadeOnDelete();
      $table->foreign('kd_jabatan')->references('kd_jabatan')->on('master_jabatans');
      $table->foreign('kd_jenis_cuti')->references('kd_jenis_cuti')->on('jenis_cutis');
      $table->foreign('kd_status_permohonan')->references('kd_status_permohonan')->on('master_status_permohonan_cutis');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cutis');
  }
};
