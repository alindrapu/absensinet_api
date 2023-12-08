<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PegawaiCurrent extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function agama(): BelongsTo
    {
        return $this->belongsTo(MasterAgama::class, 'kd_agama', 'kd_agama');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(MasterJabatan::class, 'kd_jabatan', 'kd_jabatan');
    }
}
