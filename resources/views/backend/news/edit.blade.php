@extends('backend.layouts.app')

@section('title', 'Perbaharui Berita')

@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="newsForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                    editor.setData(document.querySelector('#description').value);
                })
                .catch(error => {
                    console.error('CKEditor Error:', error);
                });

            // Submit form
            const form = document.getElementById('newsForm');
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

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
                        const fd = new FormData();
                        fd.append('_method', 'PUT');
                        fd.append('title', title);
                        fd.append('author', author);
                        fd.append('image', fileInput.files[0]);
                        fd.append('description', description);

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
                            title: title,
                            author: author,
                            description: description
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
                        // Response bukan JSON (HTML error page)
                        const text = await response.text();
                        console.error('Response bukan JSON:', text);
                        throw new Error('Server mengembalikan response yang tidak valid');
                    }

                    if (response.ok) {
                        Swal.fire('Sukses!', 'Berita berhasil diperbarui', 'success')
                            .then(() => location.href = '{{ route("news.index") }}');
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
