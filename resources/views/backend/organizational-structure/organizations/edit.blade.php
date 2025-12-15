@extends('backend.layouts.app')

@section('title', 'Perbaharui Anggota')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="organizationForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $organizations->name) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="position">Posisi</label>
                            <input type="text" class="form-control" id="position" name="position"
                                value="{{ old('position', $organizations->position) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="NIP">NIP</label>
                            <input type="text" class="form-control" id="NIP" name="NIP"
                                value="{{ old('NIP', $organizations->NIP) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="field_id">Bidang</label>
                            <select class="form-control" id="field_id" name="field_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($fields as $field)
                                    <option value="{{ $field->id }}"
                                        @if ($organizations->field_id == $field->id) 
                                            selected 
                                        @endif>
                                        {{ $field->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="level">Level Jabatan <small class="text-muted">(Pilih sesuai struktur)</small></label>
                            
                            <select name="level" id="level" class="form-control select2" required style="width: 100%;">
                                <option value="">-- Pilih Level --</option>
                                
                                <option value="1" 
                                    data-subtitle="Kepala Dinas" 
                                    {{ old('level', $organizations->level) == 1 ? 'selected' : '' }}>
                                    Level 1 - Kepala Dinas
                                </option>
                                
                                <option value="2" 
                                    data-subtitle="Sekretaris Dinas" 
                                    {{ old('level', $organizations->level) == 2 ? 'selected' : '' }}>
                                    Level 2 - Sekretaris Dinas
                                </option>
                                
                                <option value="3" 
                                    data-subtitle="Kepala Bidang" 
                                    {{ old('level', $organizations->level) == 3 ? 'selected' : '' }}>
                                    Level 3 - Kepala Bidang
                                </option>
                                
                                <option value="4" 
                                    data-subtitle="Kasubag/Jabatan Fungsional" 
                                    {{ old('level', $organizations->level) == 4 ? 'selected' : '' }}>
                                    Level 4 - Kasubag/Jabatan Fungsional
                                </option>
                                
                                <option value="5" 
                                    data-subtitle="Kepala Seksi (Kasi)" 
                                    {{ old('level', $organizations->level) == 5 ? 'selected' : '' }}>
                                    Level 5 - Kepala Seksi (Kasi)
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Gambar Saat Ini</label><br>
                            @if($organizations->image)
                                <img src="{{ $organizations->image }}" width="150" alt="Organization Image" class="mb-3 img-thumbnail">
                            @else
                                <p class="text-muted">Tidak ada gambar</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="image">Ubah Gambar Anggota (opsional)</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <p class="text-danger">* Ukuran maksimal 5MB, biarkan kosong jika tidak ingin ganti gambar</p>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="feather icon-save"></i> Update Anggota
                        </button>
                        <a href="{{ route('organizations.index') }}" class="btn btn-secondary ml-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('organizationForm');
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const fileInput = document.getElementById('image');
                const name = document.getElementById('name').value;
                const position = document.getElementById('position').value;
                const nip = document.getElementById('NIP').value;
                const field_id = document.getElementById('field_id').value;
                const level = document.getElementById('level').value;
                const apiUrl = `/api/organizations/{{ $organizations->id }}`;

                let response;

                try {
                    if (fileInput.files.length > 0) {
                        // Ada file gambar → pakai FormData dengan method spoofing
                        const fd = new FormData();
                        fd.append('_method', 'PUT');
                        fd.append('name', name);
                        fd.append('position', position);
                        fd.append('NIP', nip);
                        fd.append('field_id', field_id);
                        fd.append('level', level);
                        fd.append('image', fileInput.files[0]);

                        response = await fetch(apiUrl, {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            },
                            body: fd
                        });
                    } else {
                        const payload = {
                            name: name,
                            position: position,
                            NIP: nip,
                            field_id: field_id,
                            level: level
                        };

                        response = await fetch(apiUrl, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: JSON.stringify(payload)
                        });
                    }

                    // Parse response dengan error handling
                    let result;
                    const contentType = response.headers.get('content-type');
                    
                    if (contentType && contentType.includes('application/json')) {
                        result = await response.json();
                    } else {
                        const text = await response.text();
                        console.error('Response bukan JSON:', text);
                        throw new Error('Server mengembalikan response yang tidak valid');
                    }

                    if (response.ok) {
                        Swal.fire('Sukses!', 'Anggota berhasil diperbarui', 'success')
                            .then(() => window.location.href = '{{ route("organizations.index") }}');
                    } else {
                        Swal.fire('Gagal!', result.message || 'Terjadi kesalahan', 'error');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('Error!', error.message || 'Tidak dapat terhubung ke server', 'error');
                }
            });
        });
    </script>
@endpush
