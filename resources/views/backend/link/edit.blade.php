@extends('backend.layouts.app')

@section('title', 'Perbaharui Link')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="linkForm">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $links->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="text" class="form-control" id="link" name="link"
                                value="{{ $links->link }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const linkId = "{{ $links->id }}";
        const apiUrl = `/api/links/${linkId}`;

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('linkForm');
            if (!form) {
                console.error('Form tidak ditemukan');
                return;
            }

            if (!token) {
                console.error('API token tidak tersedia');
                alert('Silakan login kembali untuk mendapatkan token');
                return;
            }

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                fetch(apiUrl, {
                        method: 'PUT', // Use PUT for updating
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify(data)
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
                        console.log('API Response:', data);
                        if (data.message ===
                            'Links updated successfully') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Link berhasil diperbarui',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = '/link';
                            });
                        } else {
                            throw new Error('Response tidak valid');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating Links:', error);
                        alert('Gagal memperbarui Link: ' + error.message);
                    });
            });
        });
    </script>
@endpush