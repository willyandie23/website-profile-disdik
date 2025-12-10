@extends('frontend.layouts.app')

@push('css')
    <style>
        .video-container {
            width: 100%;
            max-width: 100%;
            height: 500px;
            /* Set your desired height */
        }

        .video-container iframe {
            width: 100%;
            height: 100%;
        }

        .section-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: "";
            position: absolute;
            width: 50%;
            height: 3px;
            background-color: #007bff;
            bottom: 0;
            left: 0;
        }

        .team .container-fluid .row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .team-item {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-dialog-scrollable .modal-content {
            overflow: visible !important;
            /* Override overflow-hidden */
        }

        .modal-dialog-scrollable .modal-body {
            max-height: 70vh !important;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch;
            /* Smooth scroll di iOS */
            padding-bottom: 20px;
            /* Extra ruang biar ga ketutup footer */
        }

        /* Pastikan modal tidak terpotong di layar kecil */
        @media (max-width: 576px) {
            .modal-dialog-scrollable .modal-body {
                max-height: 60vh !important;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Banner Section Start -->
    <div class="carousel-header">
        <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
            <ol class="carousel-indicators">
                @foreach ($banners as $key => $banner)
                    <li data-bs-target="#carouselId" data-bs-slide-to="{{ $key }}"
                        class="{{ $key == 0 ? 'active' : '' }}"></li>
                @endforeach
            </ol>
            <div class="carousel-inner" role="listbox">
                @foreach ($banners as $key => $banner)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ $banner->image }}" class="img-fluid w-100" alt="{{ $banner->title }}">
                        <div class="carousel-caption">
                            <div class="carousel-caption-content" style="max-width: 1000px;">
                                <h1 class="display-2 text-capitalize text-white mb-4 fadeInLeft animated"
                                    data-animation="fadeInLeft" data-delay="1.3s" style="animation-delay: 1.3s;">
                                    {{ $banner->title }}</h1>
                                <p class="mb-5 fs-5 text-white fadeInLeft animated" data-animation="fadeInLeft"
                                    data-delay="1.5s" style="animation-delay: 1.5s;">{{ $banner->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                <span class="carousel-control-prev-icon btn btn-primary fadeInLeft animated" aria-hidden="true"
                    data-animation="fadeInLeft" data-delay="1.1s" style="animation-delay: 1.3s;"> <i
                        class="fa fa-angle-left fa-3x"></i></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                <span class="carousel-control-next-icon btn btn-primary fadeInRight animated" aria-hidden="true"
                    data-animation="fadeInLeft" data-delay="1.1s" style="animation-delay: 1.3s;"><i
                        class="fa fa-angle-right fa-3x"></i></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Banner Section End -->

    {{-- FORM TRACKING – VERSI FINAL, RAPI, ELEGANT, BUTTON PAS, TIDAK KEGEDEAN --}}
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="border-top: 7px solid #667eea;">
                    <div class="card-body p-5">

                        <!-- JUDUL -->
                        <div class="text-center mb-5">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-4"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-search-location fa-2x"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">Lacak Pengajuan Cuti</h3>
                            <p class="text-muted">Gunakan Kode Pengajuan atau NIP Anda</p>
                        </div>

                        <!-- INPUT + TOMBOL CARI (SEIMBANG & RAPI) -->
                        <div class="row g-3 align-items-center mb-5">
                            <div class="col-12 col-md-9">
                                <input type="text" id="inputCari"
                                    class="form-control form-control-lg rounded-pill text-center fw-medium shadow-sm border-0"
                                    placeholder="Masukkan kode atau NIP" style="height: 58px; font-size: 1.1rem;">
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        Contoh: <span class="fw-bold text-primary">CT-2025-000005</span> •
                                        <span class="fw-bold text-primary">197812312004012003</span>
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <button id="btnCari" class="btn btn-primary w-100 h-100 rounded-pill shadow-sm fw-bold"
                                    style="height: 58px; font-size: 1.1rem;">
                                    Cari
                                </button>
                            </div>
                        </div>

                        <!-- TOMBOL PENGAJUAN -->
                        <div class="text-center pt-4 border-top">
                            <p class="text-muted mb-3 fw-medium">Ingin mengajukan cuti baru?</p>
                            <button type="button" class="btn btn-success btn-lg px-5 rounded-pill shadow fw-bold"
                                data-bs-toggle="modal" data-bs-target="#modalPengajuanCuti">
                                Ajukan Cuti Sekarang
                            </button>
                        </div>

                        <!-- HASIL CARI (UNTUK NIP) -->
                        <div id="hasilCari" class="mt-5"></div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- News and Download Section Start -->
    <div class="container-fluid blog pb-5">
        <div class="container pb-5">
            <div class="row">
                <!-- News Section (Left Column) -->
                <div class="col-md-12 col-lg-6"> <!-- Mengurangi ukuran kolom berita -->
                    <div class="text-left mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                        <h2 class="section-title text-uppercase text-black mt-5">Berita Dinas Pendidikan</h2>
                    </div>
                    <div class="row g-4 justify-content-center">
                        @foreach ($latestNews as $news)
                            <div class="col-md-12 wow fadeInUp" data-wow-delay="0.2s">
                                <div class="blog-item">
                                    <div class="blog-img">
                                        <img src="{{ $news->image }}" class="img-fluid rounded-top w-100"
                                            alt="{{ $news->title }}" style="object-fit: cover; height: 300px;">
                                        <div class="blog-date px-4 py-2"><i class="fa fa-calendar-alt me-1"></i>
                                            {{ $news->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="blog-content rounded-bottom p-4">
                                        <a href="#" class="h5 d-inline-block mb-3 fw-bold">
                                            {{ \Str::limit($news->title, 60) }}
                                        </a>

                                        <div class="text-muted small">
                                            {!! \Str::limit(html_entity_decode(strip_tags($news->description)), 150, '...') !!}

                                            @if (strip_tags($news->description) !== \Str::limit(html_entity_decode(strip_tags($news->description)), 150, ''))
                                                <a href="{{ route('berita.show', $news->slug ?? $news->id) }}"
                                                    class="fw-bold text-secondary d-inline-block mt-2">
                                                    Selengkapnya <i class="fa fa-angle-right"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Downloads Section (Right Column) -->
                <div class="col-md-12 col-lg-6">
                    <div class="text-left mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                        <h2 class="section-title text-uppercase text-black mt-5">Unduh</h2>
                    </div>
                    <ul class="list-group wow fadeInUp" data-wow-delay="0.2s"">
                        @foreach ($latestDownloads as $download)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ $download->file_path }}" class="text-decoration-none" download>
                                    <i class="fa fa-file-pdf"></i> {{ $download->file_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Video Section -->
                    <div>
                        <div class="text-left mx-auto pb-3 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                            <h2 class="section-title text-uppercase text-black mt-5">Video</h2>
                        </div>
                        <!-- Embed YouTube Video -->
                        <div class="video-container">
                            {{-- <iframe src="" allowfullscreen></iframe> --}}
                            <iframe src="https://www.youtube.com/embed/{{ $youtube_value }}" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- News and Download Section End -->

    <!-- Galery Start -->
    <div class="container-fluid feature bg-light py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h2 class="section-title text-uppercase text-black">Galeri Dinas Pendidikan</h2>
            </div>
            <div class="row">
                <!-- Galery Section -->
                @foreach ($gallerys as $gallery)
                    <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="gallery-item position-relative overflow-hidden rounded">
                            <!-- Image -->
                            <img src="{{ $gallery->image }}" class="img-fluid w-100" alt="{{ $gallery->title }}"
                                style="height: 250px; object-fit: cover;">

                            <!-- Title Overlay (hidden by default) -->
                            <div class="gallery-title position-absolute w-100 h-100 d-flex justify-content-center align-items-center text-center"
                                style="top: 0; left: 0; background-color: rgba(0, 0, 0, 0.5); color: white; opacity: 0; transition: opacity 0.3s;">
                                <h5>{{ $gallery->title }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center pt-4">
                <a href="{{ route('galeri.index') }}" class="btn btn-primary py-3 px-5 rounded-pill">Lihat Galeri
                    Lainnya</a>
            </div>
        </div>
    </div>
    <!-- Galery End -->

    <!-- Couter Data Section Start -->
    <div class="container-fluid counter py-5">
        <div class="container py-5">
            <div class="row g-5">
                <!-- Employee Count Section -->
                <div class="col-md-6 col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="counter-item">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-users fa-3x text-white"></i>
                        </div>
                        <h4 class="text-white my-4">Jumlah Pegawai</h4>
                        <div class="counter-counting">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">{{ $employeeCount }}</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                    </div>
                </div>

                <!-- News Count Section -->
                <div class="col-md-6 col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="counter-item">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-newspaper fa-3x text-white"></i>
                        </div>
                        <h4 class="text-white my-4">Jumlah Berita</h4>
                        <div class="counter-counting">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">{{ $newsCount }}</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                    </div>
                </div>

                <!-- Field Count Section -->
                <div class="col-md-6 col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="counter-item">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-clipboard-list fa-3x text-white"></i>
                        </div>
                        <h4 class="text-white my-4">Jumlah Bidang</h4>
                        <div class="counter-counting">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">{{ $fieldCount }}</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Couter Data Section End -->

    <!-- Organization Structure Start -->
    <div class="container-fluid team pb-5">
        <div class="container pb-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h2 class="section-title text-uppercase text-black mt-5">Struktur Organisasi</h2>
            </div>
            <div class="row g-4">
                <div class="row g-4 d-flex justify-content-center">
                    @foreach ($head_of_department as $organization)
                        <div class="col-md-4 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.2s">
                            <div class="team-item">
                                <div class="team-inner rounded">
                                    <div class="team-img">
                                        <img src="{{ $organization->image }}" class="img-fluid rounded-top w-100"
                                            alt="Image">
                                    </div>
                                    <div class="bg-light rounded-bottom text-center py-4">
                                        <h6 class="mb-3"><strong>{{ $organization->name }}</strong></h6>
                                        <p class="mb-0" style="font-size: 15px">{{ $organization->position }}</p>
                                        <p class="mb-0">NIP. {{ $organization->NIP }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row g-4 d-flex justify-content-end">
                    @foreach ($secretariat as $organization)
                        <div class="col-md-4 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.2s">
                            <div class="team-item">
                                <div class="team-inner rounded">
                                    <div class="team-img">
                                        <img src="{{ $organization->image }}" class="img-fluid rounded-top w-100"
                                            alt="Image">
                                    </div>
                                    <div class="bg-light rounded-bottom text-center py-4">
                                        <h6 class="mb-3"><strong>{{ $organization->name }}</strong></h6>
                                        <p class="mb-0" style="font-size: 15px">{{ $organization->position }}</p>
                                        <p class="mb-0">NIP. {{ $organization->NIP }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row g-4">
                    @foreach ($division as $organization)
                        <div class="col-md-3 col-lg-3 col-xl-3">
                            <div class="team-item">
                                <div class="team-inner rounded">
                                    <div class="team-img">
                                        <img src="{{ $organization->image }}" class="img-fluid rounded-top w-100"
                                            alt="Image">
                                    </div>
                                    <div class="bg-light rounded-bottom text-center py-4">
                                        <h6 class="mb-3"><strong>{{ $organization->name }}</strong></h6>
                                        <p class="mb-0" style="font-size: 15px">{{ $organization->position }}</p>
                                        <p class="mb-0">NIP. {{ $organization->NIP }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- <div class="col-md-3 col-lg-3 col-xl-3">
                        @foreach ($tourism_department as $organization)
                            <div class="d-flex">
                                <div class="team-item">
                                    <div class="team-inner rounded">
                                        <div class="team-img">
                                            <img src="{{ $organization->image }}" class="img-fluid rounded-top w-100" alt="Image">
                                        </div>
                                        <div class="bg-light rounded-bottom text-center py-4">
                                            <h6 class="mb-3"><strong>{{ $organization->name }}</strong></h6>
                                            <p class="mb-0" style="font-size: 15px">{{ $organization->position }}</p>
                                            <p class="mb-0">NIP. {{ $organization->NIP }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> --}}

                {{-- <div class="col-md-3 col-lg-3 col-xl-3">
                        @foreach ($youth_department as $organization)
                            <div class="team-item">
                                <div class="team-inner rounded">
                                    <div class="team-img">
                                        <img src="{{ $organization->image }}" class="img-fluid rounded-top w-100" alt="Image">
                                    </div>
                                    <div class="bg-light rounded-bottom text-center py-4">
                                        <h6 class="mb-3"><strong>{{ $organization->name }}</strong></h6>
                                        <p class="mb-0" style="font-size: 15px">{{ $organization->position }}</p>
                                        <p class="mb-0">NIP. {{ $organization->NIP }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> --}}

                {{-- <div class="col-md-3 col-lg-3 col-xl-3">
                        @foreach ($sports_department as $organization)
                            <div class="team-item">
                                <div class="team-inner rounded">
                                    <div class="team-img">
                                        <img src="{{ $organization->image }}" class="img-fluid rounded-top w-100" alt="Image">
                                    </div>
                                    <div class="bg-light rounded-bottom text-center py-4">
                                        <h6 class="mb-3"><strong>{{ $organization->name }}</strong></h6>
                                        <p class="mb-0" style="font-size: 15px">{{ $organization->position }}</p>
                                        <p class="mb-0">NIP. {{ $organization->NIP }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> --}}

                <div class="text-center pt-4">
                    <a href="{{ route('organisasi.index') }}" class="btn btn-primary py-3 px-5 rounded-pill">Lihat
                        Struktur Lainnya</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Organization Structure End -->

    <div class="modal fade" id="modalPengajuanCuti" tabindex="-1" aria-labelledby="modalPengajuanCutiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 shadow-lg border-0" style="max-height: 95vh;">
                <!-- HEADER -->
                <div class="modal-header text-white border-0 py-4"
                    style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <h3 class="modal-title fw-bold" id="modalPengajuanCutiLabel">
                        <i class="fas fa-file-signature me-3"></i> Form Pengajuan Cuti
                    </h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- FORM -->
                <form id="formPengajuanCuti" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-5 bg-light" style="max-height: 70vh; overflow-y: auto;">
                        <div class="row g-4">

                            <!-- DATA DIRI -->
                            <div class="col-12">
                                <h5 class="text-primary fw-bold"><i class="fas fa-user me-2"></i> Data Diri Pemohon</h5>
                                <hr class="border-primary">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIP</label>
                                <input name="nip" type="text" class="form-control form-control-lg">
                                <div class="form-text text-muted small">Contoh: 198001012005011001</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input name="nama_lengkap" type="text" class="form-control form-control-lg" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tempat Lahir</label>
                                <input name="tempat_lahir" type="text" class="form-control form-control-lg">
                                <div class="form-text text-muted small">Contoh: Palangka Raya</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Lahir</label>
                                <input name="tanggal_lahir" type="date" class="form-control form-control-lg">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select form-select-lg">
                                    <option value="">Pilih...</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pangkat / Golongan</label>
                                <input name="pangkat_golongan" type="text" class="form-control form-control-lg">
                                <div class="form-text text-muted small">Contoh: Penata Muda Tk.I / III-d</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                                <input name="jabatan" type="text" class="form-control form-control-lg" required>
                                <div class="form-text text-muted small">Contoh: Guru Bahasa Indonesia</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Unit Kerja <span
                                        class="text-danger">*</span></label>
                                <input name="unit_kerja" type="text" class="form-control form-control-lg" required>
                                <div class="form-text text-muted small">Contoh: SMP Negeri 5 Kasongan / SDN 2 Kasongan
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor HP / WA <span
                                        class="text-danger">*</span></label>
                                <input name="nomor_hp" type="text" class="form-control form-control-lg" required>
                                <div class="form-text text-muted small">Contoh: 081234567890</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Rumah</label>
                                <textarea name="alamat" class="form-control" rows="2"></textarea>
                                <div class="form-text text-muted small">Contoh: Jl. Tingang IV No. 123, Palangka Raya</div>
                            </div>

                            <!-- DETAIL CUTI -->
                            <div class="col-12 mt-5">
                                <h5 class="text-primary fw-bold"><i class="fas fa-calendar-check me-2"></i> Detail Cuti
                                </h5>
                                <hr class="border-primary">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Cuti <span
                                        class="text-danger">*</span></label>
                                <select name="jenis_cuti_id" class="form-select form-select-lg" required>
                                    <option value="">Pilih jenis cuti...</option>
                                    @foreach (\App\Models\JenisCuti::orderBy('nama')->get() as $jc)
                                        <option value="{{ $jc->id }}">{{ $jc->nama }} @if ($jc->maks_hari)
                                                ({{ $jc->maks_hari }} hari)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tanggal Mulai <span
                                        class="text-danger">*</span></label>
                                <input name="tanggal_mulai" type="date" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tanggal Selesai <span
                                        class="text-danger">*</span></label>
                                <input name="tanggal_selesai" type="date" class="form-control form-control-lg"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Alasan Cuti <span
                                        class="text-danger">*</span></label>
                                <textarea name="alasan_cuti" class="form-control" rows="3" required></textarea>
                                <div class="form-text text-muted small">Contoh: Menikahkan anak / Acara keluarga besar /
                                    Sakit</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Selama Cuti <span
                                        class="text-danger">*</span></label>
                                <input name="alamat_selama_cuti" type="text" class="form-control form-control-lg"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kontak Selama Cuti <span
                                        class="text-danger">*</span></label>
                                <input name="kontak_selama_cuti" type="text" class="form-control form-control-lg"
                                    required>
                                <div class="form-text text-muted small">Contoh: 0812-xxx-xxx (keluarga)</div>
                            </div>

                            <!-- LAMPIRAN -->
                            <div class="col-12 mt-5">
                                <h5 class="text-primary fw-bold"><i class="fas fa-paperclip me-2"></i> Lampiran Berkas
                                    Pendukung</h5>
                                <hr class="border-primary">
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label fw-semibold text-primary">
                                    <i class="fas fa-folder-open me-2"></i> Lampiran Umum
                                </label>
                                <input type="file" name="berkas[]" class="form-control" multiple
                                    accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text text-muted small">Opsional: surat permohonan, SK, dll. Boleh lebih
                                    dari satu file.</div>
                            </div>

                            <div class="mb-4" id="div_surat_dokter" style="display: none;">
                                <label class="form-label text-danger fw-bold">
                                    <i class="fas fa-file-medical-alt me-2"></i> Surat Keterangan Dokter <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="file" name="surat_dokter" class="form-control border-danger"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text text-danger small">
                                    Wajib untuk cuti sakit >14 hari atau cuti sakit khusus
                                </div>
                            </div>

                            <div class="mb-4" id="div_lampiran_tambahan" style="display: none;">
                                <label class="form-label text-warning fw-bold">
                                    <i class="fas fa-paperclip me-2"></i> Lampiran Tambahan <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="file" name="lampiran_tambahan" class="form-control border-warning"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text text-warning small">
                                    Contoh: surat kematian, akta kelahiran, surat nikah, dll
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer border-0 bg-white px-5 py-4 justify-content-between">
                        <button type="button" class="btn btn-light btn-lg px-5 rounded-pill"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill fw-bold text-white"
                            style="background: linear-gradient(135deg, #667eea, #764ba2); border:none;">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL – SATU MODAL UNTUK SEMUA --}}
    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Detail Pengajuan Cuti</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" id="isiDetail">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: SUKSES PENGAJUAN --}}
    <div class="modal fade" id="modalSukses" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg text-center p-5">
                <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                <h3 class="mt-4 text-success fw-bold">Pengajuan Berhasil!</h3>
                <div class="alert alert-success mt-4">
                    <h4>Kode Pengajuan Anda:</h4>
                    <h2 class="text-primary fw-bold" id="kodeSukses"></h2>
                    <p>Simpan kode ini untuk melacak status</p>
                </div>
                <button type="button" class="btn btn-primary btn-lg px-5 rounded-pill"
                    data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>

    <div class="modal-body p-4" id="hasilTrackingContent">
        <!-- Hasil tracking masuk ke sini -->
    </div>

    {{-- MODAL REVISI — VERSI FINAL YANG 100% JALAN! --}}
    <div class="modal fade" id="modalRevisi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold">
                        Upload Revisi Berkas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5" id="modalRevisiBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnKirimRevisi" class="btn btn-warning btn-lg px-5">
                        <span class="spinner-border spinner-border-sm me-2 d-none"></span>
                        Kirim Revisi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('JS CUTI AKTIF!');

            // === TRACKING ===
            const inputCari = document.getElementById('inputCari');
            const btnCari = document.getElementById('btnCari');
            const hasilCari = document.getElementById('hasilCari');
            const isiDetail = document.getElementById('isiDetail');

            window.bukaDetail = function(kode) {
                fetch(`/cuti/track?kode=${kode}`)
                    .then(r => r.text())
                    .then(html => {
                        isiDetail.innerHTML = html;
                        new bootstrap.Modal('#modalDetail').show();
                    });
            };

            if (btnCari) {
                btnCari.addEventListener('click', function() {
                    const nilai = inputCari.value.trim();
                    if (!nilai) return Swal.fire('Oops!', 'Masukkan kode atau NIP!', 'warning');

                    btnCari.disabled = true;
                    btnCari.innerHTML = 'Mencari...';

                    const isKode = /^CT|^CUTI/i.test(nilai);
                    const url = isKode ? `/cuti/track?kode=${nilai.toUpperCase()}` :
                        `/cuti/track?nip=${nilai}`;

                    fetch(url)
                        .then(r => r.text())
                        .then(html => {
                            if (isKode) {
                                isiDetail.innerHTML = html;
                                new bootstrap.Modal('#modalDetail').show();
                                hasilCari.innerHTML = '';
                            } else {
                                hasilCari.innerHTML = html;
                            }
                        })
                        .catch(() => {
                            hasilCari.innerHTML =
                                '<div class="alert alert-danger text-center">Data tidak ditemukan</div>';
                        })
                        .finally(() => {
                            btnCari.disabled = false;
                            btnCari.innerHTML = 'Cari';
                        });
                });

                inputCari.addEventListener('keypress', e => e.key === 'Enter' && btnCari.click());
            }

            // === JENIS CUTI DINAMIS ===
            const jenisCutiSelect = document.querySelector('[name="jenis_cuti_id"]');
            const divSuratDokter = document.getElementById('div_surat_dokter');
            const divLampiranTambahan = document.getElementById('div_lampiran_tambahan');

            if (jenisCutiSelect) {
                jenisCutiSelect.addEventListener('change', function() {
                    const id = this.value;
                    if (!id) {
                        divSuratDokter.style.display = 'none';
                        divLampiranTambahan.style.display = 'none';
                        return;
                    }
                    fetch(`/cuti/get-jenis-cuti/${id}`)
                        .then(r => r.json())
                        .then(d => {
                            divSuratDokter.style.display = d.butuh_surat_dokter ? 'block' : 'none';
                            divLampiranTambahan.style.display = d.butuh_lampiran_tambahan ? 'block' :
                                'none';
                        });
                });
            }

            const formPengajuan = document.getElementById('formPengajuanCuti');
            if (formPengajuan) {
                formPengajuan.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const btn = this.querySelector('button[type="submit"]');
                    const oldHtml = btn.innerHTML;

                    btn.disabled = true;
                    btn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';

                    const fd = new FormData(this);

                    fetch('{{ route('cuti.store') }}', {
                            method: 'POST',
                            body: fd,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(r => r.json())
                        .then(d => {
                            if (d.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pengajuan Berhasil!',
                                    html: `<h4>Kode Pengajuan Anda:</h4><h2 class="text-primary fw-bold">${d.kode}</h2><p>Simpan kode ini untuk melacak status</p>`,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Tutup modal
                                    bootstrap.Modal.getInstance(document.getElementById(
                                        'modalPengajuanCuti')).hide();
                                    // Reset form
                                    formPengajuan.reset();
                                    document.getElementById('div_surat_dokter').style.display =
                                        'none';
                                    document.getElementById('div_lampiran_tambahan').style
                                        .display = 'none';
                                });
                            } else {
                                throw new Error(d.message || 'Validasi gagal');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire('Gagal!', err.message || 'Terjadi kesalahan saat mengirim',
                                'error');
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.innerHTML = oldHtml;
                        });
                });
            }

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('button[data-bs-target="#modalRevisi"]');
                if (!btn) return;

                e.preventDefault();

                const alertBox = btn.closest('.alert');
                const catatan = alertBox?.querySelector('.lead')?.textContent.trim() ||
                    'Silakan upload berkas yang diminta';
                const pengajuanId = alertBox?.querySelector('input[name="pengajuan_id"]')?.value;

                if (!pengajuanId) {
                    Swal.fire('Error', 'ID pengajuan tidak ditemukan', 'error');
                    return;
                }

                const scriptData = document.getElementById('data-revisi');
                const types = scriptData ? JSON.parse(scriptData.textContent || '[]') : [];

                let formHtml = `
            <div class="alert alert-info mb-4">
                <strong>Catatan Revisi:</strong><br>
                <p class="mb-0 lead">${catatan}</p>
            </div>
            <form id="formRevisi" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pengajuan_id" value="${pengajuanId}">
        `;

                types.forEach(type => {
                    const label = type === 'surat_dokter' ? 'Surat Dokter' :
                        type === 'lampiran_tambahan' ? 'Lampiran Tambahan' :
                        type.replace(/_/g, ' ').toUpperCase();

                    formHtml += `
                <div class="mb-4 p-4 border border-warning rounded-3 bg-light">
                    <label class="form-label fw-bold text-danger">
                        ${label} <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="revisi_${type}" class="form-control form-control-lg" required accept=".pdf,.jpg,.jpeg,.png">
                </div>
            `;
                });

                formHtml += `</form>`;

                document.getElementById('modalRevisiBody').innerHTML = formHtml;

                // Buka modal dengan benar
                const modalElement = document.getElementById('modalRevisi');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            });

            // === SUBMIT REVISI — KLIK TOMBOL, BUKAN SUBMIT EVENT ===
            document.addEventListener('click', function(e) {
                if (e.target.id !== 'btnKirimRevisi' && !e.target.closest('#btnKirimRevisi')) return;

                e.preventDefault();

                const btn = e.target.closest('#btnKirimRevisi');
                const spinner = btn.querySelector('.spinner-border');
                const oldHtml = btn.innerHTML;

                btn.disabled = true;
                spinner.classList.remove('d-none');
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';

                const form = document.getElementById('formRevisi');
                const fd = new FormData(form);

                fetch('/cuti/submit-revisi', {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Revisi Berhasil!',
                                text: d.message,
                                timer: 3000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(d.message || 'Gagal mengirim revisi');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error!', err.message || 'Terjadi kesalahan saat mengirim', 'error');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = oldHtml;
                    });
            });

            // === FIX BACKDROP GAK MAU ILANG ===
            document.getElementById('modalRevisi')?.addEventListener('hidden.bs.modal', function() {
                document.body.classList.remove('modal-open');
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(b => b.remove());
            });
        });
    </script>
@endpush
