<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\JenisCuti;
use App\Models\MasterStatusPermohonanCuti;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceResponse;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;

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
      "kd_jenis_cuti" => "required|integer",
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

    // Cek tidak bisa mengajukan untuk tanggal yang sama
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

    if ($q_check) {
      $response = ["status" => "Error", "message" => "Sudah ada pengajuan cuti di tanggal yang sama"];
      return response()->json($response, 500);
    }

    $lama_cuti = $tanggal_mulai->diffInDays($tanggal_selesai) + 1;
    $kd_jabatan = '002';
    $kd_status_permohonan = ($kd_jabatan == '002' ? 3 : 2);

    $status_permohonan = MasterStatusPermohonanCuti::where('kd_status_permohonan', $kd_status_permohonan)->select('nm_status_permohonan')->first();

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
        'tanggal_buat' => Carbon::now()->format("Y_m_d"),
        'kd_status_permohonan' => $kd_status_permohonan,
      ];

      $cuti = new Cuti;
      $cuti->fill($createCuti);
      $cuti->timestamps = false;

      $cuti->save();
      DB::commit();

      $response = ["status" => "Success", "message" => "Berhasil mengajukan cuti, status saat ini {$status_permohonan->nm_status_permohonan}"];

      return response()->json($response, 200);
    } catch (QueryException $e) {
      $response = ["status" => "Error", "message" => $e];
      DB::rollBack();

      return response()->json($response, 422);
    }
  }
}
