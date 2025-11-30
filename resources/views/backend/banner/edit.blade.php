@extends('backend.layouts.app')

@section('title', 'Perbaharui Banner')

@section('content')

    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="bannerForm">
                        <div class="form-group">
                            <label for="title">Judul</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ $banners->title }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required>{{ $banners->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Gambar Saat Ini</label><br>
                            <img src="{{ $banners->image }}" width="150" alt="Banner Image" class="mb-3">
                        </div>

                        <div class="form-group">
                            <label for="image">Ubah Gambar Banner (opsional)</label>
                            <input
                                type="file"
                                name="image"
                                id="image"
                                class="form-control"
                            >
                            <p class="text-danger">* Ukuran maksimal file upload hanya 5MB dengan dimensi 1920x1080</p>
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
        document.getElementById('bannerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const fileInput    = document.getElementById('image');
            const title        = document.getElementById('title').value;
            const description  = document.getElementById('description').value;
            const apiUrl       = `/api/banner/{{ $banners->id }}`;

            if (fileInput.files.length) {
                const fd = new FormData();
                fd.append('_method', 'PUT');
                fd.append('title', title);
                fd.append('description', description);
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

            const payload = { title, description };
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
            if (response.ok && data.message === 'Banner updated successfully') {
                Swal.fire('Sukses!', 'Banner berhasil diperbarui.', 'success')
                .then(() => window.location.href = '{{ route("banner.index") }}');
            } else {
                Swal.fire('Error!', data.message || 'Update gagal', 'error');
            }
        }
    </script>
@endpush