<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter', 'all');

        $query = $user->appNotifications();

        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        $notifications = $query->paginate(15)->withQueryString();

        $totalCount = $user->appNotifications()->count();
        $unreadCount = $user->unreadNotificationsCount();
        $readCount = $user->appNotifications()->where('is_read', true)->count();

        return view('notifications.index', compact('notifications', 'filter', 'totalCount', 'unreadCount', 'readCount'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Notifikasi berhasil ditandai sudah dibaca.');
    }

    public function markAllAsRead()
    {
        $updated = Auth::user()->appNotifications()->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', $updated > 0 ? "Semua ({$updated}) notifikasi berhasil ditandai sudah dibaca." : 'Semua notifikasi sudah dibaca.');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function clearRead()
    {
        $deleted = Auth::user()->appNotifications()->where('is_read', true)->delete();

        if ($deleted > 0) {
            return back()->with('success', "{$deleted} notifikasi lama yang sudah dibaca berhasil dibersihkan.");
        }

        return back()->with('info', 'Tidak ada notifikasi lama yang sudah dibaca untuk dibersihkan.');
    }
}

