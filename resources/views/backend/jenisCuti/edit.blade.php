@extends('backend.layouts.app')

@section('title', 'Perbaharui Jenis Cuti')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="jeniForm">
                        <div class="form-group">
                            <label for="name">Nama Jenis</label>
                            <input type="text" class="form-control" id="name" name="nama"
                                value="{{ $jenis->nama }}" required>
                        </div>
                        <div class="form-group">
                            <label for="maks_hari">Maksimal hari Cuti</label>
                            <input type="text" class="form-control" id="maks_hari" name="maks_hari"
                                value="{{ $jenis->maks_hari }}" required>
                        </div>
                        <div class="form-group">
                            <label for="butuh_surat_dokter">Butuh Surat Dokter</label>
                            <select name="butuh_surat_dokter" id="butuh_surat_dokter" class="form-control">
                                <option value="1" {{ $jenis->butuh_surat_dokter == 1 ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ $jenis->butuh_surat_dokter == 0 ? 'selected' : '' }}>Tidak
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="butuh_lampiran_tambahan">Butuh Lampiran Tambahan</label>
                            <select name="butuh_lampiran_tambahan" id="butuh_lampiran_tambahan" class="form-control">
                                <option value="1" {{ $jenis->butuh_lampiran_tambahan == 1 ? 'selected' : '' }}>Ya
                                </option>
                                <option value="0" {{ $jenis->butuh_lampiran_tambahan == 0 ? 'selected' : '' }}>Tidak
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ $jenis->keterangan }}
                            </textarea>
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
        const jenisId = "{{ $jenis->id }}";
        const apiUrl = `/api/jenis-cuti/${jenisId}`;

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('jeniForm');
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
                                throw new Error(err.status || 'Network response was not ok');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('API Response:', data);
                        if (data.status ===
                            'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Jenis berhasil diperbarui',
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
                        console.error('Error updating Jenis Cuti:', error);
                        alert('Gagal memperbarui Jenis: ' + error.status);
                    });
            });
        });
    </script>
@endpush
