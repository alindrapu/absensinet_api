<?php

namespace App\Exports;

use App\Models\Presensi;
use Maatwebsite\Excel\Concerns\FromCollection;

class PresensisExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Presensi::all();
    }
}
