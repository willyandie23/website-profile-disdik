<?php

namespace App\Models;

use App\Traits\ModelLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisCuti extends Model
{
    use HasFactory, SoftDeletes, ModelLog;

    protected $table = 'jenis_cuti';

    protected $fillable = [
        'nama',
        'maks_hari',
        'butuh_surat_dokter',
        'butuh_lampiran_tambahan',
        'keterangan',
    ];

    /**
     * Relasi ke pengajuan cuti
     * Satu jenis cuti bisa dipakai oleh banyak pengajuan
     */
    public function pengajuan()
    {
        return $this->hasMany(PengajuanCuti::class, 'jenis_cuti_id');
    }
}
