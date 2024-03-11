<?php

namespace App\Http\Controllers;

use App\Models\JenisCuti;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function getJenisCuti() {
      $listCuti = JenisCuti::select('nm_jenis_cuti', 'kd_jenis_cuti')->get();
      $response = ['status' => 'sukses', 'value' => $listCuti];

      return response()->json($response, 200);
    }
}
