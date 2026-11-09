<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Pembatasan data log: khusus untuk Administrator
        $query = ActivityLog::whereHas('user', function ($q) {
            $q->where('role', 'admin');
        })->with('user');

        // Filter Tipe Aksi (Login / Logout / Semua)
        if ($request->filled('action_type')) {
            switch ($request->action_type) {
                case 'login':
                    $query->where('action', 'LIKE', 'auth.login%');
                    break;
                case 'logout':
                    $query->where('action', 'auth.logout');
                    break;
                case 'auth':
                    $query->where('action', 'LIKE', 'auth.%');
                    break;
                case 'system':
                    $query->where('action', 'NOT LIKE', 'auth.%');
                    break;
            }
        }

        // Filter Pencarian Teks (Nama Admin, Email, IP Address, Keterangan)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%")
                  ->orWhere('action', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->latest()->paginate(25)->withQueryString();

        // Ringkasan Statistik Login Khusus Administrator
        $today = Carbon::today();
        $adminLoginToday = ActivityLog::whereDate('created_at', $today)
            ->where('action', 'LIKE', 'auth.login%')
            ->whereHas('user', fn($q) => $q->where('role', 'admin'))
            ->count();

        $totalStoredLogs = ActivityLog::whereHas('user', fn($q) => $q->where('role', 'admin'))->count();

        $lastAdminLogin = ActivityLog::where('action', 'LIKE', 'auth.login%')
            ->whereHas('user', fn($q) => $q->where('role', 'admin'))
            ->latest()
            ->first();

        $activeAdminSessions = ActivityLog::where('created_at', '>=', Carbon::now()->subHours(24))
            ->where('action', 'LIKE', 'auth.login%')
            ->whereHas('user', fn($q) => $q->where('role', 'admin'))
            ->distinct('user_id')
            ->count('user_id');

        return view('admin.activity_logs.index', compact(
            'logs',
            'adminLoginToday',
            'totalStoredLogs',
            'lastAdminLogin',
            'activeAdminSessions'
        ));
    }
}
