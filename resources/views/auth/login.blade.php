{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | Website Profile Disbudporapar</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Mantis is made using Bootstrap 5 design framework.">
    <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template">
    <meta name="author" content="CodedThemes">

    <!-- [Favicon] icon -->
    <link rel="icon" id="site_favicon" href="" type="image/x-icon">
    <!-- [Google Font] Family -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
    <!-- [Tabler Icons] -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <!-- [Feather Icons] -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <!-- [Font Awesome Icons] -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <!-- [Material Icons] -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
</head>
<body>
    <!-- [Pre-loader] -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="auth-header">
                    <a href="#"><img src="" id="site_logo" alt="Logo" style="height: 100px;"></a>
                    <div class="col-auto my-1">
                        <a href="/" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-globe me-1"></i> Web Publik
                        </a>
                    </div>
                </div>
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <!-- Session Status -->
                        <x-auth-session-status class="alert alert-info mb-4" :status="session('status')" />
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Masuk ke Akun Anda</h3>
                            <p class="text-muted">Silakan masukkan email dan password</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" placeholder="Alamat Email" :value="old('email')" name="email" required autofocus autocomplete="email">
                                <label for="email">Alamat Email</label>
                                <x-input-error :messages="$errors->get('email')" class="invalid-feedback" />
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" placeholder="Password" name="password" required autocomplete="current-password">
                                <label for="password">Kata Sandi</label>
                                <x-input-error :messages="$errors->get('password')" class="invalid-feedback" />
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Ingat Saya</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk
                            </button>
                        </form>
                    </div>
                </div>
                <div class="auth-footer row justify-content-center">
                    <div class="col-auto my-1 text-center">
                        {{-- Footer content commented out --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Layout scripts -->
    <script>
        layout_change('light');
        change_box_container('false');
        layout_rtl_change('false');
        preset_change("preset-1");
        font_change("Public-Sans");
    </script>

    <!-- Fetch identities -->
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/api/identities',
                type: 'GET',
                success: function(response) {
                    console.log('API Response:', response);
                    if (response.success && response.data) {
                        if (response.data.site_favicon) {
                            $('#site_favicon').attr('href', response.data.site_favicon);
                            // console.log('Favicon set to:', response.data.site_favicon);
                        } else {
                            console.log('No favicon in response');
                        }
                        if (response.data.site_logo) {
                            $('#site_logo').attr('src', response.data.site_logo);
                            // console.log('Logo set to:', response.data.site_logo);
                        } else {
                            console.log('No logo in response');
                        }
                    } else {
                        console.log('Invalid response format:', response);
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching Identities data:', xhr.responseText);
                    Swal.fire('Error!', 'Failed to load Identities data.', 'error');
                }
            });
        });
    </script>
</body>
</html>