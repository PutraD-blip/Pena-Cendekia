<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;
        $isProfileComplete = $profile && $profile->isComplete();

        $submissions = Submission::where('user_id', $user->id)
            ->with(['category', 'payments.account', 'designTasks'])
            ->latest()
            ->paginate(10);

        // Naskah terbaru untuk pelacakan alur utama
        $latestSubmission = Submission::where('user_id', $user->id)
            ->with(['category', 'payments.account', 'designTasks'])
            ->latest()
            ->first();

        // Naskah yang disetujui admin atau memiliki catatan admin
        $approvedSubmission = Submission::where('user_id', $user->id)
            ->where(function ($q) {
                $q->whereIn('status', ['waiting_payment', 'in_design', 'ready_to_print', 'printing', 'waiting_final_payment', 'completed'])
                  ->orWhereNotNull('admin_notes');
            })
            ->latest('updated_at')
            ->first();

        // Naskah yang memerlukan tindakan pembayaran aktif (DP atau Pelunasan atau Verifikasi)
        $paymentSubmission = Submission::where('user_id', $user->id)
            ->whereIn('status', ['waiting_payment', 'waiting_final_payment', 'ready_to_print', 'payment_verification'])
            ->latest('updated_at')
            ->first();

        // Rekening bank resmi untuk instruksi pembayaran
        $paymentAccounts = PaymentAccount::where('is_active', true)->get();

        $stats = [
            'total' => Submission::where('user_id', $user->id)->count(),
            'review' => Submission::where('user_id', $user->id)->whereIn('status', ['pending_review', 'revision_required'])->count(),
            'payment' => Submission::where('user_id', $user->id)->whereIn('status', ['waiting_payment', 'waiting_final_payment', 'payment_verification'])->count(),
            'design' => Submission::where('user_id', $user->id)->where('status', 'in_design')->count(),
            'completed' => Submission::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        return view('author.dashboard', compact(
            'user',
            'profile',
            'isProfileComplete',
            'submissions',
            'latestSubmission',
            'approvedSubmission',
            'paymentSubmission',
            'paymentAccounts',
            'stats'
        ));
    }
}
