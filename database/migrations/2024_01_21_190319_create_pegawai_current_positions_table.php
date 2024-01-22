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
        Schema::create('pegawai_current_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // from users
            $table->string('kd_akses')->unique();
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->unsigned()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_current_positions');
    }
};
