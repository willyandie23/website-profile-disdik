@extends('frontend.layouts.app')

@section('title', 'Struktur Organisasi | DISDIK')

@push('css')
    <style>
        * {
            box-sizing: border-box;
        }

        .navbar-light.opaque .navbar-nav .nav-link {
            background: var(--bs-light) !important;
            color: var(--bs-dark);
        }

        .main-content {
            position: static;
            color: var(--bs-dark);
            padding: 90px 150px 80px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header Styling */
        .page-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .page-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 15px;
            margin-top: 15px;
        }

        .page-header h2::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        .page-header p {
            color: #6c757d;
            font-size: 1.1rem;
            margin-top: 15px;
            justify-content: left;
        }

        /* Level Title */
        .level-section {
            margin: 80px 0 40px;
        }

        .level-title {
            text-align: center;
            font-size: 1.75rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 50px;
            position: relative;
            padding-bottom: 20px;
        }

        .level-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #007bff;
            border-radius: 2px;
        }

        /* Card Container - PENTING: Untuk equal height */
        .org-row {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            margin-bottom: 40px;
        }

        /* Card Column - Flexbox untuk equal height */
        .org-col {
            flex: 0 0 auto;
            width: calc(25% - 22.5px);
            /* 4 kolom dengan gap 30px */
            display: flex;
        }

        /* Card Item - Harus flex untuk stretch */
        .team-item {
            display: flex;
            flex-direction: column;
            width: 100%;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f0f0f0;
        }

        .team-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(0, 123, 255, 0.12);
            border-color: rgba(0, 123, 255, 0.2);
        }

        /* Image Container - Fixed height untuk konsistensi */
        .team-img {
            position: relative;
            width: 100%;
            height: 280px;
            /* Fixed height */
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            flex-shrink: 0;
            /* Prevent image dari resize */
        }

        .team-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.4s ease;
        }

        .team-item:hover .team-img img {
            transform: scale(1.08);
        }

        /* Info Section - flex-grow untuk mengisi ruang tersisa */
        .team-info {
            flex-grow: 1;
            /* PENTING: Membuat tinggi card sama */
            display: flex;
            flex-direction: column;
            padding: 24px 20px;
            text-align: center;
            background: #ffffff;
        }

        .team-info h6 {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: #1a1a1a;
            line-height: 1.4;
            min-height: 50px;
            /* Minimum height untuk nama */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .team-info .position {
            display: block;
            color: #007bff;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 10px;
            min-height: 40px;
            /* Minimum height untuk posisi */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .team-info .field-name {
            color: #6c757d;
            font-size: 0.88rem;
            margin: 8px 0;
            font-style: italic;
        }

        .team-info .nip {
            color: #868e96;
            font-size: 0.85rem;
            margin-top: auto;
            /* Push ke bawah */
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }

        /* Badge */
        .level-badge {
            display: inline-block;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Special: Kepala Dinas (Lebih besar) */
        .kepala-dinas .org-col {
            width: 100%;
            max-width: 300px;
        }

        .kepala-dinas .team-item {
            border: 2px solid #007bff;
            box-shadow: 0 8px 30px rgba(0, 123, 255, 0.15);
        }

        .kepala-dinas .team-img {
            height: 300px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .kepala-dinas .team-info h6 {
            font-size: 1.4rem;
        }

        .kepala-dinas .team-info .position {
            font-size: 1.05rem;
        }

        /* Special: Sekretaris (Sedikit lebih besar) */
        .sekretariat .org-col {
            width: 100%;
            max-width: 300px;
        }

        .sekretariat .team-img {
            height: 300px;
        }

        /* Responsive Design */
        @media (max-width: 1400px) {
            .org-col {
                width: calc(33.333% - 20px);
                /* 3 kolom */
            }
        }

        @media (max-width: 1200px) {
            .main-content {
                padding: 60px 80px;
            }

            .org-col {
                width: calc(50% - 15px);
                /* 2 kolom */
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 30px 20px 50px;
            }

            .page-header h2 {
                font-size: 1.9rem;
            }

            .level-title {
                font-size: 1.4rem;
                margin-bottom: 35px;
            }

            .org-row {
                gap: 25px;
            }

            .org-col {
                width: 100%;
                /* 1 kolom di mobile */
            }

            .team-img {
                height: 300px;
            }

            .kepala-dinas .team-img,
            .sekretariat .team-img {
                height: 300px;
            }
        }

        @media (max-width: 576px) {
            .team-info h6 {
                font-size: 1.05rem;
                min-height: auto;
            }

            .team-info .position {
                font-size: 0.9rem;
                min-height: auto;
            }
        }

        @media (min-width: 992px) {
            .navbar-light {
                position: absolute;
                width: 100%;
                top: 0;
                left: 0;
                border-bottom: 1px dotted;
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

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .team-item {
            animation: fadeInUp 0.5s ease-out backwards;
        }

        .org-col:nth-child(1) .team-item {
            animation-delay: 0.05s;
        }

        .org-col:nth-child(2) .team-item {
            animation-delay: 0.1s;
        }

        .org-col:nth-child(3) .team-item {
            animation-delay: 0.15s;
        }

        .org-col:nth-child(4) .team-item {
            animation-delay: 0.2s;
        }

        .org-col:nth-child(5) .team-item {
            animation-delay: 0.25s;
        }

        .org-col:nth-child(6) .team-item {
            animation-delay: 0.3s;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid main-content">

        <div class="page-header">
            <h2>Struktur Organisasi</h2>
        </div>

        {{-- LEVEL 1 – Kepala Dinas --}}
        @if ($kepala->count())
            <div class="org-row kepala-dinas">
                @foreach ($kepala as $org)
                    <div class="org-col">
                        <div class="team-item">
                            <div class="team-img">
                                <img src="{{ $org->image }}" alt="{{ $org->name }}" loading="lazy">
                            </div>
                            <div class="team-info">
                                <h6>{{ $org->name }}</h6>
                                <span class="position">{{ $org->position }}</span>
                                <p class="nip">NIP. {{ $org->NIP }}</p>
                                <span class="level-badge">Pimpinan</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- LEVEL 2 – Sekretaris --}}
        @if ($sekretaris->count())
            <div class="level-section">
                <div class="level-title">Sekretariat</div>
                <div class="org-row sekretariat">
                    @foreach ($sekretaris as $org)
                        <div class="org-col">
                            <div class="team-item">
                                <div class="team-img">
                                    <img src="{{ $org->image }}" alt="{{ $org->name }}" loading="lazy">
                                </div>
                                <div class="team-info">
                                    <h6>{{ $org->name }}</h6>
                                    <span class="position">{{ $org->position }}</span>
                                    <p class="nip">NIP. {{ $org->NIP }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- LEVEL 3 – Kepala Bidang --}}
        @if ($kabid->count())
            <div class="level-section">
                <div class="level-title">Kepala Bidang</div>
                <div class="org-row">
                    @foreach ($kabid as $org)
                        <div class="org-col">
                            <div class="team-item">
                                <div class="team-img">
                                    <img src="{{ $org->image }}" alt="{{ $org->name }}" loading="lazy">
                                </div>
                                <div class="team-info">
                                    <h6>{{ $org->name }}</h6>
                                    <span class="position">{{ $org->position }}</span>
                                    @if ($org->field)
                                        <p class="field-name">Bidang {{ $org->field->name }}</p>
                                    @endif
                                    <p class="nip">NIP. {{ $org->NIP }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- LEVEL 4 – Kasubag/Kasi/JFT --}}
        @if ($staff->count())
            <div class="level-section">
                <div class="level-title">Kasubag / Jabatan Fungsional</div>
                <div class="org-row">
                    @foreach ($staff as $org)
                        <div class="org-col">
                            <div class="team-item">
                                <div class="team-img">
                                    <img src="{{ $org->image }}" alt="{{ $org->name }}" loading="lazy">
                                </div>
                                <div class="team-info">
                                    <h6>{{ $org->name }}</h6>
                                    <span class="position">{{ $org->position }}</span>
                                    @if ($org->field)
                                        <p class="field-name">Bidang {{ $org->field->name }}</p>
                                    @endif
                                    <p class="nip">NIP. {{ $org->NIP }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- LEVEL 5 – Koordinator Wilayah --}}
        @if ($kanwil->count())
            <div class="level-section">
                <div class="level-title">Kepala Seksi (Kasi)</div>
                <div class="org-row">
                    @foreach ($kanwil as $org)
                        <div class="org-col">
                            <div class="team-item">
                                <div class="team-img">
                                    <img src="{{ $org->image }}" alt="{{ $org->name }}" loading="lazy">
                                </div>
                                <div class="team-info">
                                    <h6>{{ $org->name }}</h6>
                                    <span class="position">{{ $org->position }}</span>
                                    <p class="nip">NIP. {{ $org->NIP }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
