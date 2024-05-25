<?php

namespace App\Http\Controllers;

use App\Models\PegawaiCurrent;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
  public function register(Request $request)
  {
    $response = [];
    try {
      DB::transaction(function () use ($request, &$response) {
        $validated = $request->validate([
          'nama' => 'required|max:55',
          'email' => 'email|required|unique:users',
          'password' => 'required',
          'kd_akses' => 'required|max:6|unique:users|regex:/^[a-zA-Z0-9]+$/',
          'is_admin' => 'required',
          'added_kd_akses' => 'required'
        ]);

        $user = DB::table('users')->insert([
          'nama' => $validated['nama'],
          'email' => $validated['email'],
          'password' => bcrypt($validated['password']),
          'kd_akses' => $validated['kd_akses'],
          'is_admin' => $validated['is_admin'],
          'added_kd_akses' => $validated['added_kd_akses'],
          'created_at' => now(),
          'updated_at' => now(),
        ]);


        $tanggalLahir = date('Y-m-d', strtotime($request->tanggal_lahir));
        $kd_agama = DB::table('master_agamas')->where('nm_agama', $request->nm_agama)->pluck('kd_agama')->first();
        $kd_jabatan = DB::table('master_jabatans')->where('nm_jabatan', $request->nm_jabatan)->pluck('kd_jabatan')->first();
        $user_id = DB::table('users')->where('email', $request->email)->pluck('id')->first();

        try {
          $dataCurrent = [
            "user_id" => $user_id,
            "kd_akses" => $request->kd_akses,
            "nama" => $request->nama,
            "email" => $request->email,
            "nik" => $request->nik,
            "telp" => $request->telp,
            "tempat_lahir" => $request->tempat_lahir,
            "tanggal_lahir" => $tanggalLahir,
            "jenis_kelamin" => $request->jenis_kelamin,
            "alamat" => $request->alamat,
            "is_admin" => $request->is_admin,
            "kd_agama" => $kd_agama,
            "kd_jabatan" => $kd_jabatan,
            "sts_kepeg" => ($request->sts_kepeg == 'Aktif' ? 1 : 0)
          ];

          PegawaiCurrent::create($dataCurrent);

          $response = ['status' => 'Berhasil menambahkan pegawai', 'value' => $dataCurrent];

          return response()->json($response, 200);
        } catch (QueryException $e) {
          $response = ['status' => 'Gagal menambahkan pegawai', 'pesan' => $e->getMessage(), 'value' => $dataCurrent];
          return response()->json($response, 500);
        }

        $response = ['status' => 'success', 'message' => 'Pendaftaran akun telah berhasil!', 'user_data' => $user, 'pegawai_current' => $dataCurrent];

        return $response;
      });
      DB::commit();
      return response()->json($response, 200);
    } catch (\Throwable $th) {
    }
  }

  public function login(Request $request)
  {
    $user = User::where('kd_akses', $request->kd_akses)->first();

    if (!$user) {
      throw new HttpResponseException(response()->json(['status' => 'Terjadi Kesalahan', 'message' => 'User tidak ditemukan, cek kembali kode akses Anda!'], 422));
    }

    $current = DB::table('pegawai_currents as pc')
      ->join('master_agamas as ma', 'pc.kd_agama', '=', 'ma.kd_agama')
      ->join('master_jabatans as mj', 'pc.kd_jabatan', '=', 'mj.kd_jabatan')
      ->select('mj.nm_jabatan', 'ma.nm_agama', 'pc.sts_kepeg')
      ->where('pc.kd_akses', '=', $request->kd_akses)
      ->get();

    if (Hash::check($request->password, $user->password)) {
      $token = $user->createToken('authToken')->plainTextToken;
      $response = [
        'status' => 'success',
        'token' => $token,
        'user' => $user,
        'jabatan' => $current[0]->nm_jabatan,
        'agama' => $current[0]->nm_agama,
        'sts_kepeg' => $current[0]->sts_kepeg,
        'message' => 'Berhasil login'
      ];
      return response()->json($response, 200);
    } else {
      throw new HttpResponseException(response()->json(['status' => 'error', 'message' => 'Email atau password tidak sesuai! Cek kembali email dan password Anda!'], 422));
    }
  }

  public function newKdPass(Request $request)
  {
    $response = [];
    try {
      $update = User::where('email', $request->email)
        ->update([
          'password' => bcrypt($request->password),
          'kd_akses' => $request->kd_akses,
          'added_kd_akses' => 1
        ]);

      if ($update) {
        $updateCurrent = PegawaiCurrent::where('email', $request->email)
          ->update([
            'kd_akses' => $request->kd_akses
          ]);

        $user = User::where('email', $request->email)->first();
        $user->save();

        $response = [
          'status' => 'Berhasil',
          'message' => 'Password berhasil diubah',
          'data' => $user
        ];
      } else {
        $response = [
          'status' => 'Terjadi Kesalahan',
          'message' => 'Password gagal diubah',
        ];
      }

      return response()->json($response, 200);
    } catch (\Throwable $th) {
      $response = [
        'status' => 'Terjadi Kesalahan',
        'message' => 'Password gagal diubah',
        'error' => $th->getMessage()
      ];
      return response()->json($response, 400);
    }
  }

  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    $response = ['status' => 'logout', 'message' => 'Berhasil logout'];
    return response($response, 200);
  }
}
