@extends('backend.layouts.app')

@section('title', 'Bidang')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('field.create') }}" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </a>
                    <table id="field-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Bidang</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('#field-table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/fields',
                    type: 'GET',
                    dataSrc: "data", 
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', xhr.responseText);
                        let message = 'Failed to load data.';

                        // Handle different error codes
                        if (xhr.status === 401) {
                            message = 'Unauthorized access. Please login.';
                        } else if (xhr.status === 500) {
                            message = 'Server error. Please try again later.';
                        }

                        Swal.fire('Error!', message, 'error');
                    }
                },
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <a href="/organizational-structure/fields/${row.id}/edit" class="btn btn-primary btn-sm my-1">Ubah</a>
                                <button class="btn btn-danger btn-sm delete-field" data-id="${row.id}">Hapus</button>
                            `;
                        }
                    }
                ],
                drawCallback: function() {
                    $('.delete-field').off('click').on('click', function() {
                        const fieldId = $(this).data('id');
                        const apiUrl = `/api/fields/${fieldId}`;

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Bidang ini akan dihapus permanen!",
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
                                                throw new Error(err
                                                    .message ||
                                                    'Network response was not ok'
                                                );
                                            });
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        if (data.message ===
                                            'Fields deleted successfully') {
                                            Swal.fire(
                                                'Berhasil!',
                                                'Bidang telah dihapus.',
                                                'success'
                                            ).then(() => {
                                                table.ajax.reload();
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error deleting Fields:',
                                            error);
                                        Swal.fire(
                                            'Gagal!',
                                            'Gagal menghapus Bidang: ' + error
                                            .message,
                                            'error'
                                        );
                                    });
                            }
                        });
                    });
                }
            });
        });
    </script>
@endpush