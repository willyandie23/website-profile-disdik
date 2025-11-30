@extends('frontend.layouts.app')

@section('title', 'Sambutan Kepala | DISDIK')

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

        .description {
            text-align: justify;
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
        <h2>Sambutan Kepala Dinas</h2>
        <hr>
        <div class="description">
            <p>
                Puji Syukur kami panjatkan kehadirat Tuhan Y.M.E atas Rahmat dan KaruniaNya sehingga website 
                Dinas Kebudayaan, Kepemudaan dan Olahraga serta Pariwisata Kabupaten Katingan telah selesai 
                dikerjakan dan kita dapat berkomunikasi melalui media ini. Adapun tujuan pembuatan website ini 
                adalah sebagai media informasi dan komunikasi yang menyajikan gambaran umum tentang Dinas Kebudayaan, 
                Kepemudaan dan Olahraga serta Pariwisata Kabupaten Katingan. Kami menyadari masih banyak kekurangan yang 
                terdapat dalam website ini, oleh karena itu kami mengharapkan masukan-masukan yang membangun untuk kesempurnaan 
                website ini.
            </p>
            <br>
            <p>
                Kepada semua pihak yang telah banyak membantu dan berpartisipasi sehingga website ini dapat dipublikasikan, 
                dengan ini kami menyampaikan ucapan terima kasih dan penghargaan yang sebesar-besarnya.
            </p>
            <br>
            <p>
                Kasongan, Juni 2025
            </p>
            <p>
                Kepala Disbudporapar Kabupaten Katingan,
            </p>
        </div>
        <div class="container-fluid team">
            <div class="container">
                <div class="row g-4 mb-3">
                    @foreach ($head_of_department as $organization)
                        <div class="col-md-4 col-lg-4 col-md-3 wow fadeInUp" data-wow-delay="0.2s">
                            <div class="team-item">
                                <div class="team-inner rounded">
                                    <div class="team-img">
                                        <img src="{{ $organization->image }}" class="img-fluid rounded-top w-100"
                                            alt="Image">
                                    </div>
                                    <div class="bg-light rounded-bottom text-center py-4">
                                        <div class="m-1">
                                            <h6 class="mb-3"><strong>{{ $organization->name }}</strong></h6>
                                            <p class="mb-0">NIP. {{ $organization->NIP }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
