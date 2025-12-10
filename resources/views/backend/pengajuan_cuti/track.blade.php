@extends('backend.layouts.app')

@section('title', 'Track History Pengajuan Cuti')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <a href="{{ url()->previous() }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-2"></i>
                        </a>
                        Track History: {{ $pengajuan->kode_pengajuan }}
                    </h4>
                    <div>
                        @if (auth()->user()->hasRole('kassubag'))
                            @if ($pengajuan->final_pdf)
                                <a href="/storage/{{ $pengajuan->final_pdf }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-pdf"></i> Download Surat Cuti
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card-body">

                    {{-- Tombol Aksi — KHUSUS PEJABAT --}}
                    <div class="mb-4">
                        @php
                            $canProcess = false;
                            $canCancel = false;

                            $isAdminTu =
                                auth()->user()->hasRole('admin') &&
                                $pengajuan->level_approval === 'tu' &&
                                in_array($pengajuan->status, ['diajukan', 'sedang_diproses']) &&
                                $pengajuan->status_revisi !== 'perlu_revisi';

                            $isKasubbag =
                                auth()->user()->hasRole('kassubag') &&
                                $pengajuan->level_approval === 'kasubbag' &&
                                $pengajuan->status === 'sedang_diproses' &&
                                $pengajuan->status_revisi !== 'perlu_revisi';

                            // Cek apakah admin TU sudah approve dan bisa cancel
                            $adminTuHasApproved =
                                auth()->user()->hasRole('admin') &&
                                $pengajuan->level_approval === 'kasubbag' &&
                                $pengajuan->status === 'sedang_diproses';

                            // Cek apakah kasubbag sudah approve/tolak dan bisa cancel
                            $kasubbagHasProcessed =
                                auth()->user()->hasRole('kassubag') &&
                                in_array($pengajuan->status, ['disetujui', 'ditolak']);

                            // Cek apakah admin bisa upload final_pdf
                            $canUploadFinalPdf =
                                auth()->user()->hasRole('admin') &&
                                $pengajuan->approved_by_tu &&
                                $pengajuan->approved_at_tu &&
                                $pengajuan->approved_by_kasubbag &&
                                $pengajuan->approved_at_kasubbag;

                            $canProcess = $isAdminTu || $isKasubbag;
                            $canCancel = $adminTuHasApproved || $kasubbagHasProcessed;
                        @endphp

                        {{-- PANEL UPLOAD FINAL PDF (Khusus Admin setelah semua approved) --}}
                        @if ($canUploadFinalPdf)
                            <div class="action-panel bg-info-subtle border border-info rounded-3 p-4 shadow-sm mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-info">
                                            <i class="fas fa-upload me-2"></i>
                                            Upload Surat Cuti Final
                                        </h6>
                                        <small class="text-muted">
                                            Pengajuan telah disetujui oleh semua pihak. Silakan upload surat cuti final.
                                        </small>
                                        @if ($pengajuan->final_pdf)
                                            <div class="mt-2">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Surat sudah diupload
                                                </span>
                                                <a href="/storage/{{ $pengajuan->final_pdf }}" target="_blank"
                                                    class="btn btn-sm btn-outline-success ms-2">
                                                    <i class="fas fa-eye"></i> Lihat Surat
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalUploadFinalPdf">
                                            <i class="fas fa-file-upload me-1"></i>
                                            {{ $pengajuan->final_pdf ? 'Update' : 'Upload' }} Surat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- PANEL AKSI NORMAL (Belum diproses oleh user yang login) --}}
                        @if ($canProcess)
                            <div class="action-panel bg-light border rounded-3 p-4 shadow-sm mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-primary">
                                            <i class="fas fa-user-cog me-2"></i>
                                            Panel Aksi
                                            @if ($isAdminTu)
                                                <span class="text-info">Admin TU</span>
                                            @elseif($isKasubbag)
                                                <span class="text-warning">Kasubbag</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted">
                                            @if ($pengajuan->status === 'diajukan')
                                                Pengajuan baru masuk
                                            @else
                                                Sedang dalam proses verifikasi
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                {{-- TOMBOL LIHAT BERKAS (WAJIB!) --}}
                                @if ($pengajuan->berkas->count() > 0)
                                    <div class="mb-3">
                                        <button class="btn btn-info btn-sm me-2" data-bs-toggle="modal"
                                            data-bs-target="#modalBerkas">
                                            <i class="fas fa-folder-open me-1"></i> Lihat Berkas Lampiran
                                            ({{ $pengajuan->berkas->count() }} file)
                                        </button>
                                    </div>
                                @else
                                    <div class="alert alert-warning small mb-3">
                                        <i class="fas fa-exclamation-triangle"></i> Belum ada berkas yang diupload
                                    </div>
                                @endif

                                <div class="d-flex gap-2 flex-wrap">
                                    @if ($isAdminTu)
                                        <button class="btn btn-success btn-sm" onclick="teruskan({{ $pengajuan->id }})">
                                            <i class="fas fa-check me-1"></i> Setujui & Teruskan
                                        </button>
                                    @endif

                                    @if ($isKasubbag)
                                        <button class="btn btn-success btn-sm" onclick="teruskan({{ $pengajuan->id }})">
                                            <i class="fas fa-check-double me-1"></i> Setujui Final
                                        </button>
                                    @endif

                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-revisi="{{ $pengajuan->catatan_revisi }}" data-bs-target="#modalRevisi">
                                        <i class="fas fa-edit me-1"></i> Minta Revisi
                                    </button>

                                    <button class="btn btn-danger btn-sm" onclick="tolakPengajuan({{ $pengajuan->id }})">
                                        <i class="fas fa-times me-1"></i> Tolak
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- PANEL CANCEL AKSI (Sudah diproses, bisa dibatalkan) --}}
                        @if ($canCancel)
                            <div class="action-panel bg-warning-subtle border border-warning rounded-3 p-4 shadow-sm mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-warning">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            Aksi Sudah Diproses
                                        </h6>
                                        <small class="text-muted">
                                            @if ($adminTuHasApproved)
                                                Anda telah menyetujui dan meneruskan ke Kasubbag
                                            @elseif($kasubbagHasProcessed)
                                                Anda telah memproses pengajuan ini
                                            @endif
                                        </small>
                                        <div class="mt-2">
                                            <span class="badge bg-warning text-dark">
                                                Status: {{ $pengajuan->status_label ?? ucfirst($pengajuan->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-outline-danger btn-sm"
                                            onclick="cancelApproval({{ $pengajuan->id }})">
                                            <i class="fas fa-undo me-1"></i> Batalkan Aksi
                                        </button>
                                    </div>
                                </div>

                                @if ($pengajuan->berkas->count() > 0)
                                    <div class="mt-3">
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalBerkas">
                                            <i class="fas fa-folder-open me-1"></i> Lihat Berkas Lampiran
                                            ({{ $pengajuan->berkas->count() }} file)
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Sekdes & Kadis --}}
                        @if (auth()->user()->hasAnyRole(['sekdes', 'kadis']))
                            <div class="action-panel bg-light border rounded-3 p-3 shadow-sm mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-eye fa-2x text-secondary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Mode Pemantauan</h6>
                                        <small class="text-muted">
                                            @if (auth()->user()->hasRole('sekdes'))
                                                Sekretaris Dinas
                                            @elseif(auth()->user()->hasRole('kadis'))
                                                Kepala Dinas
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Status Akhir --}}
                        @if (in_array($pengajuan->status, ['disetujui', 'ditolak', 'selesai', 'dibatalkan']) && !$canCancel)
                            <div
                                class="action-panel border rounded-3 p-3 shadow-sm
                                        {{ $pengajuan->status === 'disetujui'
                                            ? 'bg-success-subtle border-success'
                                            : ($pengajuan->status === 'ditolak'
                                                ? 'bg-danger-subtle border-danger'
                                                : ($pengajuan->status === 'selesai'
                                                    ? 'bg-info-subtle border-info'
                                                    : 'bg-dark-subtle border-dark')) }}">

                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        @if ($pengajuan->status === 'disetujui')
                                            <h6 class="mb-0 text-success fw-bold">
                                                <i class="fas fa-check-circle me-2"></i>Pengajuan Disetujui
                                            </h6>
                                        @elseif($pengajuan->status === 'ditolak')
                                            <h6 class="mb-0 text-danger fw-bold">
                                                <i class="fas fa-times-circle me-2"></i>Pengajuan Ditolak
                                            </h6>
                                        @elseif($pengajuan->status === 'selesai')
                                            <h6 class="mb-0 text-info fw-bold">
                                                <i class="fas fa-flag-checkered me-2"></i>Cuti Selesai
                                            </h6>
                                        @elseif($pengajuan->status === 'dibatalkan')
                                            <h6 class="mb-0 text-dark fw-bold">
                                                <i class="fas fa-ban me-2"></i>Pengajuan Dibatalkan
                                            </h6>
                                        @endif
                                    </div>
                                    @if ($pengajuan->status === 'disetujui' && $pengajuan->final_pdf)
                                        <a href="/storage/{{ $pengajuan->final_pdf }}" target="_blank"
                                            class="btn btn-success btn-sm">
                                            <i class="fas fa-file-pdf me-1"></i> Unduh Surat
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Informasi Pengajuan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th width="40%">NIP / Nama</th>
                                    <td>{{ $pengajuan->nip }} - {{ $pengajuan->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Cuti</th>
                                    <td>{{ $pengajuan->jenisCuti->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Cuti</th>
                                    <td>{{ $pengajuan->tanggal_mulai->format('d/m/Y') }} →
                                        {{ $pengajuan->tanggal_selesai->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Hari</th>
                                    <td><strong>{{ $pengajuan->jumlah_hari }} hari</strong></td>
                                </tr>
                                <tr>
                                    <th>Alasan</th>
                                    <td>{{ $pengajuan->alasan_cuti }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th>Status Saat Ini</th>
                                    <td>
                                        <span class="badge {{ $pengajuan->status_badge ?? 'bg-secondary' }} fs-6">
                                            {{ $pengajuan->status_label ?? ucfirst($pengajuan->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Level Approval</th>
                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $pengajuan->level_approval)) }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pengajuan</th>
                                    <td>{{ $pengajuan->tanggal_pengajuan->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if ($pengajuan->approved_at_kasubbag)
                                    <tr>
                                        <th>Disetujui Kasubbag</th>
                                        <td>{{ $pengajuan->approved_at_kasubbag->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endif
                                @if ($pengajuan->nomor_surat)
                                    <tr>
                                        <th>Nomor Surat</th>
                                        <td><strong>{{ $pengajuan->nomor_surat }}</strong></td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Catatan Revisi (jika ada) -->
                    @if ($pengajuan->status_revisi === 'perlu_revisi' && $pengajuan->catatan_revisi)
                        <div class="alert alert-warning border-start border-warning border-5">
                            <h5><i class="fas fa-exclamation-triangle"></i> Sedang Revisi</h5>
                            <p class="mb-0">{{ $pengajuan->catatan_revisi }}</p>
                            <small class="text-muted d-block mt-2">
                                Oleh: {{ $pengajuan->revisi_oleh }}
                                ({{ $pengajuan->revisi_oleh ? 'Admin TU' : 'Kasubbag' }})
                                @if ($pengajuan->tanggal_revisi)
                                    • {{ \Carbon\Carbon::parse($pengajuan->tanggal_revisi)->format('d M Y H:i') }}
                                @endif
                            </small>
                        </div>
                    @elseif ($pengajuan->status_revisi === 'sudah_direvisi' && $pengajuan->catatan_revisi)
                        <div class="alert alert-info border-start border-info border-5">
                            <h5><i class="fas fa-check-circle"></i> Sudah Direvisi</h5>
                            <p class="mb-2"><strong>Catatan revisi:</strong> {{ $pengajuan->catatan_revisi }}</p>
                            <small class="text-muted d-block">
                                Diminta oleh: {{ $pengajuan->revisi_oleh }}
                                ({{ $pengajuan->revisi_oleh ? 'Admin TU' : 'Kasubbag' }})
                            </small>
                            <div class="mt-2">
                                <span class="badge bg-success">
                                    <i class="fas fa-hourglass-half"></i> Menunggu verifikasi ulang
                                </span>
                            </div>
                        </div>
                    @elseif($pengajuan->status_revisi === 'perlu_revisi' && $pengajuan->tipe_berkas_revisi)
                        <div class="alert alert-warning border-start border-warning border-5 shadow-sm mb-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                                <div>
                                    <h5 class="alert-heading fw-bold">
                                        Perlu Revisi Berkas
                                    </h5>
                                    <p class="mb-2">{{ $pengajuan->catatan_revisi }}</p>

                                    <div class="mt-3">
                                        <strong class="text-danger">Berkas yang harus diperbaiki:</strong>
                                        <ul class="list-unstyled mb-0 mt-2">
                                            @foreach (json_decode($pengajuan->tipe_berkas_revisi, true) as $tipe)
                                                <li class="mb-2">
                                                    <span class="badge bg-danger me-2">
                                                        <i class="fas fa-times-circle"></i>
                                                    </span>
                                                    <strong>{{ ucwords(str_replace('_', ' ', $tipe)) }}</strong>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <hr class="my-3">
                                    <small class="text-muted">
                                        <i class="fas fa-user-clock"></i>
                                        Diminta oleh: <strong>{{ $pengajuan->revisi_oleh }}</strong>
                                        pada {{ $pengajuan->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Timeline Header -->
                    <div class="mb-4 mt-5">
                        <h5 class="fw-bold text-dark mb-4 text-center">
                            <i class="fas fa-history text-primary"></i> Riwayat Status Pengajuan
                        </h5>
                    </div>

                    <!-- Timeline Riwayat Status - Responsive Flexbox -->
                    <div class="timeline-container">
                        @foreach ($pengajuan->riwayatStatus as $index => $riwayat)
                            @php
                                $isLast = $loop->last;
                                $isEven = $loop->iteration % 2 == 0;
                                $status = $riwayat->status_baru;

                                $config = [
                                    'draft' => [
                                        'color' => 'secondary',
                                        'icon' => 'fa-pen-fancy',
                                        'label' => 'Draft Dibuat',
                                        'bg' => '#6c757d',
                                    ],
                                    'diajukan' => [
                                        'color' => 'primary',
                                        'icon' => 'fa-paper-plane',
                                        'label' => 'Diajukan',
                                        'bg' => '#0d6efd',
                                    ],
                                    'sedang_diproses' => [
                                        'color' => 'warning',
                                        'icon' => 'fa-cogs',
                                        'label' => 'Sedang Diproses',
                                        'bg' => '#ffc107',
                                    ],
                                    'disetujui' => [
                                        'color' => 'success',
                                        'icon' => 'fa-check-double',
                                        'label' => 'Disetujui',
                                        'bg' => '#198754',
                                    ],
                                    'ditolak' => [
                                        'color' => 'danger',
                                        'icon' => 'fa-times-circle',
                                        'label' => 'Ditolak',
                                        'bg' => '#dc3545',
                                    ],
                                    'selesai' => [
                                        'color' => 'info',
                                        'icon' => 'fa-flag-checkered',
                                        'label' => 'Selesai',
                                        'bg' => '#0dcaf0',
                                    ],
                                    'dibatalkan' => [
                                        'color' => 'dark',
                                        'icon' => 'fa-ban',
                                        'label' => 'Dibatalkan',
                                        'bg' => '#212529',
                                    ],
                                ];

                                $item = $config[$status] ?? [
                                    'color' => 'secondary',
                                    'icon' => 'fa-circle',
                                    'label' => ucfirst(str_replace('_', ' ', $status)),
                                    'bg' => '#6c757d',
                                ];
                            @endphp

                            <div class="timeline-item {{ $isEven ? 'timeline-right' : 'timeline-left' }}">
                                <!-- Badge Tanggal -->
                                <div class="timeline-badge" style="background: {{ $item['bg'] }};">
                                    {{ $riwayat->tanggal->locale('id')->translatedFormat('d F Y') }}
                                </div>

                                <!-- Content Card -->
                                <div class="timeline-content">
                                    <div class="timeline-card bg-white shadow-sm border">
                                        <h6 class="fw-bold text-{{ $item['color'] }} mb-2">
                                            <i class="fas {{ $item['icon'] }} me-2"></i>{{ $item['label'] }}
                                        </h6>

                                        @if ($riwayat->catatan)
                                            <p class="text-muted mb-2" style="font-size: 0.95rem;">
                                                {{ $riwayat->catatan }}
                                            </p>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <small class="text-dark">
                                                <i class="fas fa-user-circle me-1"></i>
                                                <strong>{{ $riwayat->oleh }}</strong>
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $riwayat->tanggal->format('H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @unless ($isLast)
                                <div class="timeline-line-connector"></div>
                            @endunless
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Lihat Berkas -->
    <div class="modal fade" id="modalBerkas" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-folder-open"></i> Berkas Lampiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($pengajuan->berkas->count() > 0)
                        <div class="row">
                            @foreach ($pengajuan->berkas as $berkas)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-primary h-100">
                                        <div class="card-body text-center p-3">
                                            <i class="fas fa-file-pdf fa-3x text-primary mb-3"></i>
                                            <p class="small text-muted mb-1">{{ $berkas->tipe_berkas }}</p>
                                            <a href="/storage/{{ $berkas->path }}" target="_blank"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="/storage/{{ $berkas->path }}" download
                                                class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted">Belum ada berkas yang diupload.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Minta Revisi (Admin TU & Kasubbag) -->
    <div class="modal fade" id="modalRevisi" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title text-light">
                        <i class="fas fa-edit"></i> Minta Revisi Berkas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formMintaRevisi">
                    @csrf
                    <div class="modal-body">

                        <!-- Checkbox Tipe Berkas yang Harus Direvisi -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-danger">
                                <i class="fas fa-exclamation-triangle"></i> Pilih Berkas yang Harus Direvisi <span
                                    class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tipe_berkas_revisi[]"
                                            value="lampiran_tambahan" id="rev_lampiran">
                                        <label class="form-check-label fw-semibold" for="rev_lampiran">
                                            Lampiran Tambahan
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tipe_berkas_revisi[]"
                                            value="surat_dokter" id="rev_dokter">
                                        <label class="form-check-label fw-semibold" for="rev_dokter">
                                            Surat Dokter
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tipe_berkas_revisi[]"
                                            value="lampiran_cuti" id="rev_lampiran_cuti">
                                        <label class="form-check-label fw-semibold" for="rev_lampiran_cuti">
                                            Lampiran Cuti
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Centang semua yang perlu diperbaiki</small>
                        </div>

                        <hr>

                        <!-- Catatan Revisi -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Revisi <span class="text-danger">*</span></label>
                            <textarea name="catatan_revisi" class="form-control" rows="6" required
                                placeholder="Jelaskan dengan jelas apa yang perlu diperbaiki pada berkas yang dipilih..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning fw-bold">
                            <i class="fas fa-paper-plane"></i> Kirim Permintaan Revisi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Upload Final PDF (Khusus Admin) -->
    <div class="modal fade" id="modalUploadFinalPdf" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-file-upload"></i> Upload Surat Cuti Final</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formUploadFinalPdf" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Upload surat cuti yang sudah ditandatangani oleh pejabat
                            berwenang
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Surat <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_surat" class="form-control"
                                value="{{ $pengajuan->nomor_surat }}" placeholder="Contoh: 123/SK/BKPSDM/2024" required>
                            <small class="text-muted">Format: Nomor/Kode/Instansi/Tahun</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">File Surat Cuti (PDF) <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="final_pdf" class="form-control" accept=".pdf"
                                {{ $pengajuan->final_pdf ? '' : 'required' }}>
                            <small class="text-muted">Format: PDF, Maksimal 5MB</small>

                            @if ($pengajuan->final_pdf)
                                <div class="mt-2">
                                    <small class="text-success">
                                        <i class="fas fa-check-circle"></i> File saat ini:
                                        <a href="/storage/{{ $pengajuan->final_pdf }}" target="_blank">
                                            Lihat surat yang sudah diupload
                                        </a>
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Perhatian:</strong> Pastikan nomor surat dan file PDF sudah benar sebelum upload.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-upload"></i> Upload Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#modalRevisi').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let catatan = button.data('revisi');

                // Masukkan ke dalam textarea
                $(this).find('textarea[name="catatan_revisi"]').val(catatan);
            });
        });

        // 1. Teruskan (Admin TU / Kasubbag)
        function teruskan(id) {
            Swal.fire({
                title: 'Yakin menyetujui?',
                text: 'Pengajuan akan diteruskan ke tahap berikutnya',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/api/pengajuan-cuti/${id}/teruskan`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Authorization': `Bearer ${token}`,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Berhasil!', data.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                        });
                }
            });
        }

        // 2. Cancel Approval
        function cancelApproval(id) {
            Swal.fire({
                title: 'Batalkan Aksi?',
                text: 'Pengajuan akan dikembalikan ke status sebelumnya',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/api/pengajuan-cuti/${id}/cancel_approval`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Authorization': `Bearer ${token}`,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Berhasil!', data.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                        });
                }
            });
        }

        // 3. Batalkan Pengajuan (by User)
        function batalkanPengajuan(id) {
            Swal.fire({
                title: 'Batalkan pengajuan?',
                text: 'Pengajuan akan dibatalkan secara permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Tidak'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/api/pengajuan-cuti/${id}/batalkan`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Authorization': `Bearer ${token}`,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Berhasil!', data.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                        });
                }
            });
        }

        // 4. Submit Minta Revisi
        document.getElementById('formMintaRevisi')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Tutup modal terlebih dahulu
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalRevisi'));
            if (modal) {
                modal.hide();
            }

            // Tunggu sebentar agar modal benar-benar tertutup
            setTimeout(() => {
                Swal.fire({
                    title: 'Kirim permintaan revisi?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, kirim!',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch(`/api/pengajuan-cuti/{{ $pengajuan->id }}/minta_revisi`, {
                                method: 'POST',
                                headers: {
                                    'Authorization': `Bearer ${token}`,
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Terkirim!', data.message, 'success')
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire('Gagal!', data.message, 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                            });
                    } else {
                        // Jika dibatalkan, buka kembali modal
                        modal.show();
                    }
                });
            }, 300);
        });

        // 5. Tolak Pengajuan
        function tolakPengajuan(id) {
            Swal.fire({
                title: 'Tolak Pengajuan?',
                text: 'Pengajuan akan ditolak dan tidak dapat diproses lagi',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/api/pengajuan-cuti/${id}/tolak`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Authorization': `Bearer ${token}`,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Ditolak!', data.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                        });
                }
            });
        }

        // 6. Upload Final PDF (Admin)
        document.getElementById('formUploadFinalPdf')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Validasi file jika diupload
            const fileInput = this.querySelector('input[name="final_pdf"]');
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];

                // Validasi tipe file
                if (file.type !== 'application/pdf') {
                    Swal.fire('Error!', 'File harus berformat PDF', 'error');
                    return;
                }

                // Validasi ukuran file (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire('Error!', 'Ukuran file maksimal 5MB', 'error');
                    return;
                }
            }

            // Tutup modal terlebih dahulu
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalUploadFinalPdf'));
            if (modal) {
                modal.hide();
            }

            // Tampilkan konfirmasi
            setTimeout(() => {
                Swal.fire({
                    title: 'Upload Surat Final?',
                    text: 'Pastikan nomor surat dan file PDF sudah benar',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#17a2b8',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Upload!',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Mengupload...',
                            html: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch(`/api/pengajuan-cuti/{{ $pengajuan->id }}/upload_final_pdf`, {
                                method: 'POST',
                                headers: {
                                    'Authorization': `Bearer ${token}`,
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Berhasil!', data.message, 'success')
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire('Gagal!', data.message, 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error!', 'Terjadi kesalahan pada server', 'error');
                            });
                    } else {
                        // Jika dibatalkan, buka kembali modal
                        modal.show();
                    }
                });
            }, 300);
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Timeline Container */
        .timeline-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 0;
        }

        /* Garis vertikal tengah */
        .timeline-container::before {
            content: '';
            position: absolute;
            width: 3px;
            background: linear-gradient(180deg, #e9ecef 0%, #dee2e6 100%);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -1.5px;
            z-index: 0;
        }

        /* Timeline Item */
        .timeline-item {
            position: relative;
            width: 50%;
            padding: 20px 40px;
            box-sizing: border-box;
        }

        .timeline-item.timeline-left {
            left: 0;
            text-align: right;
        }

        .timeline-item.timeline-right {
            left: 50%;
            text-align: left;
        }

        /* Badge Tanggal */
        .timeline-badge {
            position: absolute;
            padding: 8px 20px;
            background: #7c3aed !important;
            color: white;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            z-index: 10;
            box-shadow: 0 4px 8px rgba(124, 58, 237, 0.3);
        }

        .timeline-left .timeline-badge {
            right: -70px;
            top: 15px;
        }

        .timeline-right .timeline-badge {
            left: -70px;
            top: 15px;
        }

        /* Content Card */
        .timeline-content {
            position: relative;
            margin-top: 40px
        }

        .timeline-card {
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e9ecef !important;
            transition: all 0.3s ease;
            background: white;
        }

        .timeline-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
            transform: translateY(-3px);
        }

        .timeline-left .timeline-card {
            margin-right: 20px;
        }

        .timeline-right .timeline-card {
            margin-left: 20px;
        }

        /* Line Connector */
        .timeline-line-connector {
            height: 40px;
        }

        /* Responsive untuk tablet */
        @media screen and (max-width: 768px) {
            .timeline-container::before {
                left: 30px;
            }

            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
                left: 0 !important;
                text-align: left !important;
            }

            .timeline-badge {
                left: -15px !important;
                right: auto !important;
                top: -35px !important;
                font-size: 0.75rem;
                padding: 6px 15px;
            }

            .timeline-card {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }

        /* Responsive untuk mobile */
        @media screen and (max-width: 480px) {
            .timeline-container {
                padding: 20px 0;
            }

            .timeline-item {
                padding-left: 60px;
                padding-right: 15px;
            }

            .timeline-badge {
                font-size: 0.7rem;
                padding: 5px 12px;
            }

            .timeline-card {
                padding: 15px;
            }
        }
    </style>
@endpush
