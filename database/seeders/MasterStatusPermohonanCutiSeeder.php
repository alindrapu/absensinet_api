<?php

namespace Database\Seeders;

use App\Models\MasterStatusPermohonanCuti;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterStatusPermohonanCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MasterStatusPermohonanCuti::create([
          "kd_status_permohonan" => 1,
          "nm_status_permohonan" => "Permohonan Disetujui"
        ]);
        MasterStatusPermohonanCuti::create([
          "kd_status_permohonan" => 2,
          "nm_status_permohonan" => "Menunggu Persetujuan Sekretaris"
        ]);
        MasterStatusPermohonanCuti::create([
          "kd_status_permohonan" => 3,
          "nm_status_permohonan" => "Menunggu Persetujuan Kepala Desa"
        ]);
        MasterStatusPermohonanCuti::create([
          "kd_status_permohonan" => 4,
          "nm_status_permohonan" => "Permohonan Ditolak"
        ]);
        MasterStatusPermohonanCuti::create([
          "kd_status_permohonan" => 5,
          "nm_status_permohonan" => "Permohonan Dibatalkan"
        ]);
    }
}
