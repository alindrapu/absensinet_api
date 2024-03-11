<?php

namespace Database\Seeders;

use App\Models\JenisCuti as ModelJenisCuti;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisCuti extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    ModelJenisCuti::create([
      "kd_jenis_cuti" => 1,
      "nm_jenis_cuti" => "Cuti Tahunan"
    ]);

    ModelJenisCuti::create([
      "kd_jenis_cuti" => 2,
      "nm_jenis_cuti" => "Cuti Tidak Dibayar"
    ]);

    ModelJenisCuti::create([
      "kd_jenis_cuti" => 3,
      "nm_jenis_cuti" => "Cuti Sakit"
    ]);

    ModelJenisCuti::create([
      "kd_jenis_cuti" => 4,
      "nm_jenis_cuti" => "Cuti Melahirkan"
    ]);

    ModelJenisCuti::create([
      "kd_jenis_cuti" => 5,
      "nm_jenis_cuti" => "Cuti Menikah"
    ]);

    ModelJenisCuti::create([
      "kd_jenis_cuti" => 6,
      "nm_jenis_cuti" => "Cuti Meninggal"
    ]);

    ModelJenisCuti::create([
      "kd_jenis_cuti" => 7,
      "nm_jenis_cuti" => "Izin"
    ]);

  }
}
