@extends('backend.layouts.app')

@section('title', 'Perbaharui Galeri')

@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="galleryForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">Judul</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title', $gallerys->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control d-none">
                                {!! old('description', $gallerys->description) !!}
                            </textarea>
                        </div>

                        <div class="form-group">
                            <label>Gambar Saat Ini</label><br>
                            <img src="{{ $gallerys->image }}" width="250" class="img-thumbnail mb-3" alt="Current Image">
                        </div>

                        <div class="form-group">
                            <label for="image">Ubah Gambar (opsional)</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <p class="text-danger">* Maksimal 5MB, biarkan kosong jika tidak ingin ganti</p>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Galeri</button>
                        <a href="{{ route('gallery.index') }}" class="btn btn-secondary ml-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let editorInstance;

    document.addEventListener('DOMContentLoaded', () => {
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'undo', 'redo']
            })
            .then(editor => {
                editorInstance = editor;
                editor.setData(document.querySelector('#description').value);
            })
            .catch(err => console.error(err));

        const form = document.getElementById('galleryForm');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Sync CKEditor
            if (editorInstance) {
                document.getElementById('description').value = editorInstance.getData();
            }

            const fileInput = document.getElementById('image');
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const apiUrl = `/api/galery/{{ $gallerys->id }}`;

            try {
                let response;

                if (fileInput.files.length > 0) {
                    // ADA FILE → PAKAI FormData + POST + _method=PUT
                    const fd = new FormData();
                    fd.append('_method', 'PUT');
                    fd.append('title', title);
                    fd.append('description', description);
                    fd.append('image', fileInput.files[0]);

                    response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            // JANGAN SET Content-Type! Biarkan browser yang atur (multipart/form-data)
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': `Bearer ${token}`
                        },
                        body: fd
                    });

                } else {
                    // TIDAK ADA FILE → pakai PUT langsung (bisa JSON)
                    response = await fetch(apiUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify({
                            title: title,
                            description: description
                        })
                    });
                }

                // Cek response
                const contentType = response.headers.get('content-type');
                let result;

                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    // Kalau Laravel balikin HTML error (misal 405), tampilkan sebagai text
                    const text = await response.text();
                    throw new Error('Server error: ' + response.status + ' ' + text.substring(0, 200));
                }

                if (response.ok) {
                    Swal.fire('Sukses!', 'Galeri berhasil diperbarui', 'success')
                        .then(() => location.href = '{{ route("gallery.index") }}');
                } else {
                    Swal.fire('Gagal!', result.message || 'Terjadi kesalahan', 'error');
                }

            } catch (error) {
                console.error(error);
                Swal.fire('Error!', error.message || 'Tidak dapat terhubung ke server', 'error');
            }
        });
    });
</script>
@endpush