<?php

namespace App\Http\Controllers;

use App\Models\MasterAgama;
use Illuminate\Http\Request;

class MasterAgamaController extends Controller
{
    public function getAgama(){
      $listAgama = MasterAgama::select('nm_agama', 'kd_agama')->get();
      $response = ['status' => 'sukses', 'value' => $listAgama];

      return response()->json($response, 200);
    }
}
