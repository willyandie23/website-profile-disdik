@extends('backend.layouts.app')

@section('title', 'Identitas Website')

@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="identityForm" method="POST" action="{{ route('identity.store') }}" enctype="multipart/form-data">
                        @csrf
                        <h3>Identitas Umum</h3>
                        <div class="form-group mb-3 mt-3">
                            <label for="site_heading">Judul Website</label>
                            <input type="text" class="form-control" id="site_heading" name="site_heading" required>
                        </div>
                        <div class="form-group mb-3 mt-3">
                            <label for="site_ytb">YouTube</label>
                            <input type="text" class="form-control" id="site_ytb" name="site_ytb" value="{{ old('site_ytb', $identity->site_ytb ?? '') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="site_logo_input">Logo Website</label>
                                    <input type="file" class="form-control" id="site_logo_input" name="site_logo" accept="image/jpeg,image/png,image/gif,image/svg+xml">
                                    <p class="mt-2">Preview:</p>
                                    <img id="site_logo_preview" src="" alt="Logo Preview" style="max-width: 250px; display: none;" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="site_favicon_input">Favicon Website</label>
                                    <input type="file" class="form-control" id="site_favicon_input" name="site_favicon" accept="image/jpeg,image/png,image/gif,image/svg+xml">
                                    <p class="mt-2">Preview:</p>
                                    <img id="site_favicon_preview" src="" alt="Favicon Preview" style="max-width: 75px; display: none;" />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h3>Kontak Website</h3>
                                <div class="form-group mb-3 mt-3">
                                    <label for="cp_address">Alamat</label>
                                    <input type="text" class="form-control" id="cp_address" name="cp_address">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="cp_phone">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="cp_phone" name="cp_phone">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="cp_email">Email</label>
                                    <input type="email" class="form-control" id="cp_email" name="cp_email">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="cp_agency">Nama Instansi</label>
                                    <input type="text" class="form-control" id="cp_agency" name="cp_agency">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h3>Sosial Media</h3>
                                <div class="form-group mb-3 mt-3">
                                    <label for="sm_facebook">Facebook</label>
                                    <input type="url" class="form-control" id="sm_facebook" name="sm_facebook">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="sm_instagram">Instagram</label>
                                    <input type="url" class="form-control" id="sm_instagram" name="sm_instagram">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="sm_x">TikTok</label>
                                    <input type="url" class="form-control" id="sm_x" name="sm_x">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="sm_youtube">Youtube</label>
                                    <input type="url" class="form-control" id="sm_youtube" name="sm_youtube">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const form = $('#identityForm');
            const siteHeadingInput = $('#site_heading');
            const siteYtbInput = $('#site_ytb');
            const siteLogoInput = $('#site_logo_input');
            const siteLogoPreview = $('#site_logo_preview');
            const siteFaviconInput = $('#site_favicon_input');
            const siteFaviconPreview = $('#site_favicon_preview');
            const contactAddressInput = $('#cp_address');
            const contactPhoneInput = $('#cp_phone');
            const contactEmailInput = $('#cp_email');
            const contactAgencyInput = $('#cp_agency');
            const socialFacebookInput = $('#sm_facebook');
            const socialInstagramInput = $('#sm_instagram');
            const socialXInput = $('#sm_x');
            const socialYoutubeInput = $('#sm_youtube');

            // Debugging: Periksa apakah siteLogoInput ditemukan
            // console.log('siteLogoInput:', siteLogoInput);
            if (siteLogoInput.length === 0) {
                // console.error('Element with ID site_logo not found');
                Swal.fire('Error!', 'Input file for logo not found.', 'error');
                return;
            }
            // console.log('siteFaviconInput:', siteFaviconInput);
            if (siteFaviconInput.length === 0) {
                // console.error('Element with ID site_favicon not found');
                Swal.fire('Error!', 'Input file for favicon not found.', 'error');
                return;
            }

            // Preview gambar
            siteLogoInput.on('change', function() {
                const file = this.files[0];
                // console.log('Selected file:', file); // Debugging
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        siteLogoPreview.attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);

                    // Validasi ukuran file (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire('Error!', 'File size exceeds 2MB.', 'error');
                        this.value = '';
                        siteLogoPreview.hide();
                    }
                } else {
                    siteLogoPreview.hide();
                }
            });
            // Preview gambar
            siteFaviconInput.on('change', function() {
                const file = this.files[0];
                // console.log('Selected file:', file); // Debugging
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        siteFaviconPreview.attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);

                    // Validasi ukuran file (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire('Error!', 'File size exceeds 2MB.', 'error');
                        this.value = '';
                        siteFaviconPreview.hide();
                    }
                } else {
                    siteFaviconPreview.hide();
                }
            });

            // Mengambil data saat load page
            $.ajax({
                url: '/api/identities',
                type: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        siteHeadingInput.val(response.data.site_heading || '');
                        siteYtbInput.val(response.data.site_ytb || '');
                        if (response.data.site_logo) {
                            siteLogoPreview.attr('src', response.data.site_logo).show();
                        }
                        if (response.data.site_favicon) {
                            siteFaviconPreview.attr('src', response.data.site_favicon).show();
                        }
                        contactAddressInput.val(response.data.cp_address || '');
                        contactPhoneInput.val(response.data.cp_phone || '');
                        contactEmailInput.val(response.data.cp_email || '');
                        contactAgencyInput.val(response.data.cp_agency || '');
                        socialFacebookInput.val(response.data.sm_facebook || '');
                        socialInstagramInput.val(response.data.sm_instagram || '');
                        socialXInput.val(response.data.sm_x || '');
                        socialYoutubeInput.val(response.data.sm_youtube || '');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching identity data:', xhr.responseText);
                    Swal.fire('Error!', 'Failed to load identity data.', 'error');
                }
            });

            // Handle form submission
            form.on('submit', function(event) {
                event.preventDefault();

                // Validasi sederhana
                if (!siteHeadingInput.val()) {
                    Swal.fire('Error!', 'Website title is required.', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('site_heading', siteHeadingInput.val());
                formData.append('site_ytb', siteYtbInput.val());
                formData.append('cp_address', contactAddressInput.val());
                formData.append('cp_phone', contactPhoneInput.val());
                formData.append('cp_email', contactEmailInput.val());
                formData.append('cp_agency', contactAgencyInput.val());
                formData.append('sm_facebook', socialFacebookInput.val());
                formData.append('sm_instagram', socialInstagramInput.val());
                formData.append('sm_x', socialXInput.val());
                formData.append('sm_youtube', socialYoutubeInput.val());

                // Perbaikan: Gunakan siteLogoInput[0] dengan pengecekan
                const fileInput = siteLogoInput[0]; // Ambil elemen DOM
                if (fileInput && fileInput.files && fileInput.files[0]) {
                    formData.append('site_logo', fileInput.files[0]);
                } else {
                    console.log('No file selected for site_logo');
                }
                const fileInput2 = siteFaviconInput[0]; // Ambil elemen DOM
                if (fileInput2 && fileInput2.files && fileInput2.files[0]) {
                    formData.append('site_favicon', fileInput2.files[0]);
                } else {
                    console.log('No file selected for site_favicon');
                }

                $.ajax({
                    url: '/api/identities',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Identitas Website Berhasil Disimpan',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.error('Error saving identity data:', xhr.responseText);
                        Swal.fire('Error!', 'Failed to save identity data.', 'error');
                    }
                });
            });
        });
    </script>
@endpush
