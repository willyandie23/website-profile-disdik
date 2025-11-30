<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title id="dynamic-title">@yield('title', 'Loading...')</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicon -->
        <link rel="icon" id="site_favicon" href="" type="image/x-icon">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wdth,wght@0,75..100,300..800;1,75..100,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet"> 
        
        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="{{ asset('frontend/css/animate/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('frontend/css/owlcarousel/owl.carousel.min.css') }}" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.min.css">
        <!-- DataTables Responsive CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.min.css">

        @stack('css')

        <!-- Template Stylesheet -->
        {{-- <link href="css/style.css" rel="stylesheet"> --}}
        <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
        @stack('styles')

    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        @if ($navbar)
            <!-- Navbar -->
            @include('frontend.layouts.navbar')
        @endif

        @yield('content')

        @if ($navbar)
            <!-- Navbar -->
            @include('frontend.layouts.footer')
        @endif

        <!-- Back to Top -->
        <a href="#" class="btn btn-secondary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
    <!-- JavaScript Libraries -->
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>

    <!-- DataTables Bootstrap5 Integration -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Responsive -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.bootstrap5.min.js"></script>
    
    <script src="{{ asset('frontend/js/wow/wow.min.js') }}"></script>
    <script src="{{ asset('frontend/js/easing/easing.min.js') }}"></script>
    <script src="{{ asset('frontend/js/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/js/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('frontend/js/owlcarousel/owl.carousel.min.js') }}"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Template Javascript -->
    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/api/identities',
                type: 'GET',
                success: function(response) {
                    // console.log('API Response:', response);
                    if (response.success && response.data) {
                        const routeTitle = $('#dynamic-title').text();
                        if (routeTitle === 'Loading...') {
                            if (response.data.site_heading) {
                                $('#dynamic-title').text(response.data.site_heading);
                            }
                        }

                        if (response.data.site_favicon) {
                            $('#site_favicon').attr('href', response.data.site_favicon);
                            // console.log('Favicon set to:', response.data.site_favicon);
                        } else {
                            console.log('No favicon in response');
                        }
                        if (response.data.site_logo) {
                            $('.site_logo').attr('src', response.data.site_logo).show();
                            // console.log('Logo set to:', response.data.site_logo);
                        } else {
                            console.log('No logo in response');
                        }
                        
                        // Set social media links
                        if (response.data.sm_facebook) {
                            $('#sm_fb').attr('href', response.data.sm_facebook);
                            // console.log('Facebook link set to:', response.data.sm_facebook);
                        }
                        if (response.data.sm_instagram) {
                            $('#sm_ig').attr('href', response.data.sm_instagram);
                            // console.log('Instagram link set to:', response.data.sm_instagram);
                        }
                        if (response.data.sm_youtube) {
                            $('#sm_ytb').attr('href', response.data.sm_youtube);
                            // console.log('YouTube link set to:', response.data.sm_youtube);
                        }
                        if (response.data.sm_x) {
                            $('#sm_x').attr('href', response.data.sm_x);
                            // console.log('YouTube link set to:', response.data.sm_youtube);
                        }

                        // Additional fields like cp_address, cp_phone, etc.
                        if (response.data.cp_address) {
                            $('#cp_address').html('<i class="fa fa-map-marker-alt me-2"></i>' + response.data.cp_address);;
                            // console.log('Contact address set to:', response.data.cp_address);
                        }
                        if (response.data.cp_phone) {
                            $('#cp_phone').html('<i class="fas fa-phone me-2"></i>' + response.data.cp_phone);
                            // console.log('Phone number set to:', response.data.cp_phone);
                        }
                        if (response.data.cp_email) {
                            $('#cp_email').html('<i class="fas fa-envelope me-2"></i>' + response.data.cp_email);
                            // console.log('Email address set to:', response.data.cp_email);
                        }
                        if (response.data.cp_agency) {
                            $('#cp_agency').text(response.data.cp_agency);
                            // console.log('Agency name set to:', response.data.cp_agency);
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
    @stack('scripts')    
    </body>

</html>