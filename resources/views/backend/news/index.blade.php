@extends('backend.layouts.app')

@section('title', 'Berita')

@push('css')
    <style>
        .description {
            cursor: pointer;
            padding: 8px 12px;
            background-color: #17a2b8;
            color: white;
            border-radius: 6px;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            max-width: 250px;
            transition: all 0.3s ease;
        }

        .description-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .description:hover {
            background-color: #138496;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .description:focus {
            outline: none;
        }

        /* Styling untuk konten HTML di modal */
        #modalDescriptionBody {
            line-height: 1.8;
            word-wrap: break-word;
            max-height: 60vh;
            overflow-y: auto;
        }

        #modalDescriptionBody p {
            margin-bottom: 1rem;
        }

        #modalDescriptionBody img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin: 10px 0;
        }

        #modalDescriptionBody ul,
        #modalDescriptionBody ol {
            padding-left: 20px;
            margin-bottom: 1rem;
        }

        #modalDescriptionBody a {
            color: #007bff;
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('news.create') }}" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Tambah Berita
                    </a>
                    <table id="news-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Gambar</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <!-- Modal -->
                    <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="descriptionModalLabel">Deskripsi Berita</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="modalDescriptionBody">
                                    <!-- Deskripsi lengkap akan ditampilkan di sini -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>

        $(document).ready(function () {
            // Inisialisasi DataTable
            const table = $('#news-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/news',
                    type: 'GET',
                    dataSrc: "data",
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', xhr.responseText);
                        let message = 'Failed to load data.';
                        if (xhr.status === 401) message = 'Unauthorized access. Please login.';
                        else if (xhr.status === 500) message = 'Server error. Please try again later.';
                        Swal.fire('Error!', message, 'error');
                    }
                },
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'author'
                    },
                    {
                        data: 'image',
                        render: function (data) {
                            return `<img src="${data}" width="100">`
                        }
                    },
                    {
                        data: 'description',
                        render: function (data) {
                            // Strip HTML tags untuk preview di tabel
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = data || '';
                            const plainText = tempDiv.textContent || tempDiv.innerText || '';
                            const truncated = plainText.length > 50 ? plainText.substring(0, 50) + '...' : plainText;

                            return `
                                <span class="description" data-description="${encodeURIComponent(data || '')}" title="Klik untuk melihat detail">
                                    <i class="fas fa-eye"></i>
                                    <span class="description-text">Lihat</span>
                                </span>`;
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                                <a href="/news/${row.id}/edit" class="btn btn-primary btn-sm my-1">Ubah</a>
                                <button class="btn btn-danger btn-sm delete-news" data-id="${row.id}">Hapus</button>
                            `;
                        }
                    }
                ],
                drawCallback: function () {
                    // Event listener for description clicks to show modal
                    const descriptionModal = new bootstrap.Modal(document.getElementById('descriptionModal'));
                    
                    $('.description').on('click', function() {
                        // Decode dan render sebagai HTML
                        const htmlContent = decodeURIComponent($(this).data('description'));
                        $('#modalDescriptionBody').html(htmlContent);
                        descriptionModal.show();
                    });

                    $('.delete-news').off('click').on('click', function () {
                        const newsId = $(this).data('id');
                        const apiUrl = `/api/news/${newsId}`;

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Berita ini akan dihapus permanen!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(apiUrl, {
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Authorization': `Bearer ${token}`
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            return response.json().then(err => {
                                                throw new Error(err.message || 'Network response was not ok');
                                            });
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        if (data.message === 'News deleted successfully') {
                                            Swal.fire('Berhasil!', 'Berita telah dihapus.', 'success').then(() => {
                                                table.ajax.reload();
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error deleting News:', error);
                                        Swal.fire('Gagal!', 'Gagal menghapus Berita: ' + error.message, 'error');
                                    });
                            }
                        });
                    });
                }
            });

            // Menambahkan event listener untuk modal close
            $('#descriptionModal').on('hidden.bs.modal', function () {
                $('#modalDescriptionBody').html(''); // Clear modal content when it is closed
            });
        });

    </script>
@endpush
