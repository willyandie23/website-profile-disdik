@extends('backend.layouts.app')

@section('title', 'Perbaharui Bidang')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="fieldForm">
                        <div class="form-group">
                            <label for="name">Nama Bidang</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $fields->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required>{{ $fields->description }}</textarea>
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
        const fieldId = "{{ $fields->id }}";
        const apiUrl = `/api/fields/${fieldId}`;

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('fieldForm');
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
                            'Fields updated successfully') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Bidang berhasil diperbarui',
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
                        console.error('Error updating Fields:', error);
                        alert('Gagal memperbarui Bidang: ' + error.message);
                    });
            });
        });
    </script>
@endpush