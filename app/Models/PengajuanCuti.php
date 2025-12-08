<?php

namespace App\Models;

use App\Traits\ModelLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanCuti extends Model
{
    use HasFactory, SoftDeletes, ModelLog;

    protected $table = 'pengajuan_cuti';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_pengajuan' => 'datetime',
    ];

    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class, 'jenis_cuti_id');
    }

    public function berkas()
    {
        return $this->hasMany(BerkasPengajuan::class, 'pengajuan_cuti_id');
    }

    public function riwayatStatus()
    {
        return $this->hasMany(RiwayatStatus::class, 'pengajuan_cuti_id')->orderBy('tanggal', 'desc');
    }
}
