<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Exports\PresensiExport;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PresensiController extends Controller
{
  public function presensiPegawai(Request $request)
  {
    $response = [];
    $td = Carbon::today();
    $today = $td->toDateString();
    $user_id = DB::table('users')->where('kd_akses', $request->kd_akses)->pluck('id')->first();

    // logic : check apakah hari ini sudah absen atau belum, kalau belum input kd_akses, lat, long, tanggal presensi, jam masuk, status_lokasi_masuk, kd_jns_presensi.
    // kalau absen keluar input jam_keluar, lat_keluar, long_keluar, status_lokasi_keluar

    // Check apakah sudah ada absen hari ini
    $q_check = DB::select("select tanggal_presensi from presensis where kd_akses = '" . $request->kd_akses . "' and tanggal_presensi = '$today'");

    if (empty($q_check) || $q_check == '') {
      // lakukan absen masuk
      try {
        DB::insert(
          'INSERT INTO presensis (
            user_id,
            kd_akses,
            latitude_masuk,
            longitude_masuk,
            tanggal_presensi,
            jam_masuk,
            status_lokasi_masuk,
            kd_jenis_presensi,
            alasan,
            created_at,
            updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
          [
            $user_id,
            $request->kd_akses,
            $request->latitude,
            $request->longitude,
            $today,
            Carbon::now()->toTimeString(),
            $request->status_lokasi_masuk,
            $request->kd_jenis_absensi,
            $request->alasan,
            Carbon::now(),
            Carbon::now()
          ]
        );
        $response = ['status' => 'Berhasil absen masuk'];
        return response()->json($response, 200);
      } catch (QueryException $e) {
        $response = ['error' => $e];
        return response()->json($response, 422);
      }
    } elseif (!empty($q_check) || $q_check != '') {
      // lakukan absen keluar
      try {
        // DB::update(
        //   'UPDATE presensis
        //   SET latitude_keluar = ?,
        //   longitude_keluar = ?,
        //   jam_keluar = ?,
        //   status_lokasi_keluar = ?,
        //   updated_at = ?
        //   WHERE user_id = ?
        //   AND tanggal_presensi = ?',
        //   [
        //     $request->latitude,
        //     $request->longitude,
        //     Carbon::now()->toTimeString(),
        //     $request->status_lokasi_keluar,
        //     Carbon::now(),
        //     $user_id,
        //     $request->tanggal_presensi
        //   ]
        // );
        $query = DB::table('presensis')
          ->where('user_id', $user_id)
          ->where('tanggal_presensi', $today)
          ->update([
            'latitude_keluar' => $request->latitude,
            'longitude_keluar' => $request->longitude,
            'jam_keluar' => Carbon::now()->toTimeString(),
            'status_lokasi_keluar' => $request->status_lokasi_keluar,
            'updated_at' => Carbon::now(),
          ]);


        $response = [
          'query' => $query,
          'status' => 'Berhasil absen keluar',
          'time_check' => Carbon::now(),
          'today' => $today
        ];
        return response()->json($response, 200);
      } catch (QueryException $e) {
        $response = ['error' => "Gagal melakukan absen keluar, coba lagi", "message" => $e];
        return response()->json($response, 422);
      }
    }
  }

  public function checkPresensi(Request $request)
  {
    $response = [];
    $td = Carbon::today();
    $today = $td->toDateString();
    $user_id = DB::table('users')->where('kd_akses', $request->kd_akses)->pluck('id')->first();

    $q_check = DB::select("select tanggal_presensi from presensis where kd_akses = '" . $request->kd_akses . "' and tanggal_presensi = '$today'");

    $check_presensi = DB::select("SELECT jam_masuk, jam_keluar from presensis where user_id = '$user_id' and tanggal_presensi = '$today' and kd_akses = '$request->kd_akses'");

    $response = ["message" => $check_presensi, "Status" => "Success"];

    return response()->json($response, 200);
  }

  public function last5Days(Request $request)
  {
    $validated = $request->validate([
      "kd_akses" => "required",
    ]);

    $q_check = DB::select("select tanggal_presensi, jam_masuk, jam_keluar, alasan, latitude_masuk, longitude_masuk, latitude_keluar, longitude_keluar, status_lokasi_masuk, status_lokasi_keluar from presensis where kd_akses = '" . $validated['kd_akses'] . "' order by tanggal_presensi desc limit 5");

    $response = ["message" => "Berhasil", "data" => $q_check];
    return response()->json($response, 200);
  }

  public function allHistory(Request $request)
  {
    $validated = $request->validate([
      "kd_akses" => "required",
      "hist_month" => "integer",
      "hist_year" => "integer"
    ]);

    $response = "";
    $cond_month = !empty($validated['hist_month']) ? "and month(tanggal_presensi) = " . $validated['hist_month'] . "" : "";
    $cond_year = !empty($validated['hist_year']) ? "and year(tanggal_presensi) = " . $validated['hist_year'] . "" : "";

    try {
      $q_check = DB::select("select tanggal_presensi, jam_masuk, jam_keluar, alasan from presensis where kd_akses = '" . $validated['kd_akses'] . "' $cond_month $cond_year order by tanggal_presensi desc");

      $response = ["message" => "Berhasil mengambil data", "data" => $q_check];
      return response()->json($response, 200);
    } catch (QueryException $e) {
      $response = ['error' => "Gagal mengambil data, coba lagi", "error" => $e];
      return response()->json($response, 422);
    }
  }

  public function export(Request $request)
  {
    $validated = $request->validate([
      'tanggal_presensi' => 'date'
    ]);

    $tanggal_presensi = Carbon::parse($validated['tanggal_presensi'])->format('Y-m');
    Carbon::setLocale('id');

    $currentDate = Carbon::now();
    $month = $currentDate->translatedFormat('F');
    $year = $currentDate->format('Y');

    $filename = "log_presensi_{$month}_{$year}.xlsx";

    return Excel::download(new PresensiExport($tanggal_presensi), $filename);
  }
}
