<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAgama extends Model
{
  use HasFactory;

  protected $table = 'master_agamas';

  protected $fillable = [
    'kd_agama',
    'nm_agama'
  ];

  public function pegawaiCurrent() {
    return $this->hasMany(PegawaiCurrent::class, 'kd_agama', 'kd_agama');
  }
}
