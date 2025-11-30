@extends('backend.layouts.app')

@section('title', 'Perbaharui Berita')

@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="newsForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Tidak wajib karena kita pakai _method di FormData --}}

                        <div class="form-group">
                            <label for="title">Judul</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title', $news->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="author">Penulis</label>
                            <input type="text" class="form-control" id="author" name="author"
                                value="{{ old('author', $news->author) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Gambar Saat Ini</label><br>
                            <img src="{{ $news->image }}" width="200" alt="News Image" class="mb-3 img-thumbnail">
                        </div>

                        <div class="form-group">
                            <label for="image">Ubah Gambar Berita (opsional)</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            <p class="text-danger">* Ukuran maksimal 5MB, biarkan kosong jika tidak ingin ganti gambar</p>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control d-none">
                                {!! old('description', $news->description) !!}
                            </textarea>
                            {{-- CKEditor akan menggantikan textarea ini --}}
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="feather icon-save"></i> Update Berita
                        </button>
                        <a href="{{ route('news.index') }}" class="btn btn-secondary ml-2">Batal</a>
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
            // Inisialisasi CKEditor 5
            ClassicEditor
                .create(document.querySelector('#description'), {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'
                    ],
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Heading 3',
                                class: 'ck-heading_heading3'
                            }
                        ]
                    }
                })
                .then(editor => {
                    editorInstance = editor;

                    // Set isi editor dari data yang sudah ada (penting untuk edit!)
                    editor.setData(document.querySelector('#description').value);
                })
                .catch(error => {
                    console.error('CKEditor Error:', error);
                });

            // Submit form (hanya satu event!)
            const form = document.getElementById('newsForm');
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Sync data CKEditor ke textarea sebelum kirim
                if (editorInstance) {
                    document.getElementById('description').value = editorInstance.getData();
                }

                const fileInput = document.getElementById('image');
                const title = document.getElementById('title').value;
                const author = document.getElementById('author').value;
                const description = document.getElementById('description').value;
                const apiUrl = `/api/news/{{ $news->id }}`;

                let response;

                try {
                    if (fileInput.files.length > 0) {
                        // Ada file gambar baru → pakai FormData + spoof PUT
                        const fd = new FormData();
                        fd.append('_method', 'PUT');
                        fd.append('title', title);
                        fd.append('author', author);
                        fd.append('image', fileInput.files[0]);
                        fd.append('description', description);

                        response = await fetch(apiUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: fd
                        });
                    } else {
                        // Tidak ada gambar baru → kirim JSON biasa
                        const payload = {
                            title: title,
                            author: author,
                            description: description
                        };

                        response = await fetch(apiUrl, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: JSON.stringify(payload)
                        });
                    }

                    const result = await response.json();

                    if (response.ok) {
                        Swal.fire('Sukses!', 'Berita berhasil diperbarui', 'success')
                            .then(() => location.href = '{{ route('news.index') }}');
                    } else {
                        Swal.fire('Gagal!', result.message || 'Terjadi kesalahan', 'error');
                    }

                } catch (error) {
                    console.error(error);
                    Swal.fire('Error!', 'Tidak dapat terhubung ke server', 'error');
                }
            });
        });
    </script>
@endpush
