@extends('backend.layouts.app')

@section('title', 'Buat Berita')

@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="newsForm" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="title">Judul</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="author">Penulis</label>
                            <input type="text" name="author" id="author" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="image">Gambar Berita</label>
                            <input type="file" name="image" id="image" class="form-control" required>
                            <p class="text-danger">* Ukuran maksimal file upload hanya 5MB</p>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success mt-3">Simpan</button>
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
                    ]
                })
                .then(editor => {
                    editorInstance = editor;
                })
                .catch(error => console.error(error));

            const form = document.getElementById('newsForm');
            const url = "{{ route('news.store') }}";

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (editorInstance) {
                    document.getElementById('description').value = editorInstance.getData();
                }

                const formData = new FormData(form);

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                        },
                        body: formData
                    });

                    if (!res.ok) {
                        const err = await res.json();
                        throw new Error(err.message || 'Gagal menyimpan berita');
                    }

                    Swal.fire('Sukses!', 'Berita berhasil dibuat', 'success')
                        .then(() => location.href = "{{ route('news.index') }}");

                } catch (error) {
                    console.error(error);
                    Swal.fire('Error!', error.message, 'error');
                }
            });
        });
    </script>
@endpush
