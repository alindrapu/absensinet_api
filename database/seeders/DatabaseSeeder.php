<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\JenisPresensi;
use App\Models\MasterAgama;
use App\Models\MasterJabatan;
use App\Models\PegawaiCurrent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();

    // User::create([
    //     'nama' => 'Alindra Putra',
    //     'email' => 'alindrapu@gmail.com',
    //     'kd_akses' => 'alin08',
    //     'is_admin' => 1,
    //     'added_kd_akses' => 0,
    //     'password' => bcrypt('rahasia')
    // ]);
    // User::create([
    //     'nama' => 'Suganda',
    //     'email' => 'suganda@gmail.com',
    //     'kd_akses' => 'suga08',
    //     'is_admin' => 0,
    //     'added_kd_akses' => 0,
    //     'password' => bcrypt('rahasia')
    // ]);
    User::create([
        'nama' => 'Nicko Marsellino',
        'email' => 'Nicko@gmail.com',
        'kd_akses' => 'nick08',
        'is_admin' => 0,
        'added_kd_akses' => 0,
        'password' => bcrypt('rahasia')
    ]);


    // MasterJabatan::create([
    //     'kd_jabatan' => '001',
    //     'nm_jabatan' => 'KEPALA DESA'
    // ]);
    // MasterJabatan::create([
    //     'kd_jabatan' => '002',
    //     'nm_jabatan' => 'SEKRETARIS DESA'
    // ]);
    // MasterJabatan::create([
    //     'kd_jabatan' => '003',
    //     'nm_jabatan' => 'KASI PEMERINTAHAN'
    // ]);
    // MasterJabatan::create([
    //     'kd_jabatan' => '004',
    //     'nm_jabatan' => 'KASI PELAYANAN'
    // ]);
    // MasterJabatan::create([
    //     'kd_jabatan' => '005',
    //     'nm_jabatan' => 'KASI KESEJAHTERAAN'
    // ]);
    // MasterJabatan::create([
    //     'kd_jabatan' => '006',
    //     'nm_jabatan' => 'KAUR UMUM'
    // ]);
    // MasterJabatan::create([
    //     'kd_jabatan' => '007',
    //     'nm_jabatan' => 'KAUR PERENCANAAN'
    // ]);
    // MasterJabatan::create([
    //     'kd_jabatan' => '008',
    //     'nm_jabatan' => 'KAUR KEUANGAN'
    // ]);
    // MasterJabatan::create([
    //     'kd_jabatan' => '009',
    //     'nm_jabatan' => 'STAF KESEJAHTERAAN'
    // ]);
    // MasterJabatan::create([
    //     'kd_jabatan' => '010',
    //     'nm_jabatan' => 'STAFF PEMERINTAHAN'
    // ]);

    // MasterAgama::create([
    //     'kd_agama' => 01,
    //     'nm_agama' => 'Islam'
    // ]);
    // MasterAgama::create([
    //     'kd_agama' => 02,
    //     'nm_agama' => 'Kristen'
    // ]);
    // MasterAgama::create([
    //     'kd_agama' => 03,
    //     'nm_agama' => 'Buddha'
    // ]);
    // MasterAgama::create([
    //     'kd_agama' => 04,
    //     'nm_agama' => 'Katolik'
    // ]);
    // MasterAgama::create([
    //     'kd_agama' => 05,
    //     'nm_agama' => 'Protestan'
    // ]);
    // MasterAgama::create([
    //     'kd_agama' => 06,
    //     'nm_agama' => 'Konghu Chu'
    // ]);
    // MasterAgama::create([
    //     'kd_agama' => 07,
    //     'nm_agama' => 'Hindu'
    // ]);

    // PegawaiCurrent::create([
    //     "user_id" => 1,
    //     "kd_akses" => "alin08",
    //     "nama" => "Alindra Putra",
    //     "email" => "alindrapu@gmail.com",
    //     "nik" => "3275051809970006",
    //     "telp" => "081944008008",
    //     "tempat_lahir" => "Jakarta",
    //     "tanggal_lahir" => "1997-09-08",
    //     "jenis_kelamin" => "Pria",
    //     "alamat" => "Jakarta",
    //     "is_admin" => 1,
    //     "kd_agama" => 1,
    //     "kd_jabatan" => "001",
    //     "sts_kepeg" => 1
    // ]);

    // JenisPresensi::create([
    //   'kd_jenis_presensi' => 1,
    //   'nm_jenis_presensi' => 'WFO'
    // ]);
    // JenisPresensi::create([
    //   'kd_jenis_presensi' => 2,
    //   'nm_jenis_presensi' => 'WFA'
    // ]);
    // JenisPresensi::create([
    //   'kd_jenis_presensi' => 3,
    //   'nm_jenis_presensi' => 'Perjalanan Dinas'
    // ]);
    // JenisPresensi::create([
    //   'kd_jenis_presensi' => 4,
    //   'nm_jenis_presensi' => 'Cuti'
    // ]);
    // JenisPresensi::create([
    //   'kd_jenis_presensi' => 5,
    //   'nm_jenis_presensi' => 'Sakit'
    // ]);
    // JenisPresensi::create([
    //   'kd_jenis_presensi' => 6,
    //   'nm_jenis_presensi' => 'Izin'
    // ]);
  }
}
