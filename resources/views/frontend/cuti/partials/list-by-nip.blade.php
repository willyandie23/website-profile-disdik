{{-- resources/views/frontend/cuti/partials/list-by-nip.blade.php --}}
<div class="mt-5 p-4 bg-light rounded-4 shadow-sm">
    <h4 class="fw-bold text-primary mb-4">
        Daftar Pengajuan Cuti Anda ({{ $list->count() }} pengajuan)
    </h4>
    <div class="row g-4">
        @foreach ($list as $p)
            <div class="col-lg-6">
                <div class="card border-0 shadow h-100 hover-shadow">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-primary">{{ $p->kode_pengajuan }}</h5>
                        <p class="mb-1"><strong>Jenis:</strong> {{ $p->jenisCuti->nama }}</p>
                        <p class="mb-1"><strong>Periode:</strong>
                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }} →
                            {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                        </p>
                        <p class="mb-3"><strong>Status:</strong>
                            <span
                                class="badge
                            @if ($p->status == 'diajukan') bg-warning text-dark
                            @elseif($p->status == 'disetujui') bg-success
                            @elseif($p->status == 'ditolak') bg-danger
                            @else bg-secondary @endif px-3 py-2">
                                {{ ucwords(str_replace('_', ' ', $p->status)) }}
                            </span>
                        </p>
                        <button class="btn btn-primary w-100 rounded-pill"
                            onclick="bukaDetail('{{ $p->kode_pengajuan }}')">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
