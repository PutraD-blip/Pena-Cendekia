@php
    $isAuthor = auth()->user()->isAuthor();
    $layout = $isAuthor ? 'layouts.author' : 'layouts.admin';
@endphp
@extends($layout)

@section('title', 'Notifikasi & Pemberitahuan — PT. Pena Cendekia')
@section('page-title', 'Pusat Notifikasi')

@section('content')
<div class="{{ $isAuthor ? 'container-fluid max-w-5xl px-0' : 'container-fluid' }}">

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert" style="{{ $isAuthor ? 'background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3) !important; color: #6ee7b7;' : '' }}">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close {{ $isAuthor ? 'btn-close-white' : '' }} ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert" style="{{ $isAuthor ? 'background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3) !important; color: #93c5fd;' : '' }}">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>{{ session('info') }}</div>
            <button type="button" class="btn-close {{ $isAuthor ? 'btn-close-white' : '' }} ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h3 class="fw-bold mb-0 {{ $isAuthor ? 'text-white' : 'font-serif text-dark' }}" style="{{ $isAuthor ? 'font-family: var(--font-heading); font-size: 1.45rem;' : 'font-size: 1.45rem;' }}">
                    Pusat Notifikasi
                </h3>
                @if($unreadCount > 0)
                    <span class="badge {{ $isAuthor ? 'bg-warning text-dark' : 'bg-primary' }} rounded-pill px-2.5 py-1" style="font-size: 0.75rem; font-weight: 600;">
                        {{ $unreadCount }} Baru
                    </span>
                @endif
            </div>
            <p class="small mb-0 {{ $isAuthor ? 'text-muted' : 'text-secondary' }}">
                Pantau status pengajuan naskah, verifikasi pembayaran, dan pembaruan sistem secara langsung.
            </p>
        </div>

        <!-- Global Action Buttons -->
        <div class="d-flex flex-wrap align-items-center gap-2">
            @if($unreadCount > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $isAuthor ? 'btn-outline-light rounded-pill px-3' : 'btn-outline-secondary' }}" style="font-size: 0.8125rem;">
                        <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
                    </button>
                </form>
            @endif

            @if($readCount > 0)
                <form action="{{ route('notifications.clearRead') }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua notifikasi lama yang sudah dibaca?');">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $isAuthor ? 'btn-outline-danger rounded-pill px-3' : 'btn-outline-danger' }}" style="font-size: 0.8125rem;" title="Hapus notifikasi lama yang sudah selesai dibaca">
                        <i class="bi bi-trash3 me-1"></i> Bersihkan Notifikasi Lama
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-4">
        <div class="d-inline-flex p-1 rounded-3 {{ $isAuthor ? 'bg-dark' : 'bg-light border' }}" style="{{ $isAuthor ? 'background-color: var(--author-surface, #14171C) !important; border: 1px solid var(--author-border, rgba(255, 255, 255, 0.08));' : '' }}">
            <a href="{{ route('notifications.index', ['filter' => 'all']) }}" 
               class="btn btn-sm px-3 py-1.5 rounded-2 d-flex align-items-center gap-2 text-decoration-none {{ $filter === 'all' ? ($isAuthor ? 'btn-warning text-dark fw-semibold' : 'btn-white text-dark fw-semibold shadow-sm') : ($isAuthor ? 'text-muted' : 'text-secondary') }}"
               style="{{ $filter === 'all' && !$isAuthor ? 'background: #fff;' : '' }}">
                <span>Semua</span>
                <span class="badge rounded-pill {{ $filter === 'all' ? ($isAuthor ? 'bg-dark text-warning' : 'bg-secondary text-white') : ($isAuthor ? 'bg-secondary text-white' : 'bg-secondary-subtle text-secondary') }}" style="font-size: 0.7rem;">{{ $totalCount }}</span>
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
               class="btn btn-sm px-3 py-1.5 rounded-2 d-flex align-items-center gap-2 text-decoration-none {{ $filter === 'unread' ? ($isAuthor ? 'btn-warning text-dark fw-semibold' : 'btn-white text-dark fw-semibold shadow-sm') : ($isAuthor ? 'text-muted' : 'text-secondary') }}"
               style="{{ $filter === 'unread' && !$isAuthor ? 'background: #fff;' : '' }}">
                <span>Belum Dibaca</span>
                <span class="badge rounded-pill {{ $unreadCount > 0 ? 'bg-danger text-white' : ($isAuthor ? 'bg-secondary text-white' : 'bg-secondary-subtle text-secondary') }}" style="font-size: 0.7rem;">{{ $unreadCount }}</span>
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
               class="btn btn-sm px-3 py-1.5 rounded-2 d-flex align-items-center gap-2 text-decoration-none {{ $filter === 'read' ? ($isAuthor ? 'btn-warning text-dark fw-semibold' : 'btn-white text-dark fw-semibold shadow-sm') : ($isAuthor ? 'text-muted' : 'text-secondary') }}"
               style="{{ $filter === 'read' && !$isAuthor ? 'background: #fff;' : '' }}">
                <span>Sudah Dibaca</span>
                <span class="badge rounded-pill {{ $isAuthor ? 'bg-secondary text-white' : 'bg-secondary-subtle text-secondary' }}" style="font-size: 0.7rem;">{{ $readCount }}</span>
            </a>
        </div>
    </div>

    <!-- Notifications List Container -->
    @if($notifications->isEmpty())
        <div class="{{ $isAuthor ? 'dash-card' : 'card card-soft' }} text-center py-5 px-4" style="{{ $isAuthor ? 'background: var(--author-surface, #14171C); border: 1px solid var(--author-border, rgba(255,255,255,0.08)); border-radius: 14px;' : 'border-radius: 14px;' }}">
            <div class="py-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 {{ $isAuthor ? 'bg-secondary bg-opacity-25' : 'bg-light' }}" style="width: 68px; height: 68px;">
                    <i class="bi bi-bell-slash fs-2 {{ $isAuthor ? 'text-warning' : 'text-muted' }}"></i>
                </div>
                <h5 class="fw-bold mb-1 {{ $isAuthor ? 'text-white' : 'text-dark' }}">
                    @if($filter === 'unread')
                        Semua Notifikasi Sudah Dibaca
                    @elseif($filter === 'read')
                        Belum Ada Riwayat Notifikasi yang Dibaca
                    @else
                        Belum Ada Notifikasi
                    @endif
                </h5>
                <p class="small text-muted mb-3" style="max-width: 420px; margin: 0 auto;">
                    @if($filter === 'unread')
                        Bagus sekali! Tidak ada pemberitahuan baru yang menunggu tindakan Anda saat ini.
                    @else
                        Setiap update penting terkait naskah, penerbitan, atau verifikasi pembayaran akan ditampilkan di sini.
                    @endif
                </p>
                @if($filter !== 'all')
                    <a href="{{ route('notifications.index', ['filter' => 'all']) }}" class="btn btn-sm {{ $isAuthor ? 'btn-outline-light rounded-pill px-3' : 'btn-outline-secondary' }}">
                        <i class="bi bi-arrow-left me-1"></i> Tampilkan Semua Notifikasi
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="d-flex flex-column gap-2.5">
            @foreach($notifications as $notif)
                @php
                    $isUnread = !$notif->is_read;
                    
                    // Icon and color configuration
                    $iconBg = match($notif->type) {
                        'success' => ($isAuthor ? 'background: rgba(16, 185, 129, 0.15); color: #34d399;' : 'background: #DCFCE7; color: #16A34A;'),
                        'warning' => ($isAuthor ? 'background: rgba(245, 166, 35, 0.15); color: #fbbf24;' : 'background: #FEF3C7; color: #D97706;'),
                        'error'   => ($isAuthor ? 'background: rgba(239, 68, 68, 0.15); color: #f87171;' : 'background: #FEE2E2; color: #DC2626;'),
                        default   => ($isAuthor ? 'background: rgba(59, 130, 246, 0.15); color: #60a5fa;' : 'background: #DBEAFE; color: #2563EB;')
                    };

                    $cardStyle = '';
                    if ($isAuthor) {
                        if ($isUnread) {
                            $cardStyle = 'background: rgba(245, 166, 35, 0.05); border: 1px solid rgba(245, 166, 35, 0.35); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);';
                        } else {
                            $cardStyle = 'background: var(--author-surface, #14171C); border: 1px solid var(--author-border, rgba(255, 255, 255, 0.07)); opacity: 0.85;';
                        }
                    } else {
                        if ($isUnread) {
                            $cardStyle = 'background: #FFFFFF; border: 1px solid #CBD5E1; border-left: 4px solid #2563EB; box-shadow: 0 2px 8px rgba(0,0,0,0.04);';
                        } else {
                            $cardStyle = 'background: #F8FAFC; border: 1px solid #E2E8F0; opacity: 0.9;';
                        }
                    }
                @endphp

                <div class="p-3.5 p-md-4 rounded-3 d-flex flex-column flex-sm-row justify-content-between align-items-start gap-3 transition-all" 
                     style="{{ $cardStyle }} border-radius: 12px; transition: all 0.2s ease;">
                    
                    <!-- Left: Icon + Content -->
                    <div class="d-flex align-items-start gap-3 flex-grow-1">
                        <!-- Type Icon Circle -->
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                             style="width: 42px; height: 42px; {{ $iconBg }}">
                            <i class="bi {{ $notif->icon }} fs-5"></i>
                        </div>

                        <!-- Content Body -->
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h6 class="fw-bold mb-0 {{ $isAuthor ? ($isUnread ? 'text-white' : 'text-light') : 'text-dark' }}" style="font-size: 0.95rem;">
                                    {{ $notif->title }}
                                </h6>

                                @if($isUnread)
                                    <span class="badge {{ $isAuthor ? 'bg-warning text-dark' : 'bg-primary' }} rounded-pill px-2 py-0.5" style="font-size: 0.6875rem; font-weight: 600;">
                                        Baru
                                    </span>
                                @endif

                                @if($notif->related_type)
                                    <span class="badge {{ $isAuthor ? 'bg-secondary bg-opacity-25 text-light' : 'bg-light text-secondary border' }} rounded-pill px-2 py-0.5 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                        {{ $notif->related_type }}
                                    </span>
                                @endif
                            </div>

                            <p class="small mb-2 {{ $isAuthor ? 'text-light text-opacity-75' : 'text-secondary' }}" style="font-size: 0.875rem; line-height: 1.5;">
                                {{ $notif->message }}
                            </p>

                            <!-- Timestamp & Detail Link -->
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <span class="small text-muted d-inline-flex align-items-center gap-1" style="font-size: 0.775rem;">
                                    <i class="bi bi-clock"></i>
                                    {{ $notif->created_at->diffForHumans() }}
                                    <span class="text-opacity-50">({{ $notif->created_at->format('d M Y, H:i') }} WIB)</span>
                                </span>

                                @if($notif->target_url)
                                    <a href="{{ $notif->target_url }}" 
                                       class="small fw-semibold text-decoration-none d-inline-flex align-items-center gap-1 {{ $isAuthor ? 'text-warning' : 'text-primary' }}" 
                                       style="font-size: 0.775rem;">
                                        <span>Buka Tautan Terkait</span>
                                        <i class="bi bi-arrow-right-short fs-6"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right: Quick Actions -->
                    <div class="d-flex align-items-center gap-1.5 align-self-end align-self-sm-start flex-shrink-0 pt-1 pt-sm-0">
                        @if($isUnread)
                            <form action="{{ route('notifications.read', $notif) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-sm {{ $isAuthor ? 'btn-outline-light' : 'btn-outline-secondary' }} rounded-2 d-inline-flex align-items-center gap-1 py-1 px-2.5" 
                                        style="font-size: 0.75rem;" 
                                        title="Tandai sudah dibaca">
                                    <i class="bi bi-check2"></i>
                                    <span class="d-none d-md-inline">Dibaca</span>
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('notifications.destroy', $notif) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus notifikasi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-sm btn-outline-danger rounded-2 d-inline-flex align-items-center justify-content-center p-1 px-2" 
                                    style="font-size: 0.75rem;" 
                                    title="Hapus notifikasi ini">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="d-flex justify-content-center mt-4 pt-2">
                {{ $notifications->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
