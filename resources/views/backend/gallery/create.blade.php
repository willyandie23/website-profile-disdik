@extends('backend.layouts.app')

@section('title', 'Buat Galeri')

@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="galleryForm" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="title">Judul</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Gambar Galeri</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                            <p class="text-danger">* Ukuran maksimal file upload hanya 5MB</p>
                        </div>

                        <button type="submit" class="btn btn-success mt-3">Simpan Galeri</button>
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
        // Inisialisasi CKEditor
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo']
            })
            .then(editor => {
                editorInstance = editor;
            })
            .catch(err => console.error(err));

        // Submit form
        const form = document.getElementById('galleryForm');
        const url = "{{ route('gallery.store') }}";

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Sync CKEditor ke textarea
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
                    throw new Error(err.message || 'Gagal menyimpan galeri');
                }

                Swal.fire('Sukses!', 'Galeri berhasil dibuat', 'success')
                    .then(() => location.href = "{{ route('gallery.index') }}");

            } catch (error) {
                Swal.fire('Error!', error.message, 'error');
            }
        });
    });
</script>
@endpush