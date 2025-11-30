@extends('frontend.layouts.app')

@push('css')
    <style>
        .video-container {
            width: 100%;
            max-width: 100%;
            height: 500px; /* Set your desired height */
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
                @foreach($banners as $key => $banner)
                    <li data-bs-target="#carouselId" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></li>
                @endforeach
            </ol>
            <div class="carousel-inner" role="listbox">
                @foreach($banners as $key => $banner)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ $banner->image }}" class="img-fluid w-100" alt="{{ $banner->title }}">
                        <div class="carousel-caption">
                            <div class="carousel-caption-content" style="max-width: 1000px;">
                                <h1 class="display-2 text-capitalize text-white mb-4 fadeInLeft animated" data-animation="fadeInLeft" data-delay="1.3s" style="animation-delay: 1.3s;">{{ $banner->title }}</h1>
                                <p class="mb-5 fs-5 text-white fadeInLeft animated" data-animation="fadeInLeft" data-delay="1.5s" style="animation-delay: 1.5s;">{{ $banner->description }}</p>
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
                        @foreach($latestNews as $news)
                            <div class="col-md-12 wow fadeInUp" data-wow-delay="0.2s">
                                <div class="blog-item">
                                    <div class="blog-img">
                                        <img src="{{ $news->image }}" class="img-fluid rounded-top w-100" alt="{{ $news->title }}" style="object-fit: cover; height: 300px;">
                                        <div class="blog-date px-4 py-2"><i class="fa fa-calendar-alt me-1"></i> {{ $news->created_at->format('M d, Y') }}</div>
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
                        @foreach($latestDownloads as $download)
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
                @foreach($gallerys as $gallery)
                    <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="gallery-item position-relative overflow-hidden rounded">
                            <!-- Image -->
                            <img src="{{ $gallery->image }}" class="img-fluid w-100" alt="{{ $gallery->title }}" style="height: 250px; object-fit: cover;">
                            
                            <!-- Title Overlay (hidden by default) -->
                            <div class="gallery-title position-absolute w-100 h-100 d-flex justify-content-center align-items-center text-center" style="top: 0; left: 0; background-color: rgba(0, 0, 0, 0.5); color: white; opacity: 0; transition: opacity 0.3s;">
                                <h5>{{ $gallery->title }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center pt-4">
                <a href="{{ route('galeri.index') }}" class="btn btn-primary py-3 px-5 rounded-pill">Lihat Galeri Lainnya</a>
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
                </div>

                {{-- <div class="row g-4 d-flex flex-row-reverse">
                    @foreach ($secretariat as $organization)
                        <div class="col-md-4 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.2s">
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

                <div class="row g-4">
                    <div class="col-md-3 col-lg-3 col-xl-3">
                        @foreach ($cultural_department as $organization)
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
                    </div>

                    <div class="col-md-3 col-lg-3 col-xl-3">
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
                    </div>

                    <div class="col-md-3 col-lg-3 col-xl-3">
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
                    </div>

                    <div class="col-md-3 col-lg-3 col-xl-3">
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
                    </div>

                    <div class="text-center pt-4">
                        <a href="{{ route('organisasi.index') }}" class="btn btn-primary py-3 px-5 rounded-pill">Lihat Struktur Lainnya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Organization Structure End -->

@endsection
