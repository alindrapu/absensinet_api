<?php

namespace App\Http\Controllers;

use App\Models\PegawaiCurrentPosition;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiCurrentPositionController extends Controller
{
    public function updateCurrentPosition(Request $request)
    {
        $validated = $request->validate([
            'kd_akses' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        $response = [];

        try {
            $user_id = DB::table('users')->where('kd_akses', $validated['kd_akses'])->pluck('id')->first();

            // Do update if exist, else -> insert
            DB::table('pegawai_current_positions')->updateOrInsert(
                ['kd_akses' => $validated['kd_akses']],
                [
                    'user_id' => $user_id,
                    'latitude' => $validated['latitude'],
                    'longitude' => $validated['longitude'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );


            $response = [
                "status" => "Berhasil",
                "message" => "Berhasil memperbarui posisi pegawai!",
                "latitude" => $validated['latitude'],
                "longitude" => $validated['longitude']
            ];

            return response()->json($response, 200);
        } catch (\Throwable $th) {
            throw new HttpResponseException(response()->json(['status' => 'Terjadi Kesalahan', 'message' => 'Gagal memperbarui posisi pegawai!', "error" => $th->getMessage()], 422));
        }
    }
}
