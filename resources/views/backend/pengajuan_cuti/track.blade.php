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
                        @if ($pengajuan->final_pdf)
                            <a href="/storage/{{ $pengajuan->final_pdf }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fas fa-file-pdf"></i> Download Surat Cuti
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
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
                            <small class="text-muted">Oleh: {{ $pengajuan->revisi_oleh }}
                                ({{ $pengajuan->revisi_oleh ? 'Admin TU' : 'Kasubbag' }})</small>
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

                    <!-- Tombol Aksi (sesuai hak & status) -->
                    <div class="mt-5 p-4 border rounded bg-light">
                        <h5 class="mb-3"><i class="fas fa-cogs text-primary"></i> Aksi Tersedia</h5>
                        <div class="d-grid gap-2">

                            @if ($pengajuan->status_revisi === 'perlu_revisi' && $pengajuan->nip === auth()->user()?->nip)
                                <button class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#modalRevisi">
                                    <i class="fas fa-upload me-2"></i> Upload Ulang Berkas & Kirim Revisi
                                </button>
                            @endif

                            @if (auth()->user()->hasRole('admin_tu') &&
                                    $pengajuan->level_approval === 'tu' &&
                                    $pengajuan->status === 'sedang_diproses')
                                <button class="btn btn-success btn-lg"
                                    onclick="prosesApproval('terima_tu', {{ $pengajuan->id }})">
                                    <i class="fas fa-check me-2"></i> Setujui & Kirim ke Kasubbag
                                </button>
                                <button class="btn btn-danger btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#modalRevisiTu">
                                    <i class="fas fa-times me-2"></i> Minta Revisi
                                </button>
                            @endif

                            @if (auth()->user()->hasRole('kasubbag') &&
                                    $pengajuan->level_approval === 'kasubbag' &&
                                    $pengajuan->status === 'sedang_diproses')
                                <button class="btn btn-success btn-lg"
                                    onclick="prosesApproval('terima_kasubbag', {{ $pengajuan->id }})">
                                    <i class="fas fa-check-double me-2"></i> Setujui (Final)
                                </button>
                                <button class="btn btn-danger btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#modalRevisiKasubbag">
                                    <i class="fas fa-times me-2"></i> Minta Revisi
                                </button>
                                <button class="btn btn-secondary btn-lg"
                                    onclick="prosesApproval('tolak', {{ $pengajuan->id }})">
                                    <i class="fas fa-ban me-2"></i> Tolak Pengajuan
                                </button>
                            @endif

                            @if (in_array($pengajuan->status, ['diajukan', 'sedang_diproses']) && $pengajuan->nip === auth()->user()?->nip)
                                <button class="btn btn-dark btn-lg" onclick="batalkanPengajuan({{ $pengajuan->id }})">
                                    <i class="fas fa-ban me-2"></i> Batalkan Pengajuan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Revisi Pemohon -->
    <div class="modal fade" id="modalRevisi" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Ulang Berkas Revisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formRevisi">
                    <div class="modal-body">
                        <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                        <div class="mb-3">
                            <label class="form-label">Upload Berkas Baru (PDF/JPG/PNG)</label>
                            <input type="file" name="berkas[]" class="form-control" multiple required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim Revisi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function prosesApproval(tipe, id) {
            Swal.fire({
                title: 'Yakin?',
                text: `Anda akan ${tipe === 'terima_tu' ? 'meneruskan ke Kasubbag' : tipe === 'terima_kasubbag' ? 'menyetujui final' : 'menolak'} pengajuan ini`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan!'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/api/pengajuan-cuti/${id}/${tipe}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Sukses!', data.message, 'success').then(() => location.reload());
                            }
                        });
                }
            });
        }

        function batalkanPengajuan(id) {
            Swal.fire({
                title: 'Batalkan pengajuan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, batalkan!'
            }).then(res => {
                if (res.isConfirmed) {
                    fetch(`/api/pengajuan-cuti/${id}/batalkan`, {
                            method: 'POST'
                        })
                        .then(() => location.reload());
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
