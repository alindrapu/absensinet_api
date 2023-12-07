<?php

namespace App\Http\Controllers;

use App\Models\PegawaiCurrent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PegawaiCurrentController extends Controller
{
    public function addPegawaiCurrent(Request $request)
    {
        try {
            $dataCurrent = [
                "user_id" => $request->user_id,
                "kd_akses" => $request->kd_akses,
                "nama" => $request->nama,
                "email" => $request->email,
                "nik" => $request->nik,
                "telp" => $request->telp,
                "tempat_lahir" => $request->tempat_lahir,
                "tanggal_lahir" => $request->tanggal_lahir,
                "alamat" => $request->alamat,
                "is_admin" => $request->is_admin,
                "kd_agama" => $request->kd_agama,
                "kd_jabatan" => $request->kd_jabatan,
                "sts_kepeg" => $request->sts_kepeg
            ];

            PegawaiCurrent::create($dataCurrent);

            $response = ['status' => 'Berhasil menambahkan pegawai', 'value' => $dataCurrent];

            return response()->json($response, 200);
        } catch (QueryException $e) {
            $response = ['status' => 'Gagal menambahkan pegawai', 'pesan' => $e->getMessage(), 'value' => $dataCurrent];
            return response()->json($response, 500);
        }
    }
}
