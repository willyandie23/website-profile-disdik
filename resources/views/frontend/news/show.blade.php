@extends('frontend.layouts.app')

@section('title', 'Berita | DISDIK')

@push('css')
    <style>
        .navbar-light.opaque .navbar-nav .nav-link {
            background: var(--bs-light) !important;
            color: var(--bs-dark);
        }

        /* Main Content */
        .main-content {
            position: static;
            color: var(--bs-dark);
            padding-top: 90px;
            padding-left: 150px;
            padding-right: 150px;
            user-select: none;
        }

        h2 {
            padding-top: 15px;
            font-size: 28px;
            font-weight: bold;
        }

        /* Styling for Author and Date */
        .author {
            font-size: 16px;
            color: #666;
            background-color: #f1f1f1; /* Background color for author and date */
            padding: 8px 15px;  /* Adding padding around text */
            border-radius: 5px;  /* Rounded corners */
            margin-bottom: 20px;  /* Bottom margin for spacing */
        }

        /* Image adjustments */
        .img-fluid {
            display: block;
            margin: 0 auto;  /* Center image */
            max-width: 50%;  /* Ensure image doesn't take more than 50% of width */
            height: auto;
            margin-bottom: 20px; /* Adding space below the image */
        }

        /* Description styling */
        .news-description {
            font-size: 14px; /* Adjust font size for readability */
            line-height: 1.6;
            color: #333;
            text-align: justify;  /* Justify text */
            margin-top: 20px;
        }

        /* Styling for Mobile and Tablets */
        @media (max-width: 768px) {
            .main-content {
                padding-top: 0px;
                padding-left: 20px;
                padding-right: 20px;
            }

            h2 {
                font-size: 24px;
            }

            /* Author and Date styling */
            .author {
                font-size: 14px;
                padding: 8px;
                text-align: center;
            }

            .img-fluid {
                width: 100%;  /* Make the image width 100% on smaller screens */
                max-width: 100%;
            }

            /* News description */
            .news-description {
                font-size: 12px;
            }
        }

        @media (max-width: 992px) and (min-width: 769px) {
            .main-content {
                padding-top: 0px;
                padding-left: 50px;
                padding-right: 50px;
            }

            .img-fluid {
                width: 80%;  /* Make image slightly smaller on tablet devices */
            }

            .author {
                font-size: 15px;
            }

            .news-description {
                font-size: 13px;
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
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    <h2>{{ $news->title }}</h2>
                    <hr>
                    <div class="author">Penulis: {{ $news->author }} | Tanggal: {{ \Carbon\Carbon::parse($news->created_at)->format('d M Y') }}</div>

                    <!-- Menampilkan gambar dengan ukuran yang lebih kecil -->
                    <img src="{{ $news->image }}" alt="{{ $news->title }}" class="img-fluid">

                    <!-- Menampilkan deskripsi dengan paragraf yang terformat -->
                    <div class="news-description">
                        {!! $news->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
