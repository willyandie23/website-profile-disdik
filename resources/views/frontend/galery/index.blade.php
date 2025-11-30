@extends('frontend.layouts.app')

@section('title', 'Galeri | DISDIK')

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
            margin-top: 30px;
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

        /* Loading state */
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 400px;
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

        /* Gallery container */
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }

        /* Gallery item card */
        .gallery-item {
            position: relative;
            overflow: hidden;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            height: 320px;
            width: 100%;
            background-color: #fff;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.4s ease;
        }

        .gallery-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .gallery-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .gallery-item.visible:hover {
            transform: translateY(-8px);
        }

        /* Image wrapper */
        .gallery-item .image-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        /* Overlay */
        .gallery-item .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 60%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .gallery-item:hover .overlay {
            opacity: 1;
        }

        /* Content */
        .gallery-item .content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            transform: translateY(20px);
            transition: transform 0.4s ease, opacity 0.4s ease;
            opacity: 0;
        }

        .gallery-item:hover .content {
            transform: translateY(0);
            opacity: 1;
        }

        .gallery-item .title {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .gallery-item .view-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            backdrop-filter: blur(5px);
            transition: background 0.3s ease;
        }

        .gallery-item .view-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Badge */
        .gallery-item .badge-gallery {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .gallery-item:hover .badge-gallery {
            opacity: 1;
            transform: translateY(0);
        }

        /* Pagination wrapper */
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

        /* Modal gallery style */
        .gallery-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.95);
            justify-content: center;
            align-items: center;
            z-index: 9999;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-modal.show {
            opacity: 1;
        }

        .gallery-modal .modal-content-wrapper {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .gallery-modal img {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .gallery-modal.show img {
            transform: scale(1);
        }

        .gallery-modal .modal-title {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin-top: 20px;
            text-align: center;
        }

        /* Close button */
        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 28px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10000;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        /* Navigation buttons */
        .nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10000;
        }

        .nav-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .nav-btn.prev {
            left: 20px;
        }

        .nav-btn.next {
            right: 20px;
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

            .gallery-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .gallery-item {
                height: 200px;
            }

            .gallery-item .title {
                font-size: 14px;
            }

            .gallery-item .content {
                padding: 12px;
            }

            .gallery-item .view-btn {
                font-size: 12px;
                padding: 6px 12px;
            }

            .pagination .page-link {
                padding: 8px 12px;
                font-size: 14px;
            }

            .nav-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .close-btn {
                width: 40px;
                height: 40px;
                font-size: 22px;
            }
        }

        /* Untuk tablet view */
        @media (max-width: 992px) and (min-width: 769px) {
            .main-content {
                padding-top: 20px;
                padding-left: 40px;
                padding-right: 40px;
            }

            .gallery-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .gallery-item {
                height: 280px;
            }

            .gallery-item .title {
                font-size: 16px;
            }
        }

        @media (min-width: 1200px) {
            .gallery-container {
                grid-template-columns: repeat(4, 1fr);
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
            <div class="page-header">
                <h2>Galeri Foto</h2>
            </div>
            <hr>

            <!-- Loading State -->
            <div id="loading-state" class="loading-container">
                <div class="loading-spinner"></div>
            </div>

            <!-- Gallery Container -->
            <div class="gallery-container" id="galleryContainer"></div>

            <!-- Pagination -->
            <nav id="pagination-container" class="pagination-wrapper"></nav>
        </div>
    </div>

    <!-- Modal for viewing images -->
    <div class="gallery-modal" id="galleryModal">
        <button class="close-btn" onclick="closeModal()">
            <i class="fas fa-times"></i>
        </button>
        <button class="nav-btn prev" onclick="navigateImage(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="nav-btn next" onclick="navigateImage(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="modal-content-wrapper">
            <img id="modalImage" src="" alt="Gallery Image">
            <div class="modal-title" id="modalTitle"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const itemsPerPage = 8;
        let currentPage = 1;
        let galleryData = [];
        let currentImageIndex = 0;

        // Function to fetch gallery data from API
        async function fetchGalleryData() {
            try {
                const response = await fetch('/api/galery');
                const data = await response.json();

                document.getElementById('loading-state').style.display = 'none';

                if (response.ok && data.data && data.data.length > 0) {
                    galleryData = data.data;
                    displayGalleryItems();
                    displayPagination();
                } else {
                    showEmptyState();
                }
            } catch (error) {
                console.error('Error fetching gallery data:', error);
                document.getElementById('loading-state').style.display = 'none';
                showErrorState();
            }
        }

        // Show empty state
        function showEmptyState() {
            const galleryContainer = document.getElementById('galleryContainer');
            galleryContainer.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <i class="fas fa-images"></i>
                    <h4>Belum ada galeri</h4>
                    <p>Foto-foto akan ditampilkan di sini saat tersedia.</p>
                </div>
            `;
        }

        // Show error state
        function showErrorState() {
            const galleryContainer = document.getElementById('galleryContainer');
            galleryContainer.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <i class="fas fa-exclamation-circle"></i>
                    <h4>Gagal Memuat Galeri</h4>
                    <p>Terjadi kesalahan saat memuat data. Silakan coba lagi.</p>
                    <button class="btn btn-primary mt-3" onclick="location.reload()">
                        <i class="fas fa-refresh me-2"></i>Muat Ulang
                    </button>
                </div>
            `;
        }

        // Function to truncate title
        function truncateTitle(title, maxLength = 40) {
            if (title.length > maxLength) {
                return title.substring(0, maxLength) + '...';
            }
            return title;
        }

        // Function to display the gallery items for the current page
        function displayGalleryItems() {
            const galleryContainer = document.getElementById('galleryContainer');
            galleryContainer.innerHTML = '';

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const currentItems = galleryData.slice(startIndex, endIndex);

            currentItems.forEach((item, index) => {
                const globalIndex = startIndex + index;
                const galleryItem = document.createElement('div');
                galleryItem.classList.add('gallery-item');

                galleryItem.innerHTML = `
                    <div class="image-wrapper" onclick="openModal(${globalIndex})">
                        <img src="${item.image}" alt="${item.title}" onerror="this.src='/images/placeholder-gallery.jpg'">
                        <div class="overlay"></div>
                        <span class="badge-gallery"><i class="fas fa-camera me-1"></i>Foto</span>
                        <div class="content">
                            <div class="title">${truncateTitle(item.title)}</div>
                            <span class="view-btn">
                                <i class="fas fa-expand"></i>
                                Lihat Foto
                            </span>
                        </div>
                    </div>
                `;

                galleryContainer.appendChild(galleryItem);
            });

            addIntersectionObserver();
        }

        // Function to display pagination
        function displayPagination() {
            const paginationContainer = document.getElementById('pagination-container');
            const totalPages = Math.ceil(galleryData.length / itemsPerPage);

            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
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

            // Smart pagination logic
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

            paginationContainer.innerHTML = paginationHtml;
            bindPaginationEvents();
        }

        // Bind pagination click events
        function bindPaginationEvents() {
            const totalPages = Math.ceil(galleryData.length / itemsPerPage);

            document.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const page = this.dataset.page;

                    if (page === 'prev') {
                        currentPage = currentPage > 1 ? currentPage - 1 : 1;
                    } else if (page === 'next') {
                        currentPage = currentPage < totalPages ? currentPage + 1 : totalPages;
                    } else if (page) {
                        currentPage = parseInt(page);
                    }

                    displayGalleryItems();
                    displayPagination();

                    // Scroll to top of gallery
                    document.getElementById('galleryContainer').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });
            });
        }

        // Function to open modal
        function openModal(index) {
            currentImageIndex = index;
            const modal = document.getElementById('galleryModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');

            modalImage.src = galleryData[index].image;
            modalTitle.textContent = galleryData[index].title;

            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);

            // Disable body scroll
            document.body.style.overflow = 'hidden';
        }

        // Function to close the modal
        function closeModal() {
            const modal = document.getElementById('galleryModal');
            modal.classList.remove('show');

            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);

            // Enable body scroll
            document.body.style.overflow = '';
        }

        // Function to navigate images in modal
        function navigateImage(direction) {
            currentImageIndex += direction;

            if (currentImageIndex < 0) {
                currentImageIndex = galleryData.length - 1;
            } else if (currentImageIndex >= galleryData.length) {
                currentImageIndex = 0;
            }

            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');

            modalImage.style.opacity = '0';
            setTimeout(() => {
                modalImage.src = galleryData[currentImageIndex].image;
                modalTitle.textContent = galleryData[currentImageIndex].title;
                modalImage.style.opacity = '1';
            }, 150);
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('galleryModal');
            if (modal.style.display === 'flex') {
                if (e.key === 'Escape') {
                    closeModal();
                } else if (e.key === 'ArrowLeft') {
                    navigateImage(-1);
                } else if (e.key === 'ArrowRight') {
                    navigateImage(1);
                }
            }
        });

        // Close modal when clicking outside image
        document.getElementById('galleryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Intersection Observer for animation
        function addIntersectionObserver() {
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        const itemIndex = Array.from(document.querySelectorAll('.gallery-item')).indexOf(entry.target);
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, itemIndex * 80);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });

            document.querySelectorAll('.gallery-item').forEach(item => {
                observer.observe(item);
            });
        }

        // Call the function when the page loads
        document.addEventListener('DOMContentLoaded', fetchGalleryData);
    </script>
@endpush
