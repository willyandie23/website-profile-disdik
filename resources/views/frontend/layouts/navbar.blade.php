<!-- Navbar & Hero Start -->
<nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0 {{ Route::currentRouteName() !== 'home' ? 'opaque' : '' }}">
    <a href="{{ route('home.index') }}" class="navbar-brand p-0">
        <img src="" class="site_logo" height="150px" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0">
            <a href="{{ route('home.index') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}">Home</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Profil</a>
                <div class="dropdown-menu m-0">
                    <a href="{{ route('sambutan.index') }}" class="dropdown-item">Sambutan Kepala</a>
                    <a href="{{ route('organisasi.index') }}" class="dropdown-item">Struktur Organisasi</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Bidang</a>
                <div class="dropdown-menu m-0" id="fieldsDropdownMenu">
                    <!-- Bidang akan dimuat di sini secara dinamis -->
                </div>
            </div>
            <a href="{{ route('berita.index') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'frontend.news.index' ? 'active' : '' }}">Berita</a>
            <a href="{{ route('unduhan.index') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'frontend.download.index' ? 'active' : '' }}">Unduhan</a>
            <a href="{{ route('galeri.index') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'frontend.galery.index' ? 'active' : '' }}">Galeri</a>
            <a href="{{ route('hubungi.index') }}" class="nav-item nav-link {{ Route::currentRouteName() == 'frontend.contact.index' ? 'active' : '' }}">Hubungi Kami</a>
        </div>
    </div>
</nav>
<!-- Navbar & Hero End -->

@push('scripts')
    <script>
        $(document).ready(function() {
            // Mengambil data bidang dari API dan menampilkannya di dropdown
            $.ajax({
                url: '/api/fields',
                type: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        const fields = response.data;
                        let dropdownHtml = '';
                        
                        // Mengisi dropdown dengan nama bidang
                        fields.forEach(field => {
                            if (field.name !== 'Kepala Dinas') {  // Mengecek nama bidang
                                dropdownHtml += `
                                    <a href="/bidang/${field.id}" class="dropdown-item">
                                        ${field.name}
                                    </a>
                                `;
                            }
                        });

                        // Menambahkan list ke dropdown menu
                        $('#fieldsDropdownMenu').html(dropdownHtml);
                    } else {
                        console.log('Gagal memuat bidang');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching fields:', xhr.responseText);
                    Swal.fire('Error!', 'Gagal memuat data bidang.', 'error');
                }
            });
        });
    </script>
@endpush
