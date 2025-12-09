@extends('backend.layouts.app')

@section('title', 'Perbaharui Anggota')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="organizationForm">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $organizations->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="position">Posisi</label>
                            <input type="text" class="form-control" id="position" name="position"
                                value="{{ $organizations->position }}" required>
                        </div>
                        <div class="form-group">
                            <label for="NIP">NIP</label>
                            <input type="text" class="form-control" id="NIP" name="NIP"
                                value="{{ $organizations->NIP }}" required>
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
                            <label for="image">Ubah Gambar Anggota (opsional)</label>
                            <input
                                type="file"
                                name="image"
                                id="image"
                                class="form-control"
                            >
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
        document.getElementById('organizationForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const fileInput  = document.getElementById('image');
            const name       = document.getElementById('name').value;
            const position   = document.getElementById('position').value;
            const nip        = document.getElementById('NIP').value;
            const field_id   = document.getElementById('field_id').value;;
            const apiUrl     = `/api/organizations/{{ $organizations->id }}`;

            if (fileInput.files.length) {
                const fd = new FormData();
                fd.append('_method', 'PUT');
                fd.append('name', name);
                fd.append('position', position);
                fd.append('NIP', nip);
                fd.append('field_id', field_id);
                fd.append('image', fileInput.files[0]);

                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: fd
                });

                return handleResponse(res);
            }

            const payload = {
                name: name,
                position: position,
                NIP: nip,
                field_id: field_id
            };
            const res = await fetch(apiUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(payload)
            });

            handleResponse(res);
        });

        async function handleResponse(response) {
            const data = await response.json();
            if (response.ok && data.message === 'Organization updated successfully') {
                Swal.fire('Sukses!', 'Anggota berhasil diperbarui.', 'success')
                .then(() => window.location.href = '{{ route("organizations.index") }}');
            } else {
                Swal.fire('Error!', data.message || 'Update gagal', 'error');
            }
        }
    </script>
@endpush