{{-- resources/views/frontend/cuti/partials/list-pengajuan.blade.php --}}
<div class="mt-5">
    <h4 class="fw-bold text-primary mb-4">Daftar Pengajuan Cuti Anda</h4>
    <div class="row g-4">
        @foreach ($pengajuanList as $pengajuan)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">{{ $pengajuan->kode_pengajuan }}</h5>
                        <p class="mb-2"><strong>Jenis Cuti:</strong> {{ $pengajuan->jenisCuti->nama }}</p>
                        <p class="mb-2"><strong>Periode:</strong>
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} →
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}</p>
                        <p class="mb-3"><strong>Status:</strong>
                            <span
                                class="badge
                                @if ($pengajuan->status == 'diajukan') bg-warning text-dark
                                @elseif($pengajuan->status == 'sedang_diproses') bg-info
                                @elseif($pengajuan->status == 'disetujui') bg-success
                                @elseif($pengajuan->status == 'ditolak') bg-danger
                                @else bg-secondary @endif">
                                {{ ucwords(str_replace('_', ' ', $pengajuan->status)) }}
                            </span>
                        </p>
                        <button class="btn btn-primary w-100 rounded-pill"
                            onclick="trackByKode('{{ $pengajuan->kode_pengajuan }}')">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
