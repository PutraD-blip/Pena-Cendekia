<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Kedaluwarsa | PT. Pena Cendekia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0F172A;
            color: #F8FAFC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .error-card {
            background: #1E293B;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            max-width: 580px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
        .badge-419 {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.35);
            color: #FBBF24;
            font-weight: 800;
            font-size: 13px;
            padding: 6px 16px;
            border-radius: 50px;
            margin-bottom: 20px;
        }
        .btn-brand {
            background: #F5A623 !important;
            color: #171717 !important;
            font-weight: 700;
            border-radius: 50px;
            padding: 12px 28px;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .btn-brand:hover {
            background: #e09214 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(245, 166, 35, 0.4);
            color: #171717 !important;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="badge-419">
            <i class="bi bi-clock-history"></i> KODE ERROR 419 &bull; SESI KEDALUWARSA
        </div>
        <h2 class="fw-bold mb-3 text-white">Sesi Kedaluwarsa (Page Expired)</h2>
        <p class="text-white-50 mb-4" style="font-size: 14.5px; line-height: 1.6;">
            Halaman formulir telah berada dalam kondisi diam sehingga sesi keamanan diperbarui. Silakan klik tombol di bawah untuk membuka halaman login kembali.
        </p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('login', ['portal' => 'staff']) }}" class="btn-brand">
                <i class="bi bi-arrow-clockwise me-2"></i> Buka Halaman Login
            </a>
            <a href="{{ url('/') }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">
                <i class="bi bi-house-door me-1"></i> Ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
