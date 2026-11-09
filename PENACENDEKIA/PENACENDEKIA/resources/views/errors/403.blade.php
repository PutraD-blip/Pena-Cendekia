<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | PT. Pena Cendekia</title>
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
        .badge-403 {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #F87171;
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
            padding: 10px 24px;
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
            color: #171717 !important;
        }
        .btn-ghost {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #E2E8F0 !important;
            font-weight: 600;
            border-radius: 50px;
            padding: 10px 24px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #FFFFFF !important;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="badge-403">
            <i class="bi bi-shield-lock-fill"></i> KODE ERROR 403 &bull; AKSES DITOLAK
        </div>
        <h2 class="fw-bold mb-3 text-white">Hak Akses Tidak Sesuai</h2>
        <p class="text-white-50 mb-4" style="font-size: 14.5px; line-height: 1.6;">
            Anda tidak memiliki izin untuk mengakses halaman ini. Hal ini terjadi karena Anda sedang login dengan akun yang memiliki peran berbeda di browser ini.
        </p>

        @auth
            <div class="p-3 rounded-3 mb-4 text-start" style="background: rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.08); font-size: 13px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="text-white-50">Akun Anda Saat Ini:</span>
                    <strong class="text-warning">{{ auth()->user()->name }}</strong>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-white-50">Peran Aktif:</span>
                    <span class="badge bg-secondary text-uppercase">{{ auth()->user()->role }}</span>
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                @php
                    $targetDashboard = match(auth()->user()->role) {
                        'admin' => route('admin.dashboard'),
                        'designer' => route('designer.dashboard'),
                        'author' => route('author.dashboard'),
                        default => url('/'),
                    };
                @endphp
                <a href="{{ $targetDashboard }}" class="btn-brand">
                    <i class="bi bi-speedometer2 me-1"></i> Ke Dashboard Saya
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-ghost w-100">
                        <i class="bi bi-box-arrow-right me-1"></i> Ganti Akun / Logout
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login', ['portal' => 'staff']) }}" class="btn-brand">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk / Login
            </a>
        @endauth
    </div>
</body>
</html>
