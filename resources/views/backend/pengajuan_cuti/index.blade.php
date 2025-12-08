@extends('backend.layouts.app')

@section('title', 'Daftar Pengajuan Cuti')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Pengajuan Cuti</h4>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-4 mb-2">
                            <label class="form-label fw-semibold">Filter Status</label>
                            <select id="filter-status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="draft">Draft</option>
                                <option value="diajukan">Diajukan</option>
                                <option value="sedang_diproses">Sedang Diproses</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label fw-semibold">Filter Jenis Cuti</label>
                            <select id="filter-jenis" class="form-select">
                                <option value="">Semua Jenis</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label fw-semibold">Filter Periode</label>
                            <select id="filter-periode" class="form-select">
                                <option value="">Semua Periode</option>
                                <option value="bulan-ini">Bulan Ini</option>
                                <option value="bulan-lalu">Bulan Lalu</option>
                                <option value="3-bulan">3 Bulan Terakhir</option>
                            </select>
                        </div>
                    </div>
                    <hr class="my-3">
                    <!-- Tambahkan wrapper dengan class table-responsive -->
                    <div class="table-responsive">
                        <table id="pengajuan-table" class="table table-striped table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama / NIP</th>
                                    <th>Jenis Cuti</th>
                                    <th>Tanggal Cuti</th>
                                    <th>Hari</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Load jenis cuti dari API
            loadJenisCuti();

            const table = $('#pengajuan-table').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                scrollX: true, // Tambahkan scroll horizontal
                autoWidth: false, // Nonaktifkan auto width
                ajax: {
                    url: '/api/pengajuan-cuti',
                    dataSrc: 'data'
                },
                columns: [{
                        data: null,
                        orderable: false,
                        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        data: 'kode_pengajuan',
                        render: data => `<strong>${data}</strong>`
                    },
                    {
                        data: null,
                        render: data => `
                    <div class="fw-bold">${data.nama_lengkap}</div>
                    <small class="text-muted">${data.nip}</small>
                `
                    },
                    {
                        data: 'jenis_cuti.nama',
                        defaultContent: '-'
                    },
                    {
                        data: null,
                        render: data => {
                            const mulai = new Date(data.tanggal_mulai).toLocaleDateString('id-ID');
                            const selesai = new Date(data.tanggal_selesai).toLocaleDateString(
                                'id-ID');
                            return `${mulai} → ${selesai}`;
                        }
                    },
                    {
                        data: 'jumlah_hari',
                        render: data => `<span class="badge bg-info fs-6">${data} hari</span>`
                    },
                    {
                        data: 'alasan_cuti',
                        render: data => data ? (data.length > 40 ? data.substring(0, 40) + '...' :
                            data) : '-'
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            const badgeMap = {
                                'draft': ['bg-secondary', 'Draft'],
                                'diajukan': ['bg-primary', 'Diajukan'],
                                'sedang_diproses': ['bg-warning text-dark', 'Diproses'],
                                'disetujui': ['bg-success', 'Disetujui'],
                                'ditolak': ['bg-danger', 'Ditolak'],
                                'selesai': ['bg-info', 'Selesai'],
                                'dibatalkan': ['bg-dark', 'Dibatalkan']
                            };

                            const [bg, text] = badgeMap[data] || ['bg-secondary', data];
                            return `<span class="badge ${bg} fw-semibold">${text}</span>`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row) {
                            let btn = '';

                            // Tombol Track History untuk melihat detail dan histori pengajuan
                            btn +=
                                `<button class="btn btn-outline-primary btn-sm btn-history me-1" data-id="${row.id}" title="Track History"><i class="fas fa-history"></i> History</button>`;

                            // Hanya draft yang bisa edit & hapus
                            if (row.status === 'draft') {
                                btn += `
                            <a href="/pengajuan-cuti/${row.id}/edit" class="btn btn-outline-warning btn-sm me-1" title="Edit"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-outline-danger btn-sm btn-delete" data-id="${row.id}" title="Hapus"><i class="fas fa-trash"></i></button>
                        `;
                            }

                            return `<div class="btn-group-sm">${btn}</div>`;
                        }
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                language: {
                    processing: "Memuat data...",
                    emptyTable: "Belum ada pengajuan cuti",
                    zeroRecords: "Tidak ditemukan data yang sesuai"
                }
            });

            // Fungsi untuk load jenis cuti dari API
            function loadJenisCuti() {
                fetch('/api/jenis-cuti')
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            const $select = $('#filter-jenis');
                            result.data.forEach(jenis => {
                                $select.append(`<option value="${jenis.nama}">${jenis.nama}</option>`);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading jenis cuti:', error);
                    });
            }

            // Custom search function untuk filter
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                // Ambil data row dari table
                const rowData = table.row(dataIndex).data();
                if (!rowData) return true;

                const filterStatus = $('#filter-status').val();
                const filterJenis = $('#filter-jenis').val();
                const filterPeriode = $('#filter-periode').val();

                // Filter status
                if (filterStatus && rowData.status !== filterStatus) {
                    return false;
                }

                // Filter jenis cuti
                if (filterJenis && rowData.jenis_cuti && rowData.jenis_cuti.nama !== filterJenis) {
                    return false;
                }

                // Filter periode
                if (filterPeriode) {
                    const now = new Date();
                    const tanggal = new Date(rowData.tanggal_pengajuan);

                    if (filterPeriode === 'bulan-ini') {
                        if (!(tanggal.getMonth() === now.getMonth() &&
                                tanggal.getFullYear() === now.getFullYear())) {
                            return false;
                        }
                    } else if (filterPeriode === 'bulan-lalu') {
                        const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1);
                        if (!(tanggal.getMonth() === lastMonth.getMonth() &&
                                tanggal.getFullYear() === lastMonth.getFullYear())) {
                            return false;
                        }
                    } else if (filterPeriode === '3-bulan') {
                        const threeMonthsAgo = new Date(now.getFullYear(), now.getMonth() - 3);
                        if (!(tanggal >= threeMonthsAgo)) {
                            return false;
                        }
                    }
                }

                return true;
            });

            // Event listener untuk filter
            $('#filter-status, #filter-jenis, #filter-periode').on('change', function() {
                table.draw();
            });

            // === Event Handler Tombol Aksi ===
            $('#pengajuan-table tbody').on('click', '.btn-history', function() {
                const id = $(this).data('id');
                window.location.href = `/pengajuan-cuti/${id}/track`;
            });

            $('#pengajuan-table tbody').on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus pengajuan?',
                    text: 'Pengajuan draft akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then(res => {
                    if (res.isConfirmed) {
                        fetch(`/api/pengajuan-cuti/${id}`, {
                                method: 'DELETE'
                            })
                            .then(r => r.ok ? r.json() : Promise.reject())
                            .then(() => {
                                Swal.fire('Terhapus!', '', 'success');
                                table.ajax.reload();
                            })
                            .catch(() => Swal.fire('Gagal!', 'Tidak bisa menghapus.', 'error'));
                    }
                });
            });
        });
    </script>
@endpush
</document_content>
