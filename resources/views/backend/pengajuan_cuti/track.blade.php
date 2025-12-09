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
                    {{-- <div>
                        @if ($pengajuan->final_pdf)
                            <a href="/storage/{{ $pengajuan->final_pdf }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fas fa-file-pdf"></i> Download Surat Cuti
                            </a>
                        @endif
                    </div> --}}
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
                                in_array($pengajuan->status, ['diajukan', 'sedang_diproses']);

                            $isKasubbag =
                                auth()->user()->hasRole('kassubag') &&
                                $pengajuan->level_approval === 'kasubbag' &&
                                $pengajuan->status === 'sedang_diproses';

                            // Cek apakah admin TU sudah approve dan bisa cancel
                            $adminTuHasApproved =
                                auth()->user()->hasRole('admin') &&
                                $pengajuan->level_approval === 'kasubbag' &&
                                $pengajuan->status === 'sedang_diproses';

                            // Cek apakah kasubbag sudah approve/tolak dan bisa cancel
                            $kasubbagHasProcessed =
                                auth()->user()->hasRole('kassubag') &&
                                in_array($pengajuan->status, ['disetujui', 'ditolak']);

                            $canProcess = $isAdminTu || $isKasubbag;
                            $canCancel = $adminTuHasApproved || $kasubbagHasProcessed;
                        @endphp

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
                            </table>
                        </div>
                    </div>

                    <!-- Catatan Revisi (jika ada) -->
                    @if ($pengajuan->status_revisi === 'perlu_revisi' && $pengajuan->catatan_revisi)
                        <div class="alert alert-warning border-start border-warning border-5">
                            <h5><i class="fas fa-exclamation-triangle"></i> Perlu Revisi</h5>
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

                                <!-- Dot Icon -->
                                {{-- <div class="timeline-dot" style="background: {{ $item['bg'] }} !important;">
                                    <i class="fas {{ $item['icon'] }} text-white"></i>
                                </div> --}}

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
                                            <p class="small text-muted mb-1">{{ $berkas->nama_asli }}</p>
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

    <!-- Modal Minta Revisi (DIGUNAKAN BERSAMA oleh Admin TU & Kasubbag) -->
    <div class="modal fade" id="modalRevisi" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title text-light"><i class="fas fa-edit"></i> Minta Revisi Berkas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formMintaRevisi">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Revisi <span class="text-danger">*</span></label>
                            <textarea name="catatan_revisi" class="form-control" rows="5" required
                                placeholder="Tulis dengan jelas bagian mana yang perlu diperbaiki..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-paper-plane"></i> Kirim Permintaan Revisi
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

        /* Dot Icon */
        .timeline-dot {
            position: absolute;
            width: 50px;
            height: 50px;
            background: #7c3aed;
            border-radius: 50%;
            border: 5px solid white;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .timeline-left .timeline-dot {
            right: -65px;
            top: 10px;
        }

        .timeline-right .timeline-dot {
            left: -65px;
            top: 10px;
        }

        .timeline-dot i {
            font-size: 1.2rem;
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

            .timeline-dot {
                left: 5px !important;
                right: auto !important;
                width: 45px;
                height: 45px;
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

            .timeline-dot {
                width: 40px;
                height: 40px;
                left: 0 !important;
            }

            .timeline-dot i {
                font-size: 1rem;
            }

            .timeline-card {
                padding: 15px;
            }
        }
    </style>
@endpush
