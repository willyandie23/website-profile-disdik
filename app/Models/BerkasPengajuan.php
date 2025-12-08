<?php

namespace App\Models;

use App\Traits\ModelLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BerkasPengajuan extends Model
{
    use HasFactory, ModelLog;
    protected $table = 'berkas_pengajuan';
    protected $guarded = ['id'];

    public function pengajuanCuti()
    {
        return $this->belongsTo(PengajuanCuti::class, 'pengajuan_cuti_id');
    }
}
