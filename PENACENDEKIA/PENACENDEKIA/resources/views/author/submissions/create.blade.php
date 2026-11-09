@extends('layouts.author')

@section('title', 'Pengajuan Layanan — ' . $currentService['title'])
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* Service Selector Pills at Top */
    .service-nav-bar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }
    .service-nav-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        background-color: var(--author-card-bg, #FFFFFF);
        border: 1.5px solid var(--author-border, #CBD5E1);
        color: var(--author-text-main, #0F172A);
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .service-nav-pill:hover {
        border-color: rgba(245, 166, 35, 0.6);
        color: #F5A623;
        transform: translateY(-1px);
    }
    .service-nav-pill.active {
        background: linear-gradient(135deg, rgba(245, 166, 35, 0.15), rgba(245, 166, 35, 0.05));
        border-color: #F5A623;
        color: #D97706;
        box-shadow: 0 4px 14px rgba(245, 166, 35, 0.18);
    }
    [data-theme="dark"] .service-nav-pill.active {
        color: #F5A623;
        background: rgba(245, 166, 35, 0.15);
    }

    /* Step Indicator Bar */
    .flow-stepper-container {
        background-color: var(--author-card-bg, #FFFFFF);
        border: 1.5px solid var(--author-border, #CBD5E1);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
    }
    .flow-step-item {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 10px;
        transition: background-color 0.2s;
    }
    .flow-step-num {
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
        transition: all 0.25s ease;
    }
    .flow-step-item.active .flow-step-num {
        background: #F5A623;
        color: #111827;
        box-shadow: 0 4px 12px rgba(245, 166, 35, 0.35);
    }
    .flow-step-item.passed .flow-step-num {
        background-color: #10B981;
        color: #FFFFFF;
    }
    .flow-step-item.upcoming .flow-step-num {
        background-color: rgba(148, 163, 184, 0.15);
        color: var(--author-text-sub, #64748B);
    }
    .flow-step-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--author-text-main, #0F172A);
        line-height: 1.2;
    }
    .flow-step-desc {
        font-size: 11px;
        color: var(--author-text-sub, #64748B);
        margin-top: 2px;
    }

    /* Template Download Box */
    .template-box-card {
        background: var(--author-card-surface, #F8FAFC);
        border: 1.5px dashed rgba(245, 166, 35, 0.45);
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 24px;
    }
    .template-item-btn {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        background: var(--author-card-bg, #FFFFFF);
        border: 1.5px solid var(--author-border, #CBD5E1);
        border-radius: 10px;
        padding: 10px 14px;
        color: var(--author-text-main, #0F172A);
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 600;
        transition: all 0.18s ease;
        flex: 1;
        min-width: 240px;
    }
    .template-item-btn:hover {
        border-color: #F5A623;
        color: #D97706;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 166, 35, 0.12);
    }

    /* Package Selection Cards */
    .pkg-choice-card {
        background-color: var(--author-card-bg, #FFFFFF);
        border: 1.5px solid var(--author-border, #CBD5E1);
        border-radius: 16px;
        padding: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.22s ease;
        position: relative;
    }
    .pkg-choice-card:hover {
        border-color: rgba(245, 166, 35, 0.6);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }
    .pkg-choice-card.selected {
        border-color: #F5A623 !important;
        background-color: var(--author-card-surface, #F8FAFC) !important;
        box-shadow: 0 8px 24px rgba(245, 166, 35, 0.15) !important;
    }
    .pkg-choice-radio {
        position: absolute;
        top: 18px;
        right: 18px;
        width: 20px;
        height: 20px;
        accent-color: #F5A623;
        cursor: pointer;
    }
    .pkg-tag {
        display: inline-block;
        font-size: 10.5px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        margin-bottom: 10px;
        background: rgba(245, 166, 35, 0.15);
        color: #D97706;
    }
    .pkg-tag.highlight {
        background: #F5A623;
        color: #111827;
    }
    .pkg-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--author-text-main, #0F172A);
        margin-bottom: 6px;
    }
    .pkg-cost {
        font-size: 22px;
        font-weight: 800;
        color: var(--author-text-main, #0F172A);
        margin-bottom: 4px;
    }
    .pkg-subtitle {
        font-size: 12px;
        color: var(--author-text-sub, #64748B);
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--author-border, #E2E8F0);
    }
    .pkg-feat-list {
        list-style: none;
        padding: 0;
        margin: 0 0 16px;
        font-size: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        color: var(--author-text-sub, #64748B);
    }
    .pkg-feat-list li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        line-height: 1.4;
    }
    .pkg-feat-list li i {
        color: #10B981;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* Active Package Banner */
    .active-package-banner {
        background: var(--author-card-bg, #FFFFFF);
        border: 1.5px solid var(--author-border, #CBD5E1);
        border-left: 4px solid #F5A623;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .form-section-card {
        background: var(--author-card-bg, #FFFFFF);
        border: 1.5px solid var(--author-border, #CBD5E1);
        border-radius: 16px;
        padding: 22px;
        margin-bottom: 24px;
    }
    .form-section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--author-text-main, #0F172A);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 100%;">

    <!-- Top Navigation: Pilih Jenis Layanan -->
    <div class="mb-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="small text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px;">Pilih Jenis Layanan:</span>
            <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Redaksi Pena Cendekia, saya ingin tanya-tanya mengenai layanan penerbitan / cetak.') }}" target="_blank" rel="noopener" class="small text-decoration-none fw-semibold" style="color: #10B981;">
                <i class="bi bi-whatsapp me-1"></i> Tanya-tanya via WhatsApp
            </a>
        </div>
        <div class="service-nav-bar">
            @foreach($services as $sKey => $s)
                <a href="{{ route('author.submissions.create', ['service' => $sKey]) }}" class="service-nav-pill {{ $serviceKey === $sKey ? 'active' : '' }}">
                    <i class="bi {{ $s['icon'] }}"></i>
                    <span>{{ $s['title'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Header Ringkasan Layanan Terpilih -->
    <div class="p-4 rounded-4 mb-4" style="background: var(--author-card-bg, #FFFFFF); border: 1.5px solid var(--author-border, #CBD5E1);">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <span class="badge rounded-pill mb-2 px-3 py-1" style="background: rgba(245, 166, 35, 0.15); color: #F5A623; font-weight: 700; font-size: 11px;">
                    <i class="bi bi-patch-check-fill me-1"></i> {{ $currentService['badge'] }}
                </span>
                <h1 class="h4 fw-bold mb-1" style="color: var(--author-text-main, #0F172A);">{{ $currentService['title'] }}</h1>
                <p class="text-muted small mb-0">{{ $currentService['subtitle'] }}</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge py-2 px-3 rounded-3" style="background: var(--author-card-surface, #F8FAFC); border: 1.5px solid var(--author-border, #CBD5E1); color: var(--author-text-main, #0F172A); font-size: 12px;">
                    <i class="bi bi-shield-check text-success me-1"></i> Pengajuan Langsung ke Admin
                </span>
            </div>
        </div>
    </div>

    <!-- 3-Step Wizard Indicator -->
    <div class="flow-stepper-container">
        <div class="d-flex align-items-center justify-content-between gap-2">
            <!-- Step 1 -->
            <div class="flow-step-item active" id="indicatorStep1" onclick="goToStep(1)">
                <div class="flow-step-num">1</div>
                <div>
                    <div class="flow-step-label">1. Pilih Paket &amp; Unduh Format</div>
                    <div class="flow-step-desc">Tentukan spesifikasi &amp; ambil template</div>
                </div>
            </div>

            <i class="bi bi-chevron-right text-muted d-none d-md-block fs-6"></i>

            <!-- Step 2 -->
            <div class="flow-step-item upcoming" id="indicatorStep2" onclick="goToStep(2)">
                <div class="flow-step-num">2</div>
                <div>
                    <div class="flow-step-label">2. Formulir &amp; Upload Berkas</div>
                    <div class="flow-step-desc">Unggah file naskah / dokumen resmi</div>
                </div>
            </div>

            <i class="bi bi-chevron-right text-muted d-none d-md-block fs-6"></i>

            <!-- Step 3 -->
            <div class="flow-step-item upcoming" id="indicatorStep3">
                <div class="flow-step-num">3</div>
                <div>
                    <div class="flow-step-label">3. Tinjauan Admin &amp; Tagihan</div>
                    <div class="flow-step-desc">Verifikasi berkas &amp; invoice DP</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM UTAMA -->
    <form action="{{ route('author.submissions.store') }}" method="POST" enctype="multipart/form-data" id="submissionForm">
        @csrf
        <input type="hidden" name="service_type" value="{{ $serviceKey }}">
        <input type="hidden" name="package_name" id="inputPackageName" value="{{ old('package_name', $selectedPackage['name'] ?? 'Paket Pilihan') }}">
        <input type="hidden" name="package_price" id="inputPackagePrice" value="{{ old('package_price', $selectedPackage['price'] ?? 0) }}">

        <!-- ========================================================= -->
        <!-- LANGKAH 1: PILIH PAKET & UNDUH FORMAT DOKUMEN             -->
        <!-- ========================================================= -->
        <div id="sectionStep1">

            <!-- Pilihan Paket Grid -->
            <div class="form-section-card">
                <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom" style="border-color: var(--author-border, #E2E8F0) !important;">
                    <div>
                        <h2 class="form-section-title">
                            <i class="bi bi-boxes text-warning"></i>
                            <span>Pilihan Paket &amp; Spesifikasi {{ $currentService['title'] }}</span>
                        </h2>
                        <small class="text-muted">Pilih paket yang paling sesuai dengan kebutuhan Anda, lalu lanjutkan untuk mengunggah berkas.</small>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach($packages as $pKey => $pkg)
                        @php
                            $isSelected = old('package_name')
                                ? (old('package_name') === $pkg['name'])
                                : ($selectedPackageKey ? $selectedPackageKey === $pKey : $loop->first);
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="pkg-choice-card {{ $isSelected ? 'selected' : '' }}" 
                                 onclick="selectPackage('{{ $pkg['name'] }}', {{ $pkg['price'] }}, {{ $pkg['copies'] ?? 1 }}, this)"
                                 data-name="{{ $pkg['name'] }}"
                                 data-price="{{ $pkg['price'] }}"
                                 data-copies="{{ $pkg['copies'] ?? 1 }}">
                                
                                <input type="radio" 
                                       name="package_radio" 
                                       class="pkg-choice-radio" 
                                       value="{{ $pkg['name'] }}" 
                                       {{ $isSelected ? 'checked' : '' }}
                                       tabindex="-1">

                                <div>
                                    <span class="pkg-tag {{ !empty($pkg['highlight']) ? 'highlight' : '' }}">
                                        {{ $pkg['badge'] }}
                                    </span>
                                </div>

                                <h3 class="pkg-name">{{ $pkg['name'] }}</h3>
                                <div class="pkg-cost">
                                    @if($pkg['price'] > 0)
                                        <span style="font-size: 14px; font-weight: 600;">Rp </span>{{ number_format($pkg['price'], 0, ',', '.') }}
                                    @else
                                        <span style="font-size: 16px; font-weight: 700; color: #10B981;">Estimasi Kustom</span>
                                    @endif
                                </div>
                                <div class="pkg-subtitle">{!! $pkg['desc'] !!}</div>

                                <ul class="pkg-feat-list">
                                    @foreach($pkg['features'] as $feat)
                                        <li>
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>{{ $feat }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="mt-auto pt-2">
                                    <button type="button" class="btn btn-sm w-100 rounded-3 py-2 fw-bold {{ $isSelected ? 'btn-warning text-dark' : 'btn-outline-secondary' }}">
                                        {{ $isSelected ? '✓ Paket Terpilih' : 'Pilih Paket Ini' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Next to Step 2 Button -->
                <div class="d-flex justify-content-end mt-4 pt-3 border-top" style="border-color: var(--author-border, #E2E8F0) !important;">
                    <button type="button" class="btn btn-warning text-dark fw-bold px-4 py-2 rounded-3" onclick="goToStep(2)">
                        <span>Lanjut ke Formulir &amp; Upload Berkas</span>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- KOTAK UNDUHAN TEMPLATE RESMI DI STEP 1 -->
            @if(!empty($currentService['templates']))
                <div class="template-box-card">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-file-earmark-arrow-down-fill text-warning fs-5"></i>
                        <h3 class="h6 fw-bold mb-0" style="color: var(--author-text-main, #0F172A);">
                            Unduh Format / Template Dokumen Resmi
                        </h3>
                    </div>
                    <p class="text-muted small mb-3">
                        Gunakan template format resmi Pena Cendekia berikut untuk menyusun naskah atau persyaratan dokumen Anda sebelum diunggah:
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                        @foreach($currentService['templates'] as $tpl)
                            <a href="{{ $tpl['url'] }}" download class="template-item-btn">
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    <i class="bi bi-file-earmark-word text-primary fs-5"></i>
                                    <div class="text-truncate">
                                        <span class="d-block text-truncate">{{ $tpl['name'] }}</span>
                                        <small class="text-muted d-block" style="font-size: 11px;">{{ $tpl['size'] }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark border ms-2"><i class="bi bi-download"></i> Unduh</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- ========================================================= -->
        <!-- LANGKAH 2: FORMULIR DATA & UPLOAD BERKAS KE ADMIN         -->
        <!-- ========================================================= -->
        <div id="sectionStep2" style="display: none;">

            <!-- Active Selected Package Confirmation -->
            <div class="active-package-banner">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-warning text-dark fw-bold" style="width: 40px; height: 40px; font-size: 18px;">
                        <i class="bi {{ $currentService['icon'] }}"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 10.5px;">Paket / Layanan Terpilih:</small>
                        <h4 class="fw-bold mb-0" id="bannerPackageName" style="color: var(--author-text-main, #0F172A); font-size: 16px;">
                            {{ old('package_name', $selectedPackage['name'] ?? 'Paket Terpilih') }}
                        </h4>
                        <span class="small fw-semibold text-warning" id="bannerPackagePrice">
                            @if(!empty($selectedPackage['price']) && $selectedPackage['price'] > 0)
                                Biaya Paket: Rp {{ number_format(old('package_price', $selectedPackage['price']), 0, ',', '.') }}
                            @else
                                Biaya dihitung oleh Admin
                            @endif
                        </span>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" onclick="goToStep(1)">
                    <i class="bi bi-arrow-repeat me-1"></i> Ganti Paket
                </button>
            </div>

            <!-- KOTAK UNDUHAN TEMPLATE DI ATAS UPLOAD SECTION -->
            @if(!empty($currentService['templates']))
                <div class="template-box-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-download text-warning"></i>
                            <span class="fw-bold small" style="color: var(--author-text-main, #0F172A);">Belum Punya Formatnya? Unduh Template Terlebih Dahulu:</span>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($currentService['templates'] as $tpl)
                            <a href="{{ $tpl['url'] }}" download class="template-item-btn py-2">
                                <span class="text-truncate"><i class="bi bi-file-earmark-word text-primary me-1"></i> {{ $tpl['name'] }}</span>
                                <span class="badge bg-warning text-dark"><i class="bi bi-download"></i> Unduh (.docx)</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Data Identitas Penulis -->
            <div class="form-section-card">
                <div class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom" style="border-color: var(--author-border, #E2E8F0) !important;">
                    <h3 class="form-section-title">
                        <i class="bi bi-person-check-fill text-success"></i>
                        <span>Data Pemohon / Penulis (Akun Terverifikasi)</span>
                    </h3>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Nama Lengkap</label>
                        <input type="text" class="form-control" value="{{ $user->name }}" readonly disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">WhatsApp / Kontak</label>
                        <input type="text" class="form-control" value="{{ $user->phone ?? 'Belum diisi' }}" readonly disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small">Status Akun</label>
                        <input type="text" class="form-control text-success fw-bold" value="Aktif & Terverifikasi" readonly disabled>
                    </div>
                </div>
            </div>

            <!-- Detail Informasi Pengajuan -->
            <div class="form-section-card">
                <div class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom" style="border-color: var(--author-border, #E2E8F0) !important;">
                    <h3 class="form-section-title">
                        <i class="bi bi-journal-text text-warning"></i>
                        <span>Informasi &amp; Data {{ $currentService['title'] }}</span>
                    </h3>
                </div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="title" class="form-label fw-semibold small">
                            Judul Buku / Judul Karya Cipta <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="Contoh: Merajut Asa di Ujung Senja">
                        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="pen_name" class="form-label fw-semibold small">Nama Pena / Pencipta di Cover</label>
                        <input type="text" name="pen_name" id="pen_name" class="form-control" value="{{ old('pen_name') }}" placeholder="Kosongkan jika pakai nama akun">
                    </div>

                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-semibold small">Kategori / Klasifikasi Karya <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="editor_name" class="form-label fw-semibold small">Nama Penyunting / Editor (Opsional)</label>
                        <input type="text" name="editor_name" id="editor_name" class="form-control" value="{{ old('editor_name', $user->name) }}" placeholder="Nama penyunting atau diri sendiri">
                    </div>

                    <div class="col-12">
                        <label for="synopsis" class="form-label fw-semibold small">
                            Sinopsis / Rangkuman Karya / Keterangan Pengajuan <span class="text-danger">*</span>
                        </label>
                        <textarea name="synopsis" id="synopsis" rows="4" class="form-control @error('synopsis') is-invalid @enderror" required placeholder="Tuliskan rangkuman cerita, abstraksi karya cipta, atau catatan spesifikasi pengajuan Anda...">{{ old('synopsis') }}</textarea>
                        <small class="text-muted" style="font-size: 11px;">Minimal 15 karakter agar tim redaksi dapat meninjau berkas dengan tepat.</small>
                        @error('synopsis') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Upload File Naskah & Dokumen Utama -->
            <div class="form-section-card">
                <div class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom" style="border-color: var(--author-border, #E2E8F0) !important;">
                    <h3 class="form-section-title">
                        <i class="bi bi-cloud-arrow-up-fill text-warning"></i>
                        <span>Unggah Berkas Naskah / Dokumen Pengajuan</span>
                    </h3>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="manuscript_file" class="form-label fw-semibold small">
                            Berkas Utama (Naskah / Karya / Dokumen Ciptaan) <span class="text-danger">*</span>
                        </label>
                        <div class="p-3 rounded-3" style="background-color: var(--author-card-surface, #F8FAFC); border: 1.5px dashed var(--author-border, #CBD5E1);">
                            <input type="file" name="manuscript_file" id="manuscript_file" class="form-control @error('manuscript_file') is-invalid @enderror" required accept=".docx,.doc,.pdf,.zip,.rar">
                            <small class="text-muted d-block mt-2" style="font-size: 11.5px;">
                                <i class="bi bi-file-earmark-check text-primary me-1"></i>Format diizinkan: <strong>.docx, .pdf, .zip</strong> (Maks. 50 MB).
                            </small>
                        </div>
                        @error('manuscript_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="layout_file" class="form-label fw-semibold small">
                            Berkas Lampiran / Draft Tambahan (Opsional)
                        </label>
                        <div class="p-3 rounded-3" style="background-color: var(--author-card-surface, #F8FAFC); border: 1.5px dashed var(--author-border, #CBD5E1);">
                            <input type="file" name="layout_file" id="layout_file" class="form-control @error('layout_file') is-invalid @enderror" accept=".docx,.pdf,.cdr,.ai,.zip,.rar">
                            <small class="text-muted d-block mt-2" style="font-size: 11.5px;">
                                <i class="bi bi-paperclip text-success me-1"></i>Lampiran cover, draf layout, atau berkas pendukung (Maks. 100 MB).
                            </small>
                        </div>
                        @error('layout_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Surat Pernyataan Keaslian Karya (Wajib) -->
                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background-color: var(--author-card-surface, #F8FAFC); border: 1.5px dashed var(--author-border, #CBD5E1);">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                <label for="originality_statement_file" class="form-label fw-semibold small mb-0 d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-check text-warning fs-6"></i>
                                    <span>Surat Pernyataan Keaslian Karya (Bermaterai) <span class="text-danger fw-bold">* Wajib</span></span>
                                </label>
                                <div class="d-flex align-items-center gap-1">
                                    <a href="https://docs.google.com/document/d/1mhQrLKIVESY2vE7pRz9CVXRINmrHkBn4/edit" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 py-1 px-2.5 rounded-pill" style="font-size: 11.5px; font-weight: 600;">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                        <span>Buka di Google Docs</span>
                                    </a>
                                    <a href="{{ asset('downloads/surat-pernyataan-hak-cipta.docx') }}" download class="btn btn-sm btn-outline-warning d-inline-flex align-items-center gap-1 py-1 px-2.5 rounded-pill" style="font-size: 11.5px; font-weight: 600;">
                                        <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                        <span>Unduh .docx</span>
                                    </a>
                                </div>
                            </div>
                            <input type="file" name="originality_statement_file" id="originality_statement_file" class="form-control @error('originality_statement_file') is-invalid @enderror" required accept=".docx,.doc,.pdf,.jpg,.jpeg,.png">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-2">
                                <small class="text-muted" style="font-size: 11.5px;">
                                    <i class="bi bi-info-circle text-primary me-1"></i>Format diizinkan: <strong>.docx, .pdf, scan/foto (.jpg, .png)</strong> (Maks. 20 MB).
                                </small>
                                <span class="badge rounded-pill" style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.28); font-size: 10.5px; font-weight: 600;">
                                    <i class="bi bi-check-circle-fill me-1"></i>Format Resmi Terverifikasi
                                </span>
                            </div>
                        </div>
                        @error('originality_statement_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Spesifikasi Tambahan -->
            <div class="form-section-card">
                <div class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom" style="border-color: var(--author-border, #E2E8F0) !important;">
                    <h3 class="form-section-title">
                        <i class="bi bi-sliders text-warning"></i>
                        <span>Spesifikasi Tambahan (Jika Diperlukan)</span>
                    </h3>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="estimated_pages" class="form-label fw-semibold small">Estimasi Halaman</label>
                        <input type="number" name="estimated_pages" id="estimated_pages" min="1" class="form-control" value="{{ old('estimated_pages', 100) }}">
                        <small class="text-muted" style="font-size: 11px;">Jumlah halaman buku / dokumen.</small>
                    </div>

                    <div class="col-md-4">
                        <label for="quantity" class="form-label fw-semibold small">Jumlah Eksemplar</label>
                        <input type="number" name="quantity" id="quantity" min="1" class="form-control" value="{{ old('quantity', $selectedPackage['copies'] ?? 10) }}">
                        <small class="text-muted" style="font-size: 11px;">Jumlah eksemplar buku cetak yang diinginkan.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Pilihan Jenis Cover</label>
                        <div class="d-flex gap-3 mt-1 pt-1">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cover_type" id="cover_soft" value="soft" {{ old('cover_type', 'soft') == 'soft' ? 'checked' : '' }}>
                                <label class="form-check-label small" for="cover_soft">Soft Cover</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cover_type" id="cover_hard" value="hard" {{ old('cover_type') == 'hard' ? 'checked' : '' }}>
                                <label class="form-check-label small" for="cover_hard">Hard Cover</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label fw-semibold small">Catatan Tambahan untuk Admin &amp; Redaksi (Opsional)</label>
                        <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="Contoh: Naskah ingin dicetak ukuran UNESCO dengan kertas bookpaper, ada diskon member...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- LANGKAH 3: SUBMIT LANGSUNG KE ADMIN -->
            <div class="p-4 rounded-4 mb-4" style="background: linear-gradient(135deg, #111827 0%, #1F2937 100%); color: #FFFFFF; border: 1.5px solid rgba(245, 166, 35, 0.4);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1">Langkah 3</span>
                    <h3 class="h6 fw-bold mb-0 text-white">Alur Verifikasi Admin Redaksi</h3>
                </div>
                <p class="text-light small mb-3" style="font-size: 12px; line-height: 1.6;">
                    Setelah Anda menekan tombol di bawah, berkas akan <strong>langsung terkirim ke panel Admin Redaksi Pena Cendekia</strong>. Dewan redaksi akan meninjau kelayakan naskah dan mengonfirmasi biaya paket (Maksimal 1x24 jam). Anda tidak perlu mengirim ulang berkas lewat WhatsApp.
                </p>
                <div class="pt-2 border-top border-secondary d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span class="small text-muted" style="font-size: 11px;">
                        <i class="bi bi-shield-check text-success me-1"></i> Berkas disimpan secara aman dan terenkripsi di server resmi.
                    </span>
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Redaksi Pena Cendekia, saya ingin tanya-tanya mengenai pengajuan saya.') }}" target="_blank" rel="noopener" class="small text-decoration-none text-warning">
                        <i class="bi bi-whatsapp me-1"></i> Butuh bantuan tanya-tanya? Chat WA Redaksi
                    </a>
                </div>
            </div>

            <!-- Tombol Aksi Akhir -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mb-5">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2" onclick="goToStep(1)">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Pilih Paket
                </button>

                <button type="submit" class="btn btn-warning text-dark fw-bold rounded-3 px-5 py-3" style="font-size: 15px;">
                    <i class="bi bi-send-fill me-2"></i> Kirim Pengajuan Langsung ke Admin
                </button>
            </div>

        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    function selectPackage(name, price, copies, cardElem) {
        document.getElementById('inputPackageName').value = name;
        document.getElementById('inputPackagePrice').value = price;
        
        const qtyInput = document.getElementById('quantity');
        if (qtyInput && copies) {
            qtyInput.value = copies;
        }

        document.getElementById('bannerPackageName').textContent = name;
        if (price > 0) {
            document.getElementById('bannerPackagePrice').textContent = 'Biaya Paket: Rp ' + new Intl.NumberFormat('id-ID').format(price);
        } else {
            document.getElementById('bannerPackagePrice').textContent = 'Biaya dihitung oleh Admin';
        }

        document.querySelectorAll('.pkg-choice-card').forEach(function(card) {
            card.classList.remove('selected');
            const radio = card.querySelector('.pkg-choice-radio');
            if (radio) radio.checked = false;
            const btn = card.querySelector('button');
            if (btn) {
                btn.className = 'btn btn-sm w-100 rounded-3 py-2 fw-bold btn-outline-secondary';
                btn.textContent = 'Pilih Paket Ini';
            }
        });

        cardElem.classList.add('selected');
        const activeRadio = cardElem.querySelector('.pkg-choice-radio');
        if (activeRadio) activeRadio.checked = true;
        
        const activeBtn = cardElem.querySelector('button');
        if (activeBtn) {
            activeBtn.className = 'btn btn-sm w-100 rounded-3 py-2 fw-bold btn-warning text-dark';
            activeBtn.textContent = '✓ Paket Terpilih';
        }
    }

    function goToStep(stepNum) {
        const ind1 = document.getElementById('indicatorStep1');
        const ind2 = document.getElementById('indicatorStep2');
        const step1Sec = document.getElementById('sectionStep1');
        const step2Sec = document.getElementById('sectionStep2');

        if (stepNum === 2) {
            ind1.className = 'flow-step-item passed';
            ind2.className = 'flow-step-item active';

            step1Sec.style.display = 'none';
            step2Sec.style.display = 'block';

            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else if (stepNum === 1) {
            ind1.className = 'flow-step-item active';
            ind2.className = 'flow-step-item upcoming';

            step1Sec.style.display = 'block';
            step2Sec.style.display = 'none';

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        const hasOldTitle = {{ old('title') ? 'true' : 'false' }};

        if (hasErrors || hasOldTitle) {
            goToStep(2);
        }
    });
</script>
@endpush
