@extends('backend.layouts.app')

@section('title', 'Buat Bidang')

@section('content')
<div class="row">
    <div class="col-md-6 col-xl-12">
        <div class="card">
            <div class="card-body">
                <form id="fieldsForm" method="POST" action="{{ route('field.store') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">Nama Bidang</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" class="ckeditor">{!! old('description') !!}</textarea>
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
    ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo' ]
        })
        .then(editor => {
            const form = document.getElementById('fieldsForm');
            form.addEventListener('submit', () => {
                editor.updateSourceElement();
            });
        })
        .catch(error => console.error(error));
</script>
<script>
    const apiUrl = '/api/fields';
    // script submit Anda tetap sama (tidak berubah)
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('fieldsForm');
        if (!form) return;

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => data[key] = value);

            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) return response.json().then(err => { throw new Error(err.message || 'Error'); });
                return response.json();
            })
            .then(data => {
                if (data.message === 'Fields created successfully') {
                    Swal.fire('Success!', 'Bidang Berhasil Dibuat', 'success')
                        .then(() => location.href = '/organizational-structure/fields');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Gagal membuat Bidang: ' + error.message);
            });
        });
    });
</script>
@endpush