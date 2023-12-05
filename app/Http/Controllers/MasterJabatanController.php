<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterJabatanController extends Controller
{
    public function getJabatan() {
      $listJabatan = \App\Models\MasterJabatan::select('kd_jabatan', 'nm_jabatan')->get();
      $response = ['status' => 'sukses', 'value' => $listJabatan];

      return response()->json($response, 200);
    }
}
