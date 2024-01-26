<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPresensi extends Model
{
    use HasFactory;

    protected $table = 'jenis_presensis';

    protected $guarded = [
        'id',
    ];

    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'kd_jenis_presensi', 'kd_jenis_presensi');
    }
}
