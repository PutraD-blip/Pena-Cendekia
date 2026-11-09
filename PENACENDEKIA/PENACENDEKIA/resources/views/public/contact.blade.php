@extends('layouts.app')

@section('title', 'Hubungi Kami — PT. Pena Cendekia Pustaka')

@section('content')
<style>
    /* =========================================================
       CONTACT PAGE - HIGH-END UI/UX THEME (Pena Cendekia)
       ========================================================= */
    .contact-page-wrap {
        font-family: 'Poppins', sans-serif;
        background-color: #FAF8F5;
    }

    /* Hero Header */
    .contact-hero {
        position: relative;
        background: linear-gradient(135deg, rgba(16, 21, 29, 0.94) 0%, rgba(22, 28, 40, 0.88) 55%, rgba(245, 166, 35, 0.22) 100%),
                    url('{{ asset('images/hero-bg.jpg') }}') center/cover no-repeat;
        padding-top: 85px;
        padding-bottom: 110px;
        color: #ffffff;
    }

    .contact-hero-title {
        font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .contact-hero .breadcrumb-item,
    .contact-hero .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .contact-hero .breadcrumb-item a:hover {
        color: #F5A623;
    }

    .contact-hero .breadcrumb-item.active {
        color: #F5A623;
        font-weight: 600;
    }

    .contact-hero .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.45);
        content: "»";
        padding: 0 8px;
    }

    /* Floating Info Cards */
    .contact-cards-wrap {
        margin-top: -55px;
        position: relative;
        z-index: 10;
    }

    .contact-info-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 34px 24px;
        text-align: center;
        border: 1px solid #E5E7EB;
        box-shadow: 0 10px 30px rgba(16, 21, 29, 0.05);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .contact-info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 40px rgba(245, 166, 35, 0.15);
        border-color: #F5A623;
    }

    .contact-card-icon {
        width: 62px;
        height: 62px;
        border-radius: 50%;
        background-color: rgba(245, 166, 35, 0.12);
        border: 1.5px solid rgba(245, 166, 35, 0.45);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #F5A623;
        font-size: 1.6rem;
        margin-bottom: 16px;
        transition: all 0.25s ease;
    }

    .contact-info-card:hover .contact-card-icon {
        background-color: #F5A623;
        color: #17120a;
        border-color: #F5A623;
        box-shadow: 0 6px 18px rgba(245, 166, 35, 0.35);
    }

    .contact-card-title {
        font-size: 1.12rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 8px;
        font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
    }

    .contact-card-text {
        font-size: 0.88rem;
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 0;
        font-family: 'Poppins', sans-serif;
    }

    /* Main Section: Maps & Form */
    .contact-section-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #D97706;
        margin-bottom: 6px;
        font-family: 'Poppins', sans-serif;
    }

    .contact-section-badge::before {
        content: "";
        width: 20px;
        height: 2.5px;
        background-color: #F5A623;
        border-radius: 2px;
        display: inline-block;
    }

    .contact-main-heading {
        font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
        font-weight: 800;
        color: #1F2937;
        letter-spacing: -0.5px;
    }

    .contact-map-frame {
        width: 100%;
        height: 100%;
        min-height: 480px;
        border: 0;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(16, 21, 29, 0.04);
    }

    .contact-form-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 36px 32px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 10px 30px rgba(16, 21, 29, 0.04);
        height: 100%;
    }

    .contact-input {
        background-color: #F9FAFB;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        padding: 13px 16px;
        font-size: 0.92rem;
        font-family: 'Poppins', sans-serif;
        color: #1F2937;
        transition: all 0.2s ease;
    }

    .contact-input:focus {
        background-color: #ffffff;
        border-color: #F5A623;
        box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.2);
        color: #1F2937;
        outline: none;
    }

    .contact-input::placeholder {
        color: #9CA3AF;
        font-size: 0.9rem;
    }

    /* Submit Button (Warna Kuning Emas Elegan #F5A623) */
    .btn-contact-submit {
        background-color: #F5A623;
        color: #17120a;
        border-radius: 10px;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        padding: 14px 28px;
        border: none;
        font-size: 0.98rem;
        letter-spacing: 0.3px;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 6px 18px rgba(245, 166, 35, 0.25);
    }

    .btn-contact-submit:hover {
        background-color: #E09419;
        color: #17120a;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(245, 166, 35, 0.35);
    }

    /* Bottom CTA Banner (Warna Gelap Mewah dengan Aksen Kuning Emas #F5A623) */
    .contact-cta-banner {
        background: linear-gradient(135deg, #10151D 0%, #1E2530 100%);
        border-radius: 18px;
        padding: 44px 48px;
        color: #ffffff;
        border: 1px solid rgba(245, 166, 35, 0.3);
        box-shadow: 0 16px 36px rgba(16, 21, 29, 0.15);
        position: relative;
        overflow: hidden;
    }

    .contact-cta-banner::after {
        content: "";
        position: absolute;
        top: -40px;
        right: -40px;
        width: 200px;
        height: 200px;
        background: rgba(245, 166, 35, 0.12);
        border-radius: 50%;
        pointer-events: none;
    }

    .contact-cta-title {
        font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
        font-weight: 800;
        line-height: 1.3;
        letter-spacing: -0.3px;
        color: #ffffff;
    }

    .contact-cta-title span {
        color: #F5A623;
    }

    .btn-cta-banner {
        background-color: #F5A623;
        color: #17120a;
        border-radius: 8px;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        padding: 13px 28px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
        border: none;
        white-space: nowrap;
        box-shadow: 0 6px 18px rgba(245, 166, 35, 0.3);
    }

    .btn-cta-banner:hover {
        background-color: #E09419;
        color: #17120a;
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(245, 166, 35, 0.45);
    }

    @media (max-width: 768px) {
        .contact-hero {
            padding-top: 65px;
            padding-bottom: 90px;
        }
        .contact-form-card {
            padding: 24px 20px;
        }
        .contact-cta-banner {
            padding: 30px 24px;
            text-align: center;
        }
        .contact-cta-banner .d-flex {
            flex-direction: column;
            gap: 20px;
            align-items: center !important;
        }
        .contact-map-frame {
            min-height: 350px;
        }
    }
</style>

<div class="contact-page-wrap">
    <!-- 1. Hero Header Banner -->
    <section class="contact-hero">
        <div class="container text-center text-md-start">
            <h1 class="display-4 contact-hero-title mb-2 text-white">Hubungi Kami</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center justify-content-md-start mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hubungi Kami</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- 2. Floating 3 Contact Info Cards -->
    <section class="contact-cards-wrap mb-5">
        <div class="container">
            <div class="row g-4">
                <!-- Card 1: Lokasi Kantor -->
                <div class="col-md-4">
                    <div class="contact-info-card">
                        <div class="contact-card-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h3 class="contact-card-title">Lokasi Kantor</h3>
                        <p class="contact-card-text">
                            Jl. Jemur Wonosari Gg. Lebar No. 140, Jemur Wonosari,<br>
                            Kec. Wonocolo, Surabaya, Jawa Timur 60237
                        </p>
                    </div>
                </div>

                <!-- Card 2: Email -->
                <div class="col-md-4">
                    <div class="contact-info-card">
                        <div class="contact-card-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <h3 class="contact-card-title">
                            <a href="mailto:penacendekiasby@gmail.com" class="text-reset text-decoration-none">penacendekiasby@gmail.com</a>
                        </h3>
                        <p class="contact-card-text">
                            Kirimkan pertanyaan atau konsultasi naskah penerbitan Anda kapan saja.
                        </p>
                    </div>
                </div>

                <!-- Card 3: Hot / Call -->
                <div class="col-md-4">
                    <div class="contact-info-card">
                        <div class="contact-card-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <h3 class="contact-card-title">
                            <a href="https://wa.me/6285785522283" target="_blank" rel="noopener" class="text-reset text-decoration-none">Hotline: 0857-8552-2283</a>
                        </h3>
                        <p class="contact-card-text">
                            Layanan konsultasi cepat tanggap Senin - Sabtu (09:00 - 16:00 WIB).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Main Section: Google Map & Get in Touch Form -->
    <section class="py-4">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-stretch">
                <!-- Left Column: Google Map dengan Titik Lokasi Akurat -->
                <div class="col-lg-6">
                    <div class="h-100 rounded-4 overflow-hidden border border-light-subtle shadow-sm">
                        <iframe 
                            class="contact-map-frame" 
                            src="https://maps.google.com/maps?q=Jl.+Jemur+Wonosari+Gg.+Lebar+No.140,+Jemur+Wonosari,+Kec.+Wonocolo,+Surabaya,+Jawa+Timur+60237&t=&z=17&ie=UTF8&iwloc=&output=embed" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                <!-- Right Column: Form Kirim Pesan -->
                <div class="col-lg-6">
                    <div class="contact-form-card">
                        <div class="contact-section-badge">Kontak Kami</div>
                        <h2 class="display-6 contact-main-heading mb-4">Kirimkan Pesan Anda</h2>

                        <form id="contactForm" onsubmit="handleContactSubmit(event)">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="contact_name" class="visually-hidden">Nama Lengkap</label>
                                    <input type="text" class="form-control contact-input" id="contact_name" placeholder="Nama Lengkap" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="contact_email" class="visually-hidden">Alamat Email</label>
                                    <input type="email" class="form-control contact-input" id="contact_email" placeholder="Alamat Email" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="contact_phone" class="visually-hidden">Nomor WhatsApp / Telepon</label>
                                    <input type="tel" class="form-control contact-input" id="contact_phone" placeholder="Nomor WhatsApp / Telepon" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="contact_company" class="visually-hidden">Instansi / Layanan yang Diminati</label>
                                    <input type="text" class="form-control contact-input" id="contact_company" placeholder="Instansi / Layanan yang Diminati">
                                </div>
                                <div class="col-12">
                                    <label for="contact_message" class="visually-hidden">Pesan Anda</label>
                                    <textarea class="form-control contact-input" id="contact_message" rows="5" placeholder="Tuliskan pesan atau kebutuhan naskah Anda di sini..." required></textarea>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-contact-submit w-100">
                                        <span>Kirim Pesan Sekarang</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Bottom Banner CTA (Mewah & Harmonis Sesuai Desain Website) -->
    <section class="pt-4 pb-5 mb-3">
        <div class="container">
            <div class="contact-cta-banner">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <h3 class="contact-cta-title mb-2" style="font-size: clamp(1.3rem, 2.4vw, 1.85rem);">
                            Mencari Layanan<br><span>Penerbitan &amp; Percetakan Buku</span> Terbaik?
                        </h3>
                        <p class="text-white-50 mb-0 small" style="max-width: 580px;">
                            Konsultasikan kebutuhan naskah, desain cover, layout, dan cetak buku Anda bersama tim profesional kami.
                        </p>
                    </div>
                    <div>
                        <a href="https://wa.me/6285785522283?text=Halo%20Admin%20Pena%20Cendekia%2C%20saya%20ingin%20konsultasi%20layanan%20penerbitan%20buku." 
                           target="_blank" 
                           rel="noopener" 
                           class="btn-cta-banner">
                            <span>Konsultasi Sekarang</span>
                            <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function handleContactSubmit(e) {
    e.preventDefault();
    const name = document.getElementById('contact_name').value.trim();
    const email = document.getElementById('contact_email').value.trim();
    const phone = document.getElementById('contact_phone').value.trim();
    const company = document.getElementById('contact_company').value.trim();
    const message = document.getElementById('contact_message').value.trim();

    const text = `Halo Admin PT. Pena Cendekia Pustaka,%0A%0ASaya ingin mengirimkan pesan/konsultasi melalui website:%0A- Nama: ${encodeURIComponent(name)}%0A- Email: ${encodeURIComponent(email)}%0A- No. HP: ${encodeURIComponent(phone)}%0A- Instansi/Layanan: ${encodeURIComponent(company || '-')}%0A- Pesan: ${encodeURIComponent(message)}%0A%0ATerima kasih.`;

    window.open(`https://wa.me/6285785522283?text=${text}`, '_blank');
}
</script>
@endsection
