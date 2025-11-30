@extends('backend.layouts.app')

@section('title', 'Beranda')

@push('css')
    <style>
        .card {
            border-radius: 10px;
        }
        .card-body {
            padding: 20px;
        }
        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .list-group-item {
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 10px;
            padding: 15px;
            background-color: #f8f9fa;
        }
        .avatar {
            width: 40px;
            height: 40px;
        }
        .avatar img {
            width: 100%;
            height: 100%;
        }
        .mb-3 {
            margin-bottom: 1rem;
        }
        .fa-3x {
            font-size: 3rem;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <!-- Box untuk jumlah organisasi -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x mb-3"></i> <!-- Ikon Organisasi -->
                    <h4 style="color: white">{{ $organizationCount }}</h4>
                    <p>Jumlah Pegawai</p>
                </div>
            </div>
        </div>

        <!-- Box untuk jumlah berita -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-newspaper fa-3x mb-3"></i> <!-- Ikon Berita -->
                    <h4 style="color: white">{{ $newsCount }}</h4>
                    <p>Jumlah Berita</p>
                </div>
            </div>
        </div>

        <!-- Box untuk jumlah bidang -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-briefcase fa-3x mb-3"></i> <!-- Ikon Bidang -->
                    <h4 style="color: white">{{ $fieldCount }}</h4>
                    <p>Jumlah Bidang</p>
                </div>
            </div>
        </div>

        <!-- Box untuk total unduhan -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-download fa-3x mb-3"></i> <!-- Ikon Unduhan -->
                    <h4 style="color: white">{{ $downloadCount }}</h4>
                    <p>Total Unduhan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesan terbaru -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4>Pesan Terbaru</h4>
                </div>
                <div class="card-body">
                    @if ($latestContacts->isEmpty())
                        <p>Tidak ada pesan terbaru.</p>
                    @else
                        <ul class="list-group">
                            @foreach ($latestContacts as $contact)
                            <li class="list-group-item d-flex align-items-center">
                                <div class="avatar mr-3">
                                        <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" class="rounded-circle" alt="Avatar"> <!-- Avatar placeholder -->
                                    </div>
                                    <div class="m-3">
                                        <h5><strong>{{ $contact->name }}</strong></h5>
                                        <p><strong>Subjek:</strong> {{ $contact->subject }}</p>
                                        <p><strong>Pesan:</strong> {{ Str::limit($contact->message, 100) }}...</p>
                                        <small><strong>Dikirim pada:</strong> {{ $contact->created_at->format('d F Y H:i') }}</small>
                                        <div>
                                            <a href="{{ route('contact.show', $contact->id) }}" class="btn btn-info btn-sm mt-2">Lihat Pesan</a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
