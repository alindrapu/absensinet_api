<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $user = User::create([
          'nama' => $validated['nama'],
          'email' => $validated['email'],
          'password' => $validated['password'],
          'kd_akses' => $validated['kd_akses'],
          'is_admin' => $validated['is_admin'],
          'added_kd_akses' => $validated['added_kd_akses'],
        ]);

        if (env('APP_ENV') == 'local') {
          $response = ['status' => 'success', 'message' => 'Pendaftaran akun telah berhasil! Silahkan isi Data Pribadi Pegawai', 'user_data' => $user];
        } else {
          $response = ['status' => 'success', 'message' => 'Pendaftaran akun telah berhasil! Silahkan isi Data Pribadi Pegawai'];
        }
        return $response;
      });
      DB::commit();
      return response()->json($response, 200);
    } catch (\Throwable $th) {
      $response = ['status' => 'error', 'message' => 'Pendaftaran akun gagal, periksa kembali isian Anda atau coba lagi nanti', 'error' => $th->getMessage()];
      return response()->json($response, 400);
    }
  }

  public function login(Request $request)
  {
    $user = User::where('kd_akses', $request->kd_akses)->first();

    if (Hash::check($request->password, $user->password)) {
      $token = $user->createToken('authToken')->plainTextToken;
      $response = ['status' => 'success', 'token' => $token, 'user' => $user, 'message' => 'Berhasil login'];
      return response()->json($response, 200);
    } else if (empty($user)) {
      throw new HttpResponseException(response()->json(['status' => 'error', 'message' => 'Kesini', "request" => $request], 422));
    } else {
      throw new HttpResponseException(response()->json(['status' => 'error', 'message' => 'Email atau password tidak sesuai! Cek kembali email dan password Anda!'], 422));
    }
    $response = ['status' => 'error', 'message' => 'Server Error'];
    return response()->json($response, 500);
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
        $user = User::where('email', $request->email)->first();
        $user->save();

        $response = [
          'status' => 'success',
          'message' => 'Password berhasil diubah',
          'data' => $user
        ];
      } else {
        $response = [
          'status' => 'error',
          'message' => 'Password gagal diubah',
          'error' => 'Update failed'
        ];
      }

      return response()->json($response, 200);
    } catch (\Throwable $th) {
      $response = [
        'status' => 'error',
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
