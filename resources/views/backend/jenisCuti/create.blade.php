@extends('backend.layouts.app')

@section('title', 'Buat Jenis Cuti')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="jenisForm" method="POST" action="{{ route('jenis-cuti.create') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Jenis</label>
                            <input type="text" class="form-control" id="name" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="maks_hari">Maksimal hari Cuti</label>
                            <input type="text" class="form-control" id="maks_hari" name="maks_hari" required>
                        </div>
                        <div class="form-group">
                            <label for="butuh_surat_dokter">Butuh Surat Dokter</label>
                            <select name="butuh_surat_dokter" id="butuh_surat_dokter" class="form-control">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="butuh_lampiran_tambahan">Butuh Lampiran Tambahan</label>
                            <select name="butuh_lampiran_tambahan" id="butuh_lampiran_tambahan" class="form-control">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
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
        const apiUrl = '/api/jenis-cuti';
        // console.log(token)

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('jenisForm');
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
                                throw new Error(err.status || 'Network response was not ok');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('API Response:', data);
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Jenis Berhasil Dibuat',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = '/jenis-cuti';
                            });
                        } else {
                            throw new Error('Response tidak valid');
                        }
                    })
                    .catch(error => {
                        console.error('Error creating Jenis:', error);

                        alert('Gagal membuat Jenis: ' + error.status);

                    });
            });
        });
    </script>
@endpush
