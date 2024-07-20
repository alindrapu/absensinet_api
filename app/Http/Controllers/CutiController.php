<?php

namespace App\Http\Controllers;

use App\Exports\CutiExport;
use App\Models\Cuti;
use App\Models\JenisCuti;
use App\Models\MasterStatusPermohonanCuti;
use App\Models\PegawaiCurrent;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

// todo:
// 1. create get list apporoval cuti, binding ke user login done
// 2. create approval/reject cuti approval done, reject wip
// 3. create detail cuti

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
      "alasan_cuti" => "required|string",
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
    try {
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
    } catch (QueryException $e) {
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
      'id_permohonan' => 'required|integer',
      'status_persetujuan' => 'required|bool'
    ]);

    $now = Carbon::now()->format('Y-m-d');
    // declare variable dari validated array
    foreach ($validated as $key => $val) {
      $key = strtolower($key);
      $$key = $val;
    }
    // 1	Permohonan Disetujui
    // 2	Menunggu Persetujuan Sekretaris
    // 3	Menunggu Persetujuan Kepala Desa
    // 4	Permohonan Ditolak
    // 5	Permohonan Dibatalkan

    $curr_kd_status_permohonan = Cuti::where('id', $id_permohonan)->first()->kd_status_permohonan;
    $jabatan_approver = PegawaiCurrent::where('kd_akses', $kd_akses_approver)->select('kd_jabatan', 'nama')->first();
    $jabatan_pemohon = PegawaiCurrent::where('kd_akses', $kd_akses_pemohon)->select('kd_jabatan')->first();
    $nama_atasan = PegawaiCurrent::where('kd_akses', $kd_akses_approver)->select('nama')->first();

    if ($jabatan_pemohon !== "001" || $jabatan_pemohon !== "002") {
      if ($status_persetujuan == true) {
        if ($curr_kd_status_permohonan == 2) {
          $next_kd_status_permohonan = 3;
          $updateCuti = ['nama_atasan_1' => $nama_atasan];
        } else if ($curr_kd_status_permohonan == 3) {
          $next_kd_status_permohonan = 1;
          $updateCuti = ['nama_atasan_2' => $nama_atasan];
        }
      } else {
        $next_kd_status_permohonan = 4;
      }
    } else {
      $next_kd_status_permohonan = $status_persetujuan == true ? 1 : 4;
    }

    try {
      $updateCuti = [
        'kd_status_permohonan' => $next_kd_status_permohonan
      ];



      DB::beginTransaction();
      Cuti::where('id', $id_permohonan)->update($updateCuti);

      DB::commit();
      $response = ["status" => "Success", "message" => "Berhasil memperbarui status permohonan."];

      return response()->json($response, 200);
    } catch (QueryException $e) {
      $response = ["status" => "Error", "message" => $e];
      DB::rollBack();

      return response()->json($response, 422);
    }
  }

  public function getListPermohonanCuti(Request $request)
  {
    $validated = $request->validate([
      "kd_akses" => 'required'
    ]);

    // Cek jabatan user
    $jabatan = DB::table('pegawai_currents')->where('kd_akses', $validated['kd_akses'])->value('kd_jabatan');

    if ($jabatan == "001") { // Kepala Desa
      $kd_status_permohonan = 3;
    } else if ($jabatan == "002") { // Sekretaris
      $kd_status_permohonan = 2;
    } else {
      return response()->json("Anda tidak memiliki hak akses approval!", 422);
    }

    // $kd_status_permohonan = 2;

    try {
      $query = DB::select("SELECT
      a.id, a.kd_akses, a.nama, a.alasan_cuti, b.nm_jabatan, c.nm_jenis_cuti, a.lama_cuti, a.tanggal_mulai, a.tanggal_selesai, a.created_at AS tanggal_pengajuan
      FROM cutis a
      LEFT JOIN master_jabatans b ON a.kd_jabatan = b.kd_jabatan
      LEFT JOIN jenis_cutis c ON a.kd_jenis_cuti = c.kd_jenis_cuti
      WHERE kd_status_permohonan = $kd_status_permohonan
      ORDER BY tanggal_pengajuan ASC");


      $response = [
        "Status" => "Success", "message" => "Berhasil mengambil data permohonan cuti",
        "total" => count($query),
        "data" => $query
      ];
      return response()->json($response, 200);
    } catch (Exception $e) {
      $response = ["Error : $e", "message" => "Gagal mengambil data permohonan cuti"];
      return response()->json($response, 500);
    }
  }

  public function export(Request $request)
  {
    $validated = $request->validate([
      'tanggal_cuti' => 'date'
    ]);

    $tanggal_cuti = Carbon::parse($validated['tanggal_cuti'])->format('Y-m');
    $filename = "log_cuti_" . Carbon::now()->format("Y-m-d") . ".xlsx";
    return Excel::download(new CutiExport($tanggal_cuti), $filename);
  }
}
