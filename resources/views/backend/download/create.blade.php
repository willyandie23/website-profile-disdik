@extends('backend.layouts.app')

@section('title', 'Buat Unduhan')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file_name">Nama File</label>
                            <input type="text" name="file_name" id="file_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="file">Pilih File PDF</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                            <p class="text-danger">* Ukuran maksimal file upload hanya 5MB dengan format PDF</p>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#uploadForm').submit(function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: '{{ route('download.store') }}', // Endpoint untuk menyimpan file
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire('Sukses!', 'File berhasil diupload', 'success')
                            .then(() => window.location.href = '{{ route('download.index') }}');
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error!', 'Gagal mengupload file', 'error');
                    }
                });
            });
        });
    </script>
@endpush
