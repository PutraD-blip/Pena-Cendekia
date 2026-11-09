<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Payment;
use App\Models\DesignTask;
use App\Models\User;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_submissions' => Submission::count(),
            'pending_review' => Submission::where('status', 'pending_review')->count(),
            'pending_payments' => Payment::where('status', 'pending')->whereNotNull('proof_file')->count(),
            'in_design' => Submission::where('status', 'in_design')->count(),
            'completed' => Submission::where('status', 'completed')->count(),
            'total_authors' => User::where('role', 'author')->count(),
            'total_designers' => User::where('role', 'designer')->count(),
            'in_progress' => Submission::whereNotIn('status', ['completed', 'rejected'])->count(),
            'verified_revenue' => Payment::where('status', 'verified')->sum('amount_due'),
        ];

        $pendingSubmissions = Submission::where('status', 'pending_review')
            ->with(['user', 'category'])
            ->latest('submitted_at')
            ->take(5)
            ->get();

        $pendingPayments = Payment::where('status', 'pending')
            ->whereNotNull('proof_file')
            ->with(['submission.user', 'account'])
            ->latest('proof_uploaded_at')
            ->take(5)
            ->get();

        // Log Aktivitas Khusus Administrator
        $recentActivities = ActivityLog::whereHas('user', function ($q) {
                $q->where('role', 'admin');
            })
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingSubmissions', 'pendingPayments', 'recentActivities'));
    }
}
