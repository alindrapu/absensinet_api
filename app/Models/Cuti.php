<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuti extends Model
{
  use HasFactory;

  protected $table = "cutis";

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'kd_akses', 'kd_akses');
  }

  public function jabatan(): BelongsTo
  {
    return $this->belongsTo(MasterJabatan::class, 'kd_jabatan', 'kd_jabatan');
  }

  public function jenis_cuti(): BelongsTo
  {
    return $this->belongsTo(JenisCuti::class, 'kd_jenis_cuti', 'kd_jenis_cuti');
  }

  public function status_permohonan(): BelongsTo
  {
    return $this->belongsTo(MasterStatusPermohonanCuti::class, 'kd_status_permohonan', 'kd_status_permohonan');
  }
}
