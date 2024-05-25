<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\JenisCuti;
use App\Models\MasterStatusPermohonanCuti;
use App\Models\PegawaiCurrent;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CutiController extends Controller
{
  public function getJenisCuti()
  {
    $listCuti = JenisCuti::select('nm_jenis_cuti', 'kd_jenis_cuti')->get();
    $response = ['status' => 'sukses', 'value' => $listCuti];

    return response()->json($response, 200);
  }

  public function requestCuti(Request $request)
  {
    $validated = $request->validate([
      "kd_akses" => "required",
      "alasan_cuti" => "required",
      "nm_jenis_cuti" => "required|string",
      "tanggal_mulai" => "required|date",
      "tanggal_selesai" => "required|date",
    ]);

    // declare variable dari validated
    foreach ($validated as $key => $val) {
      $key = strtolower($key);
      $$key = $val;
    }


    $tanggal_mulai = Carbon::parse($tanggal_mulai);
    $tanggal_selesai = Carbon::parse($tanggal_selesai);

    $data_pegawai = DB::table('pegawai_currents')
      ->where('kd_akses', $kd_akses)
      ->select('kd_jabatan', 'nama')
      ->first();
    $kd_jabatan = $data_pegawai->kd_jabatan;
    $nama = $data_pegawai->nama;
    $kd_jenis_cuti = DB::table('jenis_cutis')->where('nm_jenis_cuti', $nm_jenis_cuti)->value('kd_jenis_cuti');

    // Cek tidak bisa mengajukan untuk tanggal yang sama
    try{
      $q_check = Cuti::where('kd_akses', $kd_akses)
      ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
        $query->where(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
          $q->where('tanggal_mulai', '<=', $tanggal_mulai)
            ->where('tanggal_selesai', '>=', $tanggal_mulai);
        })
          ->orWhere(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
            $q->where('tanggal_mulai', '<=', $tanggal_selesai)
              ->where('tanggal_selesai', '>=', $tanggal_selesai);
          })
          ->orWhere(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
            $q->where('tanggal_mulai', '>=', $tanggal_mulai)
              ->where('tanggal_selesai', '<=', $tanggal_selesai);
          });
      })
      ->first();
    } catch (QueryException $e){
      $response = ["status" => "Error", "message" => $e];
    } finally {
      if ($q_check) {
        $response = ["status" => "Error", "message" => "Sudah ada pengajuan cuti di tanggal yang sama"];
        return response()->json($response, 500);
      }
    }

    $kd_status_permohonan = ($kd_jabatan == '002' ? 3 : 2);
    $status_permohonan = MasterStatusPermohonanCuti::where('kd_status_permohonan', $kd_status_permohonan)->select('nm_status_permohonan')->first();
    $lama_cuti = $tanggal_mulai->diffInDays($tanggal_selesai) + 1;
    $tanggal_mulai = Carbon::parse($tanggal_mulai)->format('Y-m-d');
    $tanggal_selesai = Carbon::parse($tanggal_selesai)->format('Y-m-d');


    try {
      DB::beginTransaction();

      $createCuti = [
        'kd_akses' => $kd_akses,
        'nama' => $nama,
        'kd_jabatan' => $kd_jabatan,
        'alasan_cuti' => $alasan_cuti,
        'kd_jenis_cuti' => $kd_jenis_cuti,
        'tanggal_mulai' => $tanggal_mulai,
        'tanggal_selesai' => $tanggal_selesai,
        'lama_cuti' => $lama_cuti,
        'kd_status_permohonan' => $kd_status_permohonan,
      ];

      Cuti::create($createCuti);
      DB::commit();

      $response = ["status" => "Success", "message" => "Berhasil mengajukan cuti, status saat ini {$status_permohonan->nm_status_permohonan}"];

      return response()->json($response, 200);
    } catch (QueryException $e) {
      $response = ["status" => "Error", "message" => $e];
      DB::rollBack();

      return response()->json($response, 422);
    }
  }

  public function approvalCuti(Request $request)
  {
    $validated = $request->validate([
      'kd_akses_approver' => 'required',
      'kd_akses_pemohon' => 'required',
      'id_permohonan' => 'integer|required',
      'kd_status_permohonan' => 'integer|required'
    ]);
    // declare v_variable dari validated array
    foreach ($validated as $key => $val) {
      $key = strtolower("v_" . $key);
      $$key = $val;
    }
    $now = Carbon::now()->format('Y-m-d');
    $nama_atasan_1 = PegawaiCurrent::where('kd_akses', $v_kd_akses_approver)->select('nama')->pluck('nama')->first();

    try {
      $updateCuti = [
        'kd_status_permohonan' => $v_kd_status_permohonan,
      ];

      if ($v_kd_status_permohonan === 2) { // persetujuan sekretaris
        $updateCuti['tanggal_approve_atasan_1'] = $now;
        $updateCuti['nama_atasan_1'] = $nama_atasan_1;
        $updateCuti['kd_akses_atasan_1'] = $v_kd_akses_approver;
      } else if ($v_kd_status_permohonan === 1) { // persetujuan kepala desa
        $updateCuti['tanggal_approve_atasan_2'] = $now;
        $updateCuti['nama_atasan_2'] = $nama_atasan_2;
        $updateCuti['kd_akses_atasan_2'] = $v_kd_akses_approver;
      }

      DB::beginTransaction();
      Cuti::where('id', $v_id_permohonan)->update($updateCuti);

      DB::commit();
      $response = ["status" => "Success", "message" => "Berhasil memperbarui status permohonan."];

      return response()->json($response, 200);
    } catch (QueryException $e) {
      $response = ["status" => "Error", "message" => $e];
      DB::rollBack();

      return response()->json($response, 422);
    }
  }
}
