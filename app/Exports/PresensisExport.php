<?php

namespace App\Exports;

use App\Models\Presensi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class PresensisExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
      $result = DB::select("select kd_akses, tanggal_presensi from presensis");
      return $result;
    }
}
