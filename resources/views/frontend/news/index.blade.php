@extends('frontend.layouts.app')

@section('title', 'Berita | DISDIK')

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
            min-height: 80vh;
            background-color: #f8f9fa;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #6c757d;
            font-size: 16px;
        }

        /* Styling untuk daftar berita */
        .news-card {
            border: none;
            border-radius: 12px;
            margin-bottom: 25px;
            padding: 0;
            background-color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: row;
            overflow: hidden;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease;
        }

        .news-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .news-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .news-card.visible:hover {
            transform: translateY(-5px);
        }

        /* Gambar pada card */
        .news-card .image-wrapper {
            flex-shrink: 0;
            width: 280px;
            height: 220px;
            overflow: hidden;
        }

        .news-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .news-card:hover img {
            transform: scale(1.05);
        }

        /* Konten berita */
        .news-card .content {
            flex-grow: 1;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Badge kategori */
        .news-card .badge-category {
            display: inline-block;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
            width: fit-content;
        }

        /* Styling untuk judul */
        .news-card .title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #2c3e50;
            line-height: 1.4;
            transition: color 0.3s ease;
        }

        .news-card:hover .title {
            color: #007bff;
        }

        /* Meta info (Penulis dan tanggal) */
        .news-card .meta-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .news-card .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #6c757d;
        }

        .news-card .meta-item i {
            color: #007bff;
            font-size: 14px;
        }

        /* Deskripsi */
        .news-card .description {
            font-size: 15px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Tombol detail */
        .news-card .btn-detail {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 25px;
            transition: all 0.3s ease;
            width: fit-content;
        }

        .news-card .btn-detail:hover {
            background: linear-gradient(135deg, #0056b3, #003d82);
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
        }

        .news-card .btn-detail i {
            transition: transform 0.3s ease;
        }

        .news-card .btn-detail:hover i {
            transform: translateX(3px);
        }

        /* Loading state */
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 300px;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 60px;
            margin-bottom: 20px;
            color: #dee2e6;
        }

        /* Styling untuk pagination */
        .pagination-wrapper {
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            gap: 8px;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-link {
            background-color: white;
            border: 2px solid #e9ecef;
            color: #495057;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            text-decoration: none;
        }

        .pagination .page-link:hover {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-color: #007bff;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            background-color: #f8f9fa;
            color: #adb5bd;
            cursor: not-allowed;
            border-color: #e9ecef;
        }

        .pagination .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: none;
        }

        .pagination .prev-next {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            font-weight: 700;
        }

        .pagination .prev-next:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Responsivitas untuk layar lebih kecil (Mobile View) */
        @media (max-width: 768px) {
            .main-content {
                padding-top: 20px;
                padding-left: 15px;
                padding-right: 15px;
            }

            .page-header h2 {
                font-size: 24px;
            }

            .news-card {
                flex-direction: column;
            }

            .news-card .image-wrapper {
                width: 100%;
                height: 200px;
            }

            .news-card .content {
                padding: 20px;
            }

            .news-card .title {
                font-size: 18px;
            }

            .news-card .meta-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .news-card .description {
                font-size: 14px;
                -webkit-line-clamp: 2;
            }

            .news-card .btn-detail {
                width: 100%;
                justify-content: center;
            }

            .pagination .page-link {
                padding: 8px 12px;
                font-size: 14px;
            }
        }

        /* Untuk tablet view */
        @media (max-width: 992px) and (min-width: 769px) {
            .main-content {
                padding-top: 20px;
                padding-left: 40px;
                padding-right: 40px;
            }

            .news-card .image-wrapper {
                width: 220px;
                height: 180px;
            }

            .news-card .title {
                font-size: 18px;
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

    <div class="row main-content">
        <div class="col-md-12">
            <div class="card-body">
                <div class="page-header">
                    <h2>Berita</h2>
                </div>
                <hr>
                
                <!-- Loading State -->
                <div id="loading-state" class="loading-container">
                    <div class="loading-spinner"></div>
                </div>
                
                <!-- Tempat untuk menampilkan daftar berita -->
                <div id="news-list"></div>
                
                <!-- Pagination -->
                <nav id="pagination-container" class="pagination-wrapper"></nav>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const itemsPerPage = 10;
            let currentPage = 1;
            let allNews = [];

            // Function untuk strip HTML tags
            function stripHtmlTags(html) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html || '';
                return tempDiv.textContent || tempDiv.innerText || '';
            }

            // Function untuk format tanggal
            function formatDate(dateString) {
                const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                const date = new Date(dateString);
                return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
            }

            // Function untuk memotong judul
            function truncateTitle(title) {
                if (title.length > 60) {
                    return title.substring(0, 60) + '...';
                }
                return title;
            }

            // Function untuk memotong deskripsi (strip HTML dan potong)
            function truncateDescription(description, maxLength = 150) {
                const plainText = stripHtmlTags(description);
                if (plainText.length > maxLength) {
                    return plainText.substring(0, maxLength) + '...';
                }
                return plainText;
            }

            // Menampilkan berita berdasarkan halaman saat ini
            function displayNews(page) {
                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const pageNews = allNews.slice(start, end);
                
                if (pageNews.length === 0) {
                    $('#news-list').html(`
                        <div class="empty-state">
                            <i class="fas fa-newspaper"></i>
                            <h4>Belum ada berita</h4>
                            <p>Berita akan ditampilkan di sini saat tersedia.</p>
                        </div>
                    `);
                    return;
                }

                let newsHtml = '';
                pageNews.forEach(function(item, index) {
                    const formattedDate = formatDate(item.created_at);
                    const truncatedTitle = truncateTitle(item.title);
                    const truncatedDesc = truncateDescription(item.description, 180);

                    newsHtml += `
                        <div class="news-card" data-id="${item.id}">
                            <div class="image-wrapper">
                                <img src="${item.image}" alt="${item.title}">
                            </div>
                            <div class="content">
                                <div>
                                    <div class="title">${truncatedTitle}</div>
                                    <div class="meta-info">
                                        <span class="meta-item">
                                            <i class="fas fa-user"></i>
                                            ${item.author}
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            ${formattedDate}
                                        </span>
                                    </div>
                                    <div class="description">${truncatedDesc}</div>
                                </div>
                                <button class="btn-detail" onclick="window.location.href='/berita/${item.id}'">
                                    Baca Selengkapnya
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });

                $('#news-list').html(newsHtml);
                addIntersectionObserver();
            }

            // Function untuk render pagination
            function renderPagination() {
                const totalPages = Math.ceil(allNews.length / itemsPerPage);
                
                if (totalPages <= 1) {
                    $('#pagination-container').html('');
                    return;
                }

                let paginationHtml = `
                    <ul class="pagination">
                        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                            <a class="page-link prev-next" href="#" data-page="prev">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                `;

                // Logic untuk menampilkan halaman
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, currentPage + 2);

                if (startPage > 1) {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="1">1</a>
                        </li>
                    `;
                    if (startPage > 2) {
                        paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
                        </li>
                    `;
                }

                paginationHtml += `
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link prev-next" href="#" data-page="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
                `;

                $('#pagination-container').html(paginationHtml);
                bindPaginationEvents();
            }

            // Bind pagination click events
            function bindPaginationEvents() {
                const totalPages = Math.ceil(allNews.length / itemsPerPage);
                
                $('.page-link').off('click').on('click', function(event) {
                    event.preventDefault();
                    const page = $(this).data('page');
                    
                    if (page === 'prev') {
                        currentPage = currentPage > 1 ? currentPage - 1 : 1;
                    } else if (page === 'next') {
                        currentPage = currentPage < totalPages ? currentPage + 1 : totalPages;
                    } else if (page) {
                        currentPage = parseInt(page);
                    }

                    displayNews(currentPage);
                    renderPagination();
                    
                    // Scroll to top of news list
                    $('html, body').animate({
                        scrollTop: $('#news-list').offset().top - 100
                    }, 300);
                });
            }

            // Mengambil data berita menggunakan AJAX
            $.ajax({
                url: '/api/news',
                type: 'GET',
                success: function(response) {
                    $('#loading-state').hide();
                    allNews = response.data || [];
                    
                    displayNews(currentPage);
                    renderPagination();
                },
                error: function(xhr, status, error) {
                    $('#loading-state').hide();
                    $('#news-list').html(`
                        <div class="empty-state">
                            <i class="fas fa-exclamation-circle"></i>
                            <h4>Gagal Memuat Berita</h4>
                            <p>Terjadi kesalahan saat memuat data. Silakan coba lagi.</p>
                            <button class="btn btn-primary mt-3" onclick="location.reload()">
                                <i class="fas fa-refresh me-2"></i>Muat Ulang
                            </button>
                        </div>
                    `);
                }
            });

            // Fungsi untuk menambahkan Intersection Observer
            function addIntersectionObserver() {
                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach((entry, index) => {
                        if (entry.isIntersecting) {
                            // Delay animasi berdasarkan index
                            setTimeout(() => {
                                entry.target.classList.add('visible');
                            }, index * 100);
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '50px'
                });

                document.querySelectorAll('.news-card').forEach(card => {
                    observer.observe(card);
                });
            }
        });
    </script>
@endpush
