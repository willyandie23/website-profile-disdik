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

        // === KOLOM BARU WAJIB DI-CAST ===
        'approved_at_tu'        => 'datetime',
        'approved_at_kasubbag'  => 'datetime',
        'tanggal_kembali_kerja' => 'date',

        // Enum string (biar Laravel treat sebagai string, bukan integer)
        'status'                => 'string',
        'level_approval'        => 'string',     // tu | kasubbag | selesai
        'status_revisi'         => 'string',     // tidak_perlu | perlu_revisi | sudah_direvisi
    ];

    // === CONSTANTA UNTUK STATUS (SUPAYA TYPE-SAFE & AUTOCOMPLETE) ===
    public const STATUS_DRAFT                  = 'draft';
    public const STATUS_DIAJUKAN               = 'diajukan';
    public const STATUS_DIMINTA_REVISI         = 'diminta_revisi';
    public const STATUS_DIREVISI               = 'direvisi';
    public const STATUS_SEDANG_DIPROSES_TU     = 'sedang_diproses_tu';
    public const STATUS_DISETUJUI_TU           = 'disetujui_tu';
    public const STATUS_SEDANG_DIPROSES_KASUBBAG = 'sedang_diproses_kasubbag';
    public const STATUS_DISETUJUI_KASUBBAG     = 'disetujui_kasubbag';
    public const STATUS_DITOLAK                = 'ditolak';
    public const STATUS_DIBATALKAN             = 'dibatalkan';
    public const STATUS_SELESAI                = 'selesai';

    public const LEVEL_TU          = 'tu';
    public const LEVEL_KASUBBAG    = 'kasubbag';
    public const LEVEL_SELESAI     = 'selesai';

    public const REVISI_TIDAK_PERLU     = 'tidak_perlu';
    public const REVISI_PERLU_REVISI    = 'perlu_revisi';
    public const REVISI_SUDAH_DIREVISI  = 'sudah_direvisi';

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

    // === ACCESSOR & MUTATOR (BIAR LEBIH MUDAH DIPAKAI DI BLADE / API) ===

    // Contoh: nama status yang lebih manusiawi
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_DRAFT                  => 'Draft',
            self::STATUS_DIAJUKAN               => 'Diajukan',
            self::STATUS_DIMINTA_REVISI         => 'Minta Revisi',
            self::STATUS_DIREVISI               => 'Sudah Direvisi',
            self::STATUS_SEDANG_DIPROSES_TU     => 'Proses TU',
            self::STATUS_DISETUJUI_TU           => 'Disetujui TU',
            self::STATUS_SEDANG_DIPROSES_KASUBBAG => 'Proses Kasubbag',
            self::STATUS_DISETUJUI_KASUBBAG     => 'Disetujui Kasubbag',
            self::STATUS_DITOLAK                => 'Ditolak',
            self::STATUS_DIBATALKAN             => 'Dibatalkan',
            self::STATUS_SELESAI                => 'Selesai',
            default                             => ucfirst($this->status),
        };
    }

    // Badge color untuk UI
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_DRAFT                  => 'bg-secondary',
            self::STATUS_DIAJUKAN               => 'bg-primary',
            self::STATUS_DIMINTA_REVISI         => 'bg-warning text-dark',
            self::STATUS_DIREVISI               => 'bg-info',
            self::STATUS_SEDANG_DIPROSES_TU,
            self::STATUS_SEDANG_DIPROSES_KASUBBAG => 'bg-orange text-dark',
            self::STATUS_DISETUJUI_TU,
            self::STATUS_DISETUJUI_KASUBBAG     => 'bg-success',
            self::STATUS_DITOLAK                => 'bg-danger',
            self::STATUS_DIBATALKAN             => 'bg-dark',
            self::STATUS_SELESAI                => 'bg-teal',
            default                             => 'bg-secondary',
        };
    }

    // Cek apakah sedang menunggu revisi dari pemohon
    public function perluRevisi()
    {
        return $this->status_revisi === self::REVISI_PERLU_REVISI;
    }

    // Cek apakah boleh diajukan
    public function bisaDiajukan()
    {
        return $this->status === self::STATUS_DRAFT || $this->status === self::STATUS_DIREVISI;
    }
}
