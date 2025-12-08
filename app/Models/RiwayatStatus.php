<?php

namespace App\Models;

use App\Traits\ModelLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatStatus extends Model
{
    use HasFactory, SoftDeletes, ModelLog;
    protected $table = 'riwayat_status';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function pengajuanCuti()
    {
        return $this->belongsTo(PengajuanCuti::class, 'pengajuan_cuti_id');
    }
}
