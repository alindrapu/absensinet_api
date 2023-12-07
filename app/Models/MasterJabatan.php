<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJabatan extends Model
{
    use HasFactory;

    protected $table = 'master_jabatans';

    protected $fillable = [
      'kd_jabatan',
      'nm_jabatan'
    ];

    public function pegawaiCurrent() {
        return $this->hasMany(PegawaiCurrent::class, 'kd_jabatan', 'kd_jabatan');
      }
}
