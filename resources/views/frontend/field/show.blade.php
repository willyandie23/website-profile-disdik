@extends('frontend.layouts.app')

@section('title', 'Bidang | DISDIK')

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

        .description.ck-content p {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: unset !important;
            text-align: justify;
        }
        
        .ck-content p { margin: 1em 0; }
        .ck-content ul, .ck-content ol { padding-left: 40px; }
        .ck-content h1, .ck-content h2, .ck-content h3 { font-weight: bold; margin: 1.5em 0 1em; }

        .team-item {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            min-height: 350px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .bg-light {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
        }

        h6 {
            font-size: 16px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            transition: all 0.3s ease;
        }

        p {
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            transition: all 0.3s ease;
        }

        .team-item:hover h6, .team-item:hover p {
            white-space: normal;
            overflow: visible;
            text-overflow: unset;
        }

        @media (max-width: 768px) {
            .main-content {
                padding-top: 0px;
                padding-left: 20px;
                padding-right: 20px;
            }
        }

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
        <h2>{{ $field->name }}</h2>
        <hr>
        <div class="description ck-content">
            {!! $field->description !!}
        </div>

        <div class="container-fluid team">
            <div class="container">
                <div class="row g-4 mb-3 d-flex justify-content-center">
                    @foreach ($organizations as $organization)
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
                                            <p class="mb-0">{{ $organization->position }}</p>
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
