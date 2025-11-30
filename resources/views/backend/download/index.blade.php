@extends('backend.layouts.app')

@section('title', 'Unduhan')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('download.create') }}" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Tambah File PDF
                    </a>
                    <table id="downloads-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>File Name</th>
                                <th>Total Download</th>
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
            const table = $('#downloads-table').DataTable({
                processing: true,
                serverSide: false, // Menampilkan data tanpa me-reload halaman
                ajax: {
                    url: '/api/downloads', // Endpoint API untuk mengambil data
                    type: 'GET',
                    dataSrc: "data", // Menunjukkan data yang diterima dari server
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', xhr.responseText);
                        Swal.fire('Error!', 'Gagal memuat data.', 'error');
                    }
                },
                columns: [
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'file_name' },
                    { data: 'total_download' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-danger btn-sm delete-file" data-id="${row.id}">Hapus</button>
                                <a href="${row.file_path}" class="btn btn-info btn-sm my-1 download-btn" data-id="${row.id}" target="_blank">Download</a>
                            `;
                        }
                    }
                ],
                drawCallback: function() {
                    // Handle button delete click with AJAX
                    $('.delete-file').off('click').on('click', function() {
                        const fileId = $(this).data('id');
                        const apiUrl = `/api/downloads/${fileId}`;

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "File ini akan dihapus permanen!",
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
                                            'File deleted successfully') {
                                            Swal.fire(
                                                'File!',
                                                'File telah dihapus.',
                                                'success'
                                            ).then(() => {
                                                table.ajax.reload();
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error deleting File:',
                                            error);
                                        Swal.fire(
                                            'Gagal!',
                                            'Gagal menghapus File: ' +
                                            error
                                            .message,
                                            'error'
                                        );
                                    });
                            }
                        });
                    });
                    $('.download-btn').on('click', function() {
                        const fileId = $(this).data('id');
                        
                        // Increment the download counter first
                        $.ajax({
                            url: `/api/downloads/${fileId}/download`,
                            type: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            success: function(response) {
                                // Setelah increment berhasil, redirect ke URL download
                                table.ajax.reload();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error!', 'Gagal memperbarui jumlah download.', 'error');
                            }
                        });
                    });
                }
            });
        });
    </script>
@endpush
