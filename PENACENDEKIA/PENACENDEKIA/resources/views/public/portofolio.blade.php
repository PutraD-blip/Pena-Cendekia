@extends('layouts.app')

@section('title', 'Portofolio Terbitan Buku — PT. Pena Cendekia Pustaka')

@section('content')
<style>
    /* =========================================================
       PORTOFOLIO BUKU - PREMIUM CLEAN UI/UX (Pena Cendekia)
       ========================================================= */
    .portofolio-page-wrap {
        font-family: 'Poppins', sans-serif;
        background-color: #FAF8F5;
    }

    /* Hero Header */
    .portofolio-hero {
        position: relative;
        background: linear-gradient(135deg, rgba(16, 21, 29, 0.95) 0%, rgba(22, 28, 40, 0.90) 55%, rgba(245, 166, 35, 0.22) 100%),
                    url('{{ asset('images/hero-bg.jpg') }}') center/cover no-repeat;
        padding-top: 85px;
        padding-bottom: 95px;
        color: #ffffff;
    }

    .portofolio-hero-title {
        font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .portofolio-hero .breadcrumb-item,
    .portofolio-hero .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.9rem;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .portofolio-hero .breadcrumb-item a:hover {
        color: #F5A623;
    }

    .portofolio-hero .breadcrumb-item.active {
        color: #F5A623;
        font-weight: 600;
    }

    .portofolio-hero .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.45);
        content: "»";
        padding: 0 8px;
    }

    /* Clean Elevated Toolbar */
    .catalog-toolbar-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 22px 28px;
        box-shadow: 0 10px 30px rgba(16, 21, 29, 0.05);
        border: 1px solid #EAE6DF;
        margin-top: -45px;
        position: relative;
        z-index: 10;
    }

    /* Filter Pills */
    .filter-pills-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .filter-btn {
        background: #F9FAFB;
        border: 1px solid #E5E7EB;
        color: #4B5563;
        font-size: 0.84rem;
        font-weight: 600;
        padding: 7px 18px;
        border-radius: 50px;
        transition: all 0.2s ease;
        white-space: nowrap;
        cursor: pointer;
    }

    .filter-btn:hover {
        background: #FFFDF8;
        border-color: #F5A623;
        color: #17120a;
    }

    .filter-btn.active {
        background-color: #F5A623;
        color: #17120a;
        border-color: #F5A623;
        box-shadow: 0 4px 14px rgba(245, 166, 35, 0.28);
    }

    /* Search Input */
    .search-input-wrap {
        position: relative;
    }

    .search-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
        font-size: 0.95rem;
    }

    .search-input {
        padding-left: 40px !important;
        border-radius: 50px;
        border: 1px solid #E5E7EB;
        background-color: #F9FAFB;
        font-size: 0.88rem;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        border-color: #F5A623;
        box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.2);
        background-color: #ffffff;
        outline: none;
    }

    /* Unified Clean Book Card */
    .book-card-item {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #EAE6DF;
        box-shadow: 0 8px 24px rgba(16, 21, 29, 0.04);
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .book-card-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 36px rgba(16, 21, 29, 0.1);
        border-color: #F5A623;
    }

    /* Showcase Studio Area for Book Cover */
    .book-cover-showcase {
        background: linear-gradient(180deg, #F9F7F3 0%, #F3EFE6 100%);
        padding: 26px 18px;
        text-align: center;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 275px;
        border-bottom: 1px solid #EFECE6;
    }

    .book-3d-box {
        width: 155px;
        height: 230px;
        border-radius: 4px 10px 10px 4px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 12px 26px rgba(43, 33, 24, 0.22), -2px 0 8px rgba(43, 33, 24, 0.1);
        transition: transform 0.3s ease;
    }

    .book-card-item:hover .book-3d-box {
        transform: scale(1.04) rotate(-1deg);
    }

    .book-3d-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Spine effect */
    .book-3d-box::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 6px;
        background: linear-gradient(to right, rgba(0,0,0,0.4), rgba(255,255,255,0.2) 30%, rgba(0,0,0,0.15) 60%, transparent);
        z-index: 2;
    }

    /* Card Details */
    .book-info-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .book-category-pill {
        display: inline-block;
        background: rgba(245, 166, 35, 0.14);
        color: #B47407;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 50px;
        margin-bottom: 8px;
        align-self: flex-start;
        border: 1px solid rgba(245, 166, 35, 0.3);
    }

    .book-card-title {
        font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.05rem;
        color: #1F2937;
        line-height: 1.38;
        margin-bottom: 4px;
    }

    .book-card-sub {
        font-size: 0.8rem;
        color: #6B7280;
        line-height: 1.45;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-meta-list {
        font-size: 0.8rem;
        color: #4B5563;
        margin-bottom: 16px;
        margin-top: auto;
    }

    .book-meta-item {
        display: flex;
        align-items: flex-start;
        gap: 7px;
        margin-bottom: 5px;
    }

    .book-meta-item i {
        color: #F5A623;
        font-size: 0.9rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Card Action Buttons */
    .card-actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        padding-top: 14px;
        border-top: 1px solid #F3F4F6;
    }

    .btn-detail-book {
        background-color: #F3F4F6;
        color: #374151;
        font-size: 0.82rem;
        font-weight: 600;
        border-radius: 8px;
        padding: 9px 12px;
        border: 1px solid #E5E7EB;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        white-space: nowrap;
    }

    .btn-detail-book:hover {
        background-color: #E5E7EB;
        color: #111827;
    }

    .btn-order-book {
        background-color: #F5A623;
        color: #17120a;
        font-size: 0.82rem;
        font-weight: 700;
        border-radius: 8px;
        padding: 9px 12px;
        border: none;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        box-shadow: 0 4px 12px rgba(245, 166, 35, 0.25);
        white-space: nowrap;
    }

    .btn-order-book:hover {
        background-color: #E09419;
        color: #17120a;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(245, 166, 35, 0.35);
    }

    /* Bottom CTA Banner (Mewah & Harmonis) */
    .portofolio-cta-banner {
        background: linear-gradient(135deg, #10151D 0%, #1E2530 100%);
        border-radius: 18px;
        padding: 42px 48px;
        color: #ffffff;
        border: 1px solid rgba(245, 166, 35, 0.3);
        box-shadow: 0 16px 36px rgba(16, 21, 29, 0.15);
        position: relative;
        overflow: hidden;
    }

    .portofolio-cta-banner::after {
        content: "";
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        background: rgba(245, 166, 35, 0.12);
        border-radius: 50%;
        pointer-events: none;
    }

    .btn-cta-banner {
        background-color: #F5A623;
        color: #17120a;
        border-radius: 8px;
        font-weight: 700;
        padding: 12px 28px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
        border: none;
        white-space: nowrap;
        box-shadow: 0 4px 14px rgba(245, 166, 35, 0.3);
    }

    .btn-cta-banner:hover {
        background-color: #E09419;
        color: #17120a;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(245, 166, 35, 0.45);
    }

    @media (max-width: 991.98px) {
        .catalog-toolbar-card {
            padding: 18px 20px;
        }
        .filter-pills-row {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 6px;
        }
    }
</style>

<div class="portofolio-page-wrap">
    <!-- Hero Header -->
    <section class="portofolio-hero">
        <div class="container text-center text-md-start">
            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.5px;">
                KATALOG PENERBITAN RESMI
            </span>
            <h1 class="display-4 portofolio-hero-title mb-2 text-white">Portofolio Buku Pena Cendekia</h1>
            <p class="text-white-50 mb-3 max-w-2xl" style="font-size: 1.02rem; line-height: 1.65; max-width: 820px;">
                Pena Cendekia adalah penerbit independen yang berfokus pada buku-buku budaya, sastra, pemikiran, psikologi perkembangan, buku ajar dan buku referensi, serta menerima buku-buku fiksi seperti prosa, novel, dan puisi baik terjemahan maupun naskah asli dalam bahasa Indonesia.
            </p>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center justify-content-md-start mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Portofolio Buku</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Toolbar: Filter Kategori & Search Bar -->
    <div class="container">
        <div class="catalog-toolbar-card">
            <div class="row g-3 align-items-center justify-content-between">
                <!-- Filter Buttons -->
                <div class="col-xl-8 col-lg-7">
                    <div class="filter-pills-row">
                        <button type="button" class="filter-btn active" data-category="all">Semua Koleksi</button>
                        <button type="button" class="filter-btn" data-category="Buku Ajar / Kesehatan">Buku Ajar &amp; Kesehatan</button>
                        <button type="button" class="filter-btn" data-category="Pemikiran Islam &amp; Kebangsaan">Pemikiran &amp; Humaniora</button>
                        <button type="button" class="filter-btn" data-category="Hukum Islam &amp; Keluarga">Hukum &amp; Syariah</button>
                        <button type="button" class="filter-btn" data-category="Pendidikan Islam &amp; Teknologi">Pendidikan &amp; Teknologi</button>
                    </div>
                </div>

                <!-- Live Search Box -->
                <div class="col-xl-4 col-lg-5">
                    <div class="search-input-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" id="catalogSearchInput" class="form-control search-input" placeholder="Cari judul buku atau penulis...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Book Grid Section -->
    <section class="py-5">
        <div class="container">
            <!-- Count Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="fw-bold mb-0 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Menampilkan <span id="displayedBooksCount" class="text-warning fw-bold">{{ count($books) }}</span> Judul Buku
                </h5>
                <a href="{{ route('home') }}#paket" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold">
                    <i class="bi bi-send me-1"></i> Terbitkan Buku Anda
                </a>
            </div>

            <!-- Grid Cards -->
            <div class="row g-4" id="booksGridContainer">
                @foreach($books as $book)
                <div class="col-sm-6 col-lg-3 book-item-col" 
                     data-category="{{ $book['category'] }}" 
                     data-title="{{ strtolower($book['title']) }}" 
                     data-author="{{ strtolower($book['author']) }}">
                    <div class="book-card-item">
                        <!-- Book Cover Showcase with Warm Clean Backdrop -->
                        <a href="{{ route('buku.detail', $book['id']) }}" class="book-cover-showcase text-decoration-none" title="Lihat detail buku {{ $book['title'] }}">
                            <div class="book-3d-box">
                                <img src="{{ asset($book['cover']) }}" alt="Cover {{ $book['title'] }}">
                            </div>
                        </a>

                        <!-- Info & Metadata -->
                        <div class="book-info-body">
                            <span class="book-category-pill">{{ $book['category'] }}</span>
                            <h4 class="book-card-title">
                                <a href="{{ route('buku.detail', $book['id']) }}" class="text-dark text-decoration-none" style="transition: color 0.2s ease;">
                                    {{ $book['title'] }}
                                </a>
                            </h4>
                            <p class="book-card-sub">{{ $book['sub'] }}</p>

                            <div class="book-meta-list">
                                <div class="book-meta-item">
                                    <i class="bi bi-person-fill"></i>
                                    <span class="text-truncate" title="{{ $book['author'] }}"><strong>Penulis:</strong> {{ $book['author'] }}</span>
                                </div>
                                <div class="book-meta-item">
                                    <i class="bi bi-upc-scan"></i>
                                    <span><strong>ISBN:</strong> {{ $book['isbn'] }} ({{ $book['year'] }})</span>
                                </div>
                                <div class="book-meta-item">
                                    <i class="bi bi-book"></i>
                                    <span>{{ $book['pages'] }} &bull; {{ $book['size'] }}</span>
                                </div>
                            </div>

                            <!-- Buttons (Side-by-Side: Compact & Professional) -->
                            <div class="card-actions-grid mt-auto">
                                <a href="{{ route('buku.detail', $book['id']) }}" class="btn btn-detail-book" title="Buka Halaman Detail Buku">
                                    <i class="bi bi-info-circle"></i> Detail
                                </a>
                                <a href="https://wa.me/6285785522283?text={{ urlencode("Halo Admin PT. Pena Cendekia Pustaka, saya tertarik dengan buku '" . $book['title'] . "' (ISBN: " . $book['isbn'] . ") dan ingin konsultasi.") }}" 
                                   target="_blank" 
                                   rel="noopener" 
                                   class="btn btn-order-book">
                                    Konsultasi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Empty State jika pencarian tidak ditemukan -->
            <div id="noBooksFound" class="text-center py-5 d-none">
                <div class="p-5 bg-white rounded-4 border shadow-sm mx-auto" style="max-width: 480px;">
                    <i class="bi bi-book-half display-3 text-muted mb-3 d-block"></i>
                    <h5 class="fw-bold text-dark">Buku Tidak Ditemukan</h5>
                    <p class="text-muted small mb-3">Tidak ada judul buku atau nama penulis yang cocok dengan pencarian Anda.</p>
                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-4 text-dark fw-bold" onclick="resetFilters()">
                        Reset Pencarian
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Banner -->
    <section class="py-5">
        <div class="container">
            <div class="portofolio-cta-banner">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <h3 class="fw-bold mb-2 text-white" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(1.3rem, 2.4vw, 1.85rem);">
                            Ingin Naskah Anda Masuk ke Portofolio Penerbitan Kami?
                        </h3>
                        <p class="text-white-50 mb-0 small" style="max-width: 600px;">
                            Wujudkan gagasan dan hasil penelitian Anda menjadi buku resmi ber-ISBN dengan proses kilat 6 minggu bersama PT. Pena Cendekia Pustaka.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('login') }}" class="btn-cta-banner">
                            <span>Mulai Terbitkan Buku</span>
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detail & Sinopsis Buku -->
<div class="modal fade" id="portfolioBookDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <div>
                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill small fw-semibold" id="modalCategoryBadge">Kategori</span>
                    <h5 class="modal-title fw-bold text-dark mt-1" id="modalTitle" style="font-family: 'Plus Jakarta Sans', sans-serif;">Judul Buku</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-5 text-center">
                        <div class="book-3d-box mx-auto" style="width: 180px; height: 265px;">
                            <img id="modalCover" src="" alt="Cover Buku">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h6 class="text-muted small mb-2" id="modalSubtitle">Subjudul Buku</h6>
                        
                        <div class="p-3 bg-light rounded-3 mb-3 small">
                            <div class="mb-1"><strong>Penulis:</strong> <span id="modalAuthor">-</span></div>
                            <div class="mb-1"><strong>Editor:</strong> <span id="modalEditor">-</span></div>
                            <div class="mb-1"><strong>ISBN:</strong> <span id="modalIsbn">-</span></div>
                            <div class="mb-1"><strong>Tahun Terbit:</strong> <span id="modalYear">-</span></div>
                            <div class="mb-0"><strong>Spesifikasi:</strong> <span id="modalSpecs">-</span></div>
                        </div>

                        <h6 class="fw-bold text-dark mb-2">Sinopsis Buku</h6>
                        <p class="text-muted small" style="line-height: 1.7;" id="modalSynopsis">
                            Sinopsis naskah buku...
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-2 px-4 bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-3 btn-sm" data-bs-dismiss="modal">Tutup</button>
                <a id="modalWaBtn" href="" target="_blank" rel="noopener" class="btn btn-warning rounded-pill px-4 btn-sm fw-bold text-dark" style="background-color: #F5A623; border: none;">
                    <i class="bi bi-whatsapp me-1"></i> Konsultasi Buku Ini
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function openBookDetailModal(book) {
        document.getElementById('modalCategoryBadge').textContent = book.category;
        document.getElementById('modalTitle').textContent = book.title;
        document.getElementById('modalSubtitle').textContent = book.sub;
        document.getElementById('modalCover').src = '{{ asset('') }}' + book.cover;
        document.getElementById('modalAuthor').textContent = book.author;
        document.getElementById('modalEditor').textContent = book.editor || '-';
        document.getElementById('modalIsbn').textContent = book.isbn;
        document.getElementById('modalYear').textContent = book.year;
        document.getElementById('modalSpecs').textContent = book.pages + ' • ' + book.size;
        document.getElementById('modalSynopsis').textContent = book.synopsis;

        const text = `Halo Admin PT. Pena Cendekia Pustaka, saya ingin konsultasi mengenai buku '${book.title}' (ISBN: ${book.isbn}) atau ingin menerbitkan buku serupa.`;
        document.getElementById('modalWaBtn').href = `https://wa.me/6285785522283?text=${encodeURIComponent(text)}`;

        const modalEl = document.getElementById('portfolioBookDetailModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    // Filter & Search Logic
    document.addEventListener('DOMContentLoaded', function () {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const searchInput = document.getElementById('catalogSearchInput');
        const bookItems = document.querySelectorAll('.book-item-col');
        const displayedCountEl = document.getElementById('displayedBooksCount');
        const noBooksFound = document.getElementById('noBooksFound');

        let activeCategory = 'all';
        let searchQuery = '';

        function applyFilter() {
            let visibleCount = 0;

            bookItems.forEach(item => {
                const itemCat = item.getAttribute('data-category');
                const itemTitle = item.getAttribute('data-title');
                const itemAuthor = item.getAttribute('data-author');

                const matchesCat = (activeCategory === 'all' || itemCat.includes(activeCategory) || activeCategory.includes(itemCat));
                const matchesSearch = searchQuery === '' || itemTitle.includes(searchQuery) || itemAuthor.includes(searchQuery);

                if (matchesCat && matchesSearch) {
                    item.classList.remove('d-none');
                    visibleCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            displayedCountEl.textContent = visibleCount;

            if (visibleCount === 0) {
                noBooksFound.classList.remove('d-none');
            } else {
                noBooksFound.classList.add('d-none');
            }
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeCategory = this.getAttribute('data-category');
                applyFilter();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                searchQuery = this.value.trim().toLowerCase();
                applyFilter();
            });
        }

        window.resetFilters = function () {
            if (searchInput) searchInput.value = '';
            searchQuery = '';
            activeCategory = 'all';
            filterBtns.forEach(b => b.classList.remove('active'));
            if (filterBtns[0]) filterBtns[0].classList.add('active');
            applyFilter();
        };
    });
</script>
@endsection
