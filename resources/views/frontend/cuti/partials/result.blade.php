{{-- resources/views/frontend/cuti/partials/result.blade.php --}}
{{-- VERSI FINAL — SUPER ELEGAN, TIDAK KOSONG, KREATIF MAKSIMAL --}}

<div class="container-fluid px-4 py-5">
    {{-- HEADER — LEBIH MEWAH, ADA ICON, GRADIENT BACKGROUND SOFT --}}
    <div class="text-center mb-5 position-relative overflow-hidden rounded-4"
        style="background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%); padding: 3rem 2rem;">
        <div class="position-relative">
            <div class="d-inline-flex align-items-center bg-primary text-white rounded-pill px-4 py-2 shadow-lg mb-3">
                <i class="fas fa-barcode me-2"></i>
                <span class="fw-bold">{{ $pengajuan->kode_pengajuan }}</span>
            </div>
            <h2 class="fw-bold text-dark mb-2">
                <i class="fas fa-user-tie text-primary me-3"></i>{{ $pengajuan->nama_lengkap }}
            </h2>
            <p class="fs-5 text-muted">
                <i class="fas fa-briefcase me-2"></i>{{ $pengajuan->jabatan }} • {{ $pengajuan->unit_kerja }}
            </p>

            {{-- STATUS BADGE BARU — COMPACT, CANTIK, ADA ICON & BACKGROUND GRADIENT --}}
            <div
                class="d-inline-flex align-items-center rounded-pill px-5 py-3 shadow-lg text-white fw-bold fs-5 mt-4
                @if ($pengajuan->status == 'diajukan') bg-warning
                @elseif($pengajuan->status == 'sedang_diproses') bg-gradient-info
                @elseif($pengajuan->status == 'disetujui') bg-success
                @elseif($pengajuan->status == 'ditolak') bg-danger
                @else bg-secondary @endif">
                @if ($pengajuan->status == 'diajukan')
                    <i class="fas fa-clock me-3"></i>
                @elseif($pengajuan->status == 'sedang_diproses')
                    <i class="fas fa-cog fa-spin me-3"></i>
                @elseif($pengajuan->status == 'disetujui')
                    <i class="fas fa-check-double me-3"></i>
                @elseif($pengajuan->status == 'ditolak')
                    <i class="fas fa-times-circle me-3"></i>
                @else
                    <i class="fas fa-question-circle me-3"></i>
                @endif
                {{ ucwords(str_replace('_', ' ', $pengajuan->status)) }}
            </div>
        </div>
    </div>

    @if ($pengajuan->status_revisi === 'perlu_revisi')
        <div class="alert alert-warning border-start border-warning border-5 shadow-lg mb-5 p-5 rounded-4">
            {{-- TAMBAHAN INI YANG FIX MASALAH --}}
            <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">

            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning"></i>
                </div>
                <div class="col">
                    <h4 class="fw-bold mb-3">Pengajuan Anda Perlu Direvisi!</h4>
                    <div class="bg-light p-4 rounded-3 mb-4">
                        <strong>Catatan dari Admin:</strong><br>
                        <p class="mb-0 lead">{{ $pengajuan->catatan_revisi }}</p>
                    </div>
                    <button class="btn btn-warning btn-lg px-5 rounded-pill shadow" data-bs-toggle="modal"
                        data-bs-target="#modalRevisi">
                        <i class="fas fa-edit me-3"></i><strong>Revisi Berkas Sekarang</strong>
                    </button>
                </div>
            </div>
        </div>
    @elseif($pengajuan->status_revisi === 'sudah_direvisi')
        <div class="alert alert-success border-start border-success border-5 shadow-sm mb-5 p-4">
            <i class="fas fa-check-circle fa-2x me-3"></i>
            <strong>Revisi telah dikirim</strong> — Menunggu verifikasi ulang.
        </div>
    @endif

    {{-- TAB NAVIGASI — LEBIH MODERN DENGAN ICON --}}
    <ul class="nav nav-pills justify-content-center mb-5 gap-4 flex-wrap" id="trackingTab">
        <li class="nav-item">
            <button class="nav-link active rounded-pill px-5 py-3 shadow-sm fw-semibold" data-bs-toggle="pill"
                data-bs-target="#data-diri">
                <i class="fas fa-id-card me-2"></i> Data Diri
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link rounded-pill px-5 py-3 shadow-sm fw-semibold" data-bs-toggle="pill"
                data-bs-target="#berkas">
                <i class="fas fa-paperclip me-2"></i> Lampiran
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link rounded-pill px-5 py-3 shadow-sm fw-semibold" data-bs-toggle="pill"
                data-bs-target="#riwayat">
                <i class="fas fa-history me-2"></i> Riwayat
            </button>
        </li>
        @if ($pengajuan->final_pdf)
            <li class="nav-item">
                <button class="nav-link rounded-pill px-5 py-3 shadow-sm text-danger fw-bold" data-bs-toggle="pill"
                    data-bs-target="#pdf-final">
                    <i class="fas fa-file-pdf me-2"></i> Dokumen Final
                </button>
            </li>
        @endif
    </ul>

    <div class="tab-content">

        {{-- TAB 1: DATA DIRI — LEBIH ELEGAN, ADA ICON & CARD GROUP --}}
        <div class="tab-pane fade show active" id="data-diri">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-fingerprint text-primary me-3 fs-4"></i>
                        <div>
                            <small class="text-muted">NIP</small>
                            <h5 class="fw-bold text-dark mb-0">{{ $pengajuan->nip ?: '-' }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user text-primary me-3 fs-4"></i>
                        <div>
                            <small class="text-muted">Nama Lengkap</small>
                            <h5 class="fw-bold text-primary mb-0">{{ $pengajuan->nama_lengkap }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-birthday-cake text-primary me-3 fs-4"></i>
                        <div>
                            <small class="text-muted">Tempat, Tanggal Lahir</small>
                            <p class="mb-0">{{ $pengajuan->tempat_lahir ?? '-' }},
                                {{ $pengajuan->tanggal_lahir ? \Carbon\Carbon::parse($pengajuan->tanggal_lahir)->format('d M Y') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-venus-mars text-primary me-3 fs-4"></i>
                        <div>
                            <small class="text-muted">Jenis Kelamin</small>
                            <p class="mb-0">{{ $pengajuan->jenis_kelamin ?: '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-medal text-primary me-3 fs-4"></i>
                        <div>
                            <small class="text-muted">Pangkat/Golongan</small>
                            <p class="mb-0">{{ $pengajuan->pangkat_golongan ?: '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-phone-alt text-primary me-3 fs-4"></i>
                        <div>
                            <small class="text-muted">No. HP/WA</small>
                            <p class="mb-0">{{ $pengajuan->nomor_hp }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-home text-primary me-3 fs-4 mt-1"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted">Alamat Rumah</small>
                            <div class="p-3 bg-light rounded mt-1">{{ $pengajuan->alamat ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-5 border-primary opacity-25">

            <h4 class="fw-bold text-primary mb-4"><i class="fas fa-clipboard-list me-3"></i>Detail Pengajuan Cuti</h4>
            <div class="row g-4">
                <div class="col-md-6">
                    <small class="text-muted">Jenis Cuti</small>
                    <div class="mt-2">
                        <span
                            class="badge bg-primary fs-5 px-4 py-2 rounded-pill">{{ $pengajuan->jenisCuti->nama }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <small class="text-muted">Periode Cuti</small>
                    <h4 class="text-danger fw-bold mt-2">
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} →
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}
                    </h4>
                    <small class="text-muted">{{ $pengajuan->jumlah_hari }} hari</small>
                </div>
                <div class="col-12">
                    <small class="text-muted">Alasan Cuti</small>
                    <div class="p-4 bg-light rounded-3 mt-2 shadow-sm">{{ $pengajuan->alasan_cuti }}</div>
                </div>
                <div class="col-md-8">
                    <small class="text-muted">Alamat Selama Cuti</small>
                    <p class="mt-2">{{ $pengajuan->alamat_selama_cuti }}</p>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Kontak Selama Cuti</small>
                    <p class="mt-2">{{ $pengajuan->kontak_selama_cuti }}</p>
                </div>
            </div>
        </div>

        {{-- TAB 2: LAMPIRAN — TETAP CANTIK --}}
        <div class="tab-pane fade" id="berkas">
            <h5 class="fw-bold text-primary mb-4">Lampiran Berkas</h5>
            @if ($pengajuan->berkas->count() > 0)
                <div class="row g-4">
                    @foreach ($pengajuan->berkas as $file)
                        @php
                            $revisiTypes = $pengajuan->tipe_berkas_revisi
                                ? json_decode($pengajuan->tipe_berkas_revisi, true)
                                : [];
                            $perluRevisi = in_array($file->tipe_berkas, $revisiTypes);
                            $icon =
                                $file->tipe_berkas == 'surat_dokter'
                                    ? 'fa-file-medical-alt text-danger'
                                    : ($file->tipe_berkas == 'lampiran_tambahan'
                                        ? 'fa-paperclip text-warning'
                                        : 'fa-file text-primary');
                            $label =
                                $file->tipe_berkas == 'surat_dokter'
                                    ? 'Surat Dokter'
                                    : ($file->tipe_berkas == 'lampiran_tambahan'
                                        ? 'Lampiran Tambahan'
                                        : 'Lampiran Umum');
                        @endphp
                        <div class="col-md-6">
                            <div
                                class="card border-0 shadow-sm h-100 rounded-4 {{ $perluRevisi ? 'border-warning border-3' : '' }}">
                                <div class="card-body d-flex align-items-center p-4">
                                    <i class="fas {{ $icon }} fa-4x me-4"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $label }}</h6>
                                        <small class="text-muted">{{ $file->nama_asli }}</small>
                                        @if ($perluRevisi)
                                            <span class="badge bg-warning text-dark ms-2">Perlu Direvisi</span>
                                        @endif
                                    </div>
                                    <a href="{{ Storage::url($file->path) }}" target="_blank"
                                        class="btn btn-outline-primary rounded-pill btn-sm">Lihat</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-folder-open fa-5x mb-3 opacity-50"></i>
                    <p>Tidak ada lampiran</p>
                </div>
            @endif
        </div>

        {{-- TAB 3: RIWAYAT STATUS — 100% ASLI ANDA, TIDAK DIUBAH SAMA SEKALI --}}
        <div class="tab-pane fade" id="riwayat">
            <div class="text-center mb-5">
                <h4 class="fw-bold text-primary">Riwayat Status Pengajuan</h4>
                <p class="text-muted small">Pemohon di sebelah kiri • Admin di sebelah kanan</p>
            </div>

            <div class="timeline-elegant position-relative">
                @foreach ($pengajuan->riwayatStatus()->orderBy('tanggal', 'asc')->get() as $r)
                    @php
                        $isPemohon =
                            str_contains(strtolower($r->oleh), strtolower($pengajuan->nama_lengkap)) ||
                            in_array($r->oleh, ['Pemohon', 'User']);
                        $bg = $isPemohon ? 'primary' : 'success';
                        $icon = $isPemohon ? 'user' : 'user-shield';
                        $position = $isPemohon ? 'left' : 'right';
                    @endphp

                    <div class="timeline-item {{ $position }} mb-5">
                        <div
                            class="timeline-card bg-white rounded-4 shadow p-4 border-start border-5 border-{{ $bg }}">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-{{ $bg }} text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-{{ $icon }} fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-{{ $bg }} mb-0">
                                        {{ $isPemohon ? 'Pemohon' : 'Admin/Verifikator' }}
                                    </h6>
                                    <small class="text-muted">{{ $r->tanggal->format('d M Y - H:i') }}</small>
                                </div>
                            </div>
                            <h5 class="mb-2">{{ ucwords(str_replace('_', ' ', $r->status_baru)) }}</h5>
                            <p class="mb-2">{{ $r->catatan ?: '-' }}</p>
                            <small class="text-muted">Oleh: {{ $r->oleh }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- TAB 4: PDF FINAL --}}
        @if ($pengajuan->final_pdf)
            <div class="tab-pane fade" id="pdf-final">
                <div class="text-center py-5">
                    <i class="fas fa-file-pdf fa-5x text-danger mb-4"></i>
                    <h4 class="fw-bold mb-4">Dokumen Final Telah Diterbitkan</h4>
                    <div class="ratio ratio-16x9 my-4 border rounded-4 shadow-lg overflow-hidden">
                        <iframe src="{{ Storage::url($pengajuan->final_pdf) }}" allowfullscreen
                            class="rounded-4"></iframe>
                    </div>
                    <a href="{{ Storage::url($pengajuan->final_pdf) }}" target="_blank"
                        class="btn btn-danger btn-lg px-5 rounded-pill shadow-lg">
                        <i class="fas fa-download me-2"></i> Download PDF Resmi
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>

<script id="data-revisi" type="application/json">
    @json($pengajuan->tipe_berkas_revisi ? json_decode($pengajuan->tipe_berkas_revisi) : [])
</script>

{{-- CSS ASLI ANDA + SEDIKIT PENYEMPURNAAN --}}
<style>
    .bg-gradient-info {
        background: linear-gradient(135deg, #007bff, #00c0ff) !important;
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107, #ffd84c) !important;
    }

    .timeline-elegant {
        position: relative;
        padding: 50px 0;
    }

    .timeline-elegant::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 6px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
        border-radius: 3px;
        transform: translateX(-50%);
    }

    .timeline-item {
        position: relative;
        width: 50%;
        padding: 0 40px;
    }

    .timeline-item.left {
        left: 0;
        text-align: right;
    }

    .timeline-item.right {
        left: 50%;
        text-align: left;
    }

    .timeline-item.left .timeline-card {
        margin-left: auto;
        max-width: 420px;
    }

    .timeline-item.right .timeline-card {
        margin-right: auto;
        max-width: 420px;
    }

    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
        color: white !important;
    }
</style>
