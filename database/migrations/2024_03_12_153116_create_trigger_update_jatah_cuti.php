<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    DB::unprepared("CREATE TRIGGER update_jatah_cuti_trigger AFTER UPDATE ON cutis
    FOR EACH ROW
    BEGIN
        IF NEW.kd_status_permohonan = 1 THEN
            IF NEW.kd_jenis_cuti = 1 THEN
              UPDATE pegawai_currents a SET a.jatah_cuti_tahunan = a.jatah_cuti_tahunan - NEW.lama_cuti
              WHERE a.kd_akses = NEW.kd_akses;
            ELSEIF NEW.kd_jenis_cuti = 4 THEN
              UPDATE pegawai_currents a SET a.jatah_cuti_melahirkan = a.jatah_cuti_melahirkan - NEW.lama_cuti
              WHERE a.kd_akses = NEW.kd_akses;
            ELSEIF NEW.kd_jenis_cuti = 5 THEN
              UPDATE pegawai_currents a SET a.jatah_cuti_menikah = a.jatah_cuti_menikah - NEW.lama_cuti
              WHERE a.kd_akses = NEW.kd_akses;
            ELSEIF NEW.kd_jenis_cuti = 6 THEN
              UPDATE pegawai_currents a SET a.jatah_cuti_kematian = a.jatah_cuti_kematian - NEW.lama_cuti
              WHERE a.kd_akses = NEW.kd_akses;
            END IF;
        END IF;
    END;");
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    DB::unprepared('DROP TRIGGER IF EXISTS update_jatah_cuti_trigger');
  }
};
