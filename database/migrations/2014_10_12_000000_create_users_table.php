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
    Schema::create('users', function (Blueprint $table) {
      $table->unsignedBigInteger('id')->autoIncrement();
      $table->string('kd_akses')->nullable()->unique();
      $table->string('nama');
      $table->string('email')->unique();
      $table->string('password');
      $table->integer('is_admin');
      $table->integer('added_kd_akses');
      $table->rememberToken();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
