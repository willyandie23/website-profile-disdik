@extends('backend.layouts.app')

@section('title', 'Detail Hubungi Kami')

@push('css')
    <style>
        .card-header {
            background-color: #007bff;
            color: white;
        }

        .card-body {
            background-color: #f9f9f9;
        }

        h3, h4, h5 {
            font-weight: 600;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        .btn-primary {
            font-size: 16px;
            padding: 10px 20px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h3 class="text-primary">
                            <strong>Pengirim: </strong> {{ $contact->name }}
                        </h3>
                        <h4 class="text-muted">
                            <strong>Email: </strong> {{ $contact->email }}
                        </h4>
                    </div>
                    <div class="mb-4">
                        <strong class="text-dark">Dikirim: </strong>
                        <span class="text-muted">{{ $contact->created_at->format('d F Y H:i') }}</span>
                    </div>
                    <div class="mb-4">
                        <h5 class="text-secondary">
                            <strong>Subjek: </strong> {{ $contact->subject }}
                        </h5>
                    </div>
                    <div class="mb-4">
                        <h5 class="text-dark">
                            <strong>Pesan: </strong>
                        </h5>
                        <p class="bg-light p-3 rounded shadow-sm">
                            {{ $contact->message }}
                        </p>
                    </div>
                    <a href="{{ route('contact.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
