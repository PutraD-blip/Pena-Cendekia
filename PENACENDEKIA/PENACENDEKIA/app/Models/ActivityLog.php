<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, ?string $description = null, $subject = null, ?User $user = null): ?self
    {
        $currentUser = $user ?? auth()->user();

        // Hanya catat log aktivitas untuk Administrator
        if ($currentUser && !$currentUser->isAdmin()) {
            return null;
        }

        $log = self::create([
            'user_id' => $currentUser?->id,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Jaga agar database tidak penuh: batasi maksimal 100 riwayat terbaru
        self::pruneOldLogs(100);

        return $log;
    }

    /**
     * Memangkas log lama agar database tetap ringan (maksimal 100 data).
     */
    public static function pruneOldLogs(int $limit = 100): void
    {
        $total = self::count();
        if ($total > $limit) {
            $keepIds = self::latest('id')->take($limit)->pluck('id');
            self::whereNotIn('id', $keepIds)->delete();
        }
    }

    public function getDeviceAttribute(): string
    {
        if (empty($this->user_agent)) {
            return 'Perangkat tidak diketahui';
        }

        $ua = $this->user_agent;
        $os = 'Perangkat Lain';
        if (preg_match('/windows nt 10/i', $ua)) $os = 'Windows 10/11';
        elseif (preg_match('/windows/i', $ua)) $os = 'Windows';
        elseif (preg_match('/android/i', $ua)) $os = 'Android';
        elseif (preg_match('/iphone|ipad|ipod/i', $ua)) $os = 'iOS';
        elseif (preg_match('/macintosh|mac os x/i', $ua)) $os = 'macOS';
        elseif (preg_match('/linux/i', $ua)) $os = 'Linux';

        $browser = 'Browser';
        if (preg_match('/edg/i', $ua)) $browser = 'Edge';
        elseif (preg_match('/chrome/i', $ua)) $browser = 'Chrome';
        elseif (preg_match('/firefox/i', $ua)) $browser = 'Firefox';
        elseif (preg_match('/safari/i', $ua)) $browser = 'Safari';
        elseif (preg_match('/opera|opr/i', $ua)) $browser = 'Opera';

        return "{$os} &bull; {$browser}";
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'auth.login' => 'Login (Password)',
            'auth.login.google' => 'Login (Google Sign-In)',
            'auth.logout' => 'Keluar (Logout)',
            'auth.register' => 'Pendaftaran Akun',
            'auth.register.google' => 'Daftar (Google Sign-In)',
            'auth.password_reset' => 'Reset Kata Sandi',
            'auth.link.google' => 'Tautkan Akun Google',
            'submission.created' => 'Pengajuan Naskah Baru',
            'submission.approved' => 'Naskah Disetujui',
            'submission.rejected' => 'Revisi Naskah',
            'payment.verified' => 'Verifikasi Pembayaran',
            'payment.rejected' => 'Tolak Pembayaran',
            'payment.proof_uploaded' => 'Unggah Bukti Bayar',
            default => $this->action,
        };
    }

    public function isAuthAction(): bool
    {
        return str_starts_with($this->action, 'auth.');
    }
}
