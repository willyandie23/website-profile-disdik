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

    {{-- TRACKING PENGAJUAN CUTI – UKURAN PAS, SUPER ELEGAN, GLASSY, CANTIK BANGET --}}
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">

                <div class="bg-white bg-opacity-90 backdrop-blur-md rounded-4 shadow-lg p-5 border-0 position-relative overflow-hidden"
                    style="border-top: 6px solid #5d5fef solid; box-shadow: 0 15px 35px rgba(93, 95, 239, 0.12)!important;">

                    <!-- Garis gradasi halus di atas -->
                    <div class="position-absolute top-0 start-0 w-100 h-1"
                        style="background: linear-gradient(90deg, #5d5fef, #a855f7, #ec4899);"></div>

                    <div class="text-center">
                        <!-- Icon premium -->
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4"
                            style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea, #764ba2);">
                            <i class="fas fa-search-location text-white" style="font-size: 36px;"></i>
                        </div>

                        <h4 class="fw-bold text-dark mb-2" style="font-size: 1.8rem; letter-spacing: 0.5px;">
                            Lacak Pengajuan Cuti
                        </h4>
                        <p class="text-muted mb-4" style="font-size: 1.05rem;">
                            Masukkan kode pengajuan untuk melihat status terkini
                        </p>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('cuti.track') }}" method="GET"
                        class="row g-3 align-items-center justify-content-center">
                        <div class="col-12 col-md-8">
                            <input type="text" name="kode"
                                class="form-control form-control-lg text-center fw-bold border-2 shadow-sm"
                                placeholder="CT-2025-000123" required maxlength="20" autocomplete="off"
                                style="
                                height: 64px;
                                font-size: 1.3rem;
                                letter-spacing: 4px;
                                text-transform: uppercase;
                                border-color: #e2e8f0;
                                border-radius: 16px;
                                background: #f8faff;
                            "
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9-]/g, '')">
                        </div>
                        <div class="col-12 col-md-4">
                            <button type="submit"
                                class="btn btn-lg w-100 fw-bold d-flex align-items-center justify-content-center gap-2 shadow text-white"
                                style="
            height: 64px;
            border-radius: 16px;
            font-size: 1.25rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            transition: all 0.3s ease;
        "
                                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 25px rgba(102,126,234,0.4)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 15px rgba(0,0,0,0.1)'">
                                <i class="fas fa-search"></i>
                                Lacak
                            </button>
                        </div>
                    </form>

                    <!-- Link ajukan cuti -->
                    <div class="text-center mt-4 pt-3">
                        <small class="text-muted d-block mb-2">Belum punya kode pengajuan?</small>
                        <a href="#" class="btn btn-outline-primary rounded-pill px-5 py-3 fw-bold fs-5 shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#modalPengajuanCuti">
                            <i class="fas fa-plus-circle me-2"></i> Ajukan Cuti Sekarang
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>
    {{-- SELESAI – CANTIK, ELEGAN, UKURAN PAS, NYATU SEMPURNA --}}

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

    <div class="modal fade" id="modalPengajuanCuti" tabindex="-1" aria-labelledby="modalPengajuanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="modal-header border-0 text-white py-4"
                    style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <h3 class="modal-title fw-bold" id="modalPengajuanLabel">
                        <i class="fas fa-file-signature me-3"></i> Form Pengajuan Cuti
                    </h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-5 bg-light">
                    <div class="row g-4">
                        <!-- Data Pemohon -->
                        <div class="col-12">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="fas fa-user-circle me-2"></i> Data Pemohon
                            </h5>
                            <hr class="border-primary">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP</label>
                            <input type="text" class="form-control form-control-lg" placeholder="18102025xxxxxxx"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-lg" placeholder="Ahmad Fauzi, S.Pd."
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pangkat / Golongan</label>
                            <input type="text" class="form-control form-control-lg" placeholder="Penata Muda / III-a"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan</label>
                            <input type="text" class="form-control form-control-lg" placeholder="Guru Matematika"
                                disabled>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Unit Kerja</label>
                            <input type="text" class="form-control form-control-lg" placeholder="SMP Negeri 1 Jakarta"
                                disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">No. HP / WA</label>
                            <input type="text" class="form-control form-control-lg" placeholder="0812-3456-7890">
                        </div>

                        <!-- Jenis & Periode Cuti -->
                        <div class="col-12 mt-4">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="fas fa-calendar-check me-2"></i> Jenis & Periode Cuti
                            </h5>
                            <hr class="border-primary">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Cuti</label>
                            <select class="form-select form-select-lg">
                                <option value="">Pilih Jenis Cuti...</option>
                                <option>Cuti Tahunan</option>
                                <option>Cuti Besar</option>
                                <option>Cuti Sakit</option>
                                <option>Cuti Melahirkan</option>
                                <option>Cuti Alasan Penting</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" class="form-control form-control-lg">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" class="form-control form-control-lg">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alasan Cuti</label>
                            <textarea class="form-control" rows="4"
                                placeholder="Contoh: Menikahkan anak / Istirahat setelah tugas luar daerah..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Alamat Selama Cuti</label>
                            <input type="text" class="form-control form-control-lg"
                                placeholder="Jl. Merdeka No.10, Bandung">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kontak yang Bisa Dihubungi</label>
                            <input type="text" class="form-control form-control-lg"
                                placeholder="0812-9999-8888 (keluarga)">
                        </div>

                        <!-- Lampiran Berkas -->
                        <div class="col-12 mt-4">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="fas fa-paperclip me-2"></i> Lampiran Berkas Pendukung
                            </h5>
                            <hr class="border-primary">
                            <div class="border-2 border-dashed border-primary rounded-4 p-5 text-center bg-white">
                                <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 48px;"></i>
                                <p class="mt-3 mb-2 fw-semibold text-dark">Drag & drop file di sini atau klik untuk upload
                                </p>
                                <p class="text-muted small">PDF, JPG, PNG • Maks. 10MB • Maks. 5 file</p>
                                <button type="button" class="btn btn-outline-primary btn-lg px-5 mt-3 rounded-pill">Pilih
                                    Berkas</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 bg-white px-5 py-4">
                    <button type="button" class="btn btn-light btn-lg px-5 rounded-pill shadow-sm"
                        data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-lg px-5 rounded-pill fw-bold text-white shadow"
                        style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
