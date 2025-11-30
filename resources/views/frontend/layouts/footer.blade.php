<!-- Footer Start -->
<div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <div class="footer-item">
                        <a href="{{ route('home.index') }}" class="navbar-brand p-0">
                            <img src="" class="site_logo" style="max-height: 650px; max-width: 650px; height: auto; width: auto;" alt="Logo">
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column" id="linkList">
                    <!-- Link akan dimuat di sini secara dinamis -->
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="text-white mb-4">Ikuti Kami</h4>
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                        <a class="btn btn-secondary btn-md-square rounded-circle me-3" id="sm_fb" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="btn btn-secondary btn-md-square rounded-circle me-3" id="sm_ytb" href="">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a class="btn btn-secondary btn-md-square rounded-circle me-3" id="sm_x" href="">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a class="btn btn-secondary btn-md-square rounded-circle me-3" id="sm_ig" href="">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="text-white mb-4">Terhubung Dengan Kami</h4>
                    <a href="" id="cp_agency"></a>
                    <a href="" id="cp_address"><i class="fa fa-map-marker-alt me-2"></i></a>
                    <a href="" id="cp_email"><i class="fas fa-envelope me-2"></i></a>
                    <a href="" id="cp_phone"><i class="fas fa-phone me-2"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright Start -->
    <div class="container-fluid copyright py-4">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6 text-center text-md-start mb-md-0">
                    <span class="text-body"><a href="#" class="border-bottom text-white"><i class="fas fa-copyright text-light me-2"></i>2025</a>, Dinas Pendidikan Kab. Katingan.</span>
                </div>
                <div class="col-md-6 text-center text-md-end text-body">
                    <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                    <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                    <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                    Dibuat dengan ♥ oleh <a class="border-bottom text-white" href="https://diskominfopersantik.katingankab.go.id/">Diskominfostandi Kab. Katingan</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->
</div>
<!-- Footer End -->


@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/api/links',
                type: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        const links = response.data;
                        let linkHtml = '<h4 class="text-white mb-4">Link Lainnya</h4>';
                        
                        links.forEach(link => {
                            linkHtml += `
                                <a href="${link.link}"><i class="fas fa-angle-right me-2"></i> ${link.name}</a>
                            `;
                        });

                        $('#linkList').html(linkHtml);
                    } else {
                        console.log('Gagal memuat link');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching links:', xhr.responseText);
                }
            });
        });
    </script>
@endpush