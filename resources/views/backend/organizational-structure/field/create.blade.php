@extends('backend.layouts.app')

@section('title', 'Buat Bidang')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="fieldsForm" method="POST" action="{{ route('field.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Bidang</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" rows="4"></textarea>
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
        const apiUrl = '/api/fields';

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('fieldsForm');
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

                // Send the data via fetch to the API
                fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify(data) // Ensure the data is sent as JSON
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
                        console.log('API Response:', data); // Log the response
                        if (data.message === 'Fields created successfully') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Bidang Berhasil Dibuat',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = '/organizational-structure/fields';
                            });
                        } else {
                            throw new Error('Response tidak valid');
                        }
                    })
                    .catch(error => {
                        console.error('Error creating Fields:', error);
                        alert('Gagal membuat Bidang: ' + error.message);
                    });
            });
        });
    </script>
@endpush