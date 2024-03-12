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
      $table->dropColumn('tanggal_ubah');
      $table->dropColumn('tanggal_buat');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('cutis', function (Blueprint $table) {
      //
    });
  }
};
