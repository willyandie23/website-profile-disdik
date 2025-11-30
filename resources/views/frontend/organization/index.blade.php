@extends('frontend.layouts.app')

@section('title', 'Struktur Organisasi | DISDIK')

@push('css')
    <style>
        .navbar-light.opaque .navbar-nav .nav-link {
            background: var(--bs-light) !important;
            color: var(--bs-dark);
        }

        .main-content {
            position: static;
            color: var(--bs-dark);
            padding-top: 90px;
            padding-left: 150px;
            padding-right: 150px;
        }

        h2 {
            padding-top: 15px;
            font-size: 28px;
            font-weight: bold;
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
            display: flex;
            flex-direction: column;
            min-height: 350px; /* Setel tinggi minimal card */
            overflow: hidden; /* Menghindari elemen keluar dari card */
            transition: all 0.3s ease; /* Menambahkan transisi untuk efek hover */
        }

        .bg-light {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto; /* Menambahkan scrollbar jika konten panjang */
        }

        h6 {
            font-size: 16px;
            font-weight: bold;
            white-space: nowrap; /* Menjaga teks tetap dalam satu baris */
            overflow: hidden; /* Memastikan teks yang panjang tidak keluar */
            text-overflow: ellipsis; /* Menambahkan elipsis jika teks panjang */
            max-width: 100%; /* Menjamin lebar maksimal untuk nama */
            transition: all 0.3s ease; /* Menambahkan transisi untuk efek hover */
        }

        p {
            font-size: 14px;
            white-space: nowrap; /* Menjaga posisi teks tetap dalam satu baris */
            overflow: hidden; /* Memastikan teks yang panjang tidak keluar */
            text-overflow: ellipsis; /* Menambahkan elipsis pada teks yang panjang */
            max-width: 100%; /* Menjamin lebar maksimal untuk posisi jabatan */
            transition: all 0.3s ease; /* Menambahkan transisi untuk efek hover */
        }

        .team-item:hover h6, .team-item:hover p {
            white-space: normal; /* Membiarkan teks untuk berpindah baris */
            overflow: visible; /* Menghilangkan batas overflow */
            text-overflow: unset; /* Menghilangkan elipsis pada hover */
        }

        /* Responsivitas untuk layar lebih kecil (Mobile View) */
        @media (max-width: 768px) {
            .main-content {
                padding-top: 0px;
                padding-left: 20px;
                padding-right: 20px;
            }
        }

        /* Untuk tablet view dan lebih besar */
        @media (max-width: 992px) and (min-width: 769px) {
            .main-content {
                padding-top: 0px;
                padding-left: 50px;
                padding-right: 50px;
            }
        }

        @media (min-width: 992px) {
            .navbar-light {
                position: absolute;
                width: 100%;
                top: 0;
                left: 0;
                border-top: 0;
                border-right: 0;
                border-bottom: 1px solid;
                border-left: 0;
                border-style: dotted;
                z-index: 999;
            }

            .navbar-light.opaque {
                background: var(--bs-light) !important;
            }

            .sticky-top.navbar-light {
                position: fixed;
                background: var(--bs-light);
                border: none;
            }
        }

    </style>
@endpush

@section('content')
    <div class="container main-content">
        <h2>Struktur Organisasi</h2>
        <hr>

        <div class="row g-4 mb-3 d-flex justify-content-center">
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

        <div class="row g-4 mb-3 d-flex flex-row-reverse">
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
        </div>

        <div class="row g-4 mb-3">
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
        </div>
        <div class="row g-4 mb-3 d-flex justify-content-center">
            @foreach ($secretariat_sub as $organization)
                @if ($organization->position != 'Plt. SEKRETARIS')
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
                @endif
            @endforeach
        </div>
    </div>
@endsection
