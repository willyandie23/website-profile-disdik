@extends('backend.layouts.app')

@section('title', 'Perbaharui Bidang')

@section('content')
<div class="row">
    <div class="col-md-6 col-xl-12">
        <div class="card">
            <div class="card-body">
                <form id="fieldForm">
                    <div class="form-group mb-3">
                        <label for="name">Nama Bidang</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $fields->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description" class="ckeditor">{!! $fields->description !!}</textarea>
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
            const form = document.getElementById('fieldForm');
            form.addEventListener('submit', () => {
                editor.updateSourceElement();
            });
        })
        .catch(error => console.error(error));
</script>
<script>
    const fieldId = "{{ $fields->id }}";
    const apiUrl = `/api/fields/${fieldId}`;

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('fieldForm');
        if (!form) return;

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => data[key] = value);

            fetch(apiUrl, {
                method: 'PUT',
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
                if (data.message === 'Fields updated successfully') {
                    Swal.fire('Success!', 'Bidang berhasil diperbarui', 'success')
                        .then(() => location.href = '/organizational-structure/fields');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Gagal memperbarui Bidang: ' + error.message);
            });
        });
    });
</script>
@endpush