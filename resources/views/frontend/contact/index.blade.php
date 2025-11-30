@extends('frontend.layouts.app')

@section('title', 'Hubungi Kami | DISDIK')

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

        /* Grid layout for form and map */
        .contact-container {
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .contact-form {
            flex: 1;
        }

        .map-container {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        iframe {
            max-width: 100%;
            border: 0;
            height: 450px;
        }

        /* Responsiveness for mobile */
        @media (max-width: 768px) {
            .contact-container {
                flex-direction: column;
                align-items: center;
            }

            .contact-form {
                margin-bottom: 30px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container main-content mb-3">
        <h2>Hubungi Kami</h2>
        <hr>

        <div class="contact-container">
            <!-- Left side: Contact Form -->
            <div class="contact-form">
                <form action="{{ route('hubungi.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subjek</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            </div>

            <!-- Right side: Google Map -->
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4029.2194329336244!2d113.415915!3d-1.8754534999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dfce32cedea6d59%3A0xdb5a5b507ce4af43!2sDINAS%20PENDIDIKAN%20KABUPATEN%20KATINGAN!5e1!3m2!1sid!2sid!4v1764511631572!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Pesan Berhasil Dikirim!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
    </script>
@endpush
