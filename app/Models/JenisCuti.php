<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisCuti extends Model
{
  use HasFactory;

  protected $table = 'jenis_cutis';

  protected $guarded = [
    "kd_jenis_cuti",
  ];

  public function jenis_cuti(): HasMany
  {
    return $this->hasMany(Cuti::class, 'kd_jenis_cuti', 'kd_jenis_cuti');
  }
}
