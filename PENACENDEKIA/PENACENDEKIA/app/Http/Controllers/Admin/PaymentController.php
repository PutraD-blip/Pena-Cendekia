<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Submission;
use App\Models\DesignTask;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['submission.user', 'account', 'verifier']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $payments = $query->latest('proof_uploaded_at')->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Request $request, Payment $payment)
    {
        $action = $request->input('action'); // 'approve' or 'reject'
        $admin = Auth::user();
        $submission = $payment->submission;

        if ($action === 'approve') {
            $payment->update([
                'status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now(),
                'rejection_notes' => null,
            ]);

            if ($payment->type === 'dp') {
                $submission->update(['status' => 'in_design']);
                $nextStep = 'Proses Desain (Layout & Cover)';

                // Auto-assign ke desainer aktif jika belum ada tugas desain
                $designer = User::where('role', 'designer')->where('is_active', true)->first();
                if ($designer) {
                    $packageName = strtolower($submission->package_name ?? '');
                    $taskTypes = ['layout'];
                    if (!str_contains($packageName, 'layout saja')) {
                        $taskTypes[] = 'cover';
                    }

                    foreach ($taskTypes as $type) {
                        DesignTask::firstOrCreate(
                            [
                                'submission_id' => $submission->id,
                                'task_type' => $type,
                            ],
                            [
                                'designer_id' => $designer->id,
                                'assigned_by' => $admin->id,
                                'status' => 'not_started',
                                'assigned_at' => now(),
                            ]
                        );
                    }
                }
            } else {
                $submission->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
                $nextStep = 'Penerbitan Selesai (Buku Siap Dikirim / Diserahterimakan)';
            }

            $typeLabel = $payment->type === 'dp' ? 'DP (50%)' : 'Pelunasan (50%)';
            ActivityLog::record('payment.verified', "Pembayaran {$typeLabel} untuk naskah '{$submission->title}' berhasil diverifikasi oleh Admin", $payment, $admin);

            NotificationService::send(
                $submission->user_id,
                "Pembayaran {$typeLabel} Terverifikasi!",
                "Pembayaran {$typeLabel} sebesar Rp " . number_format($payment->amount_due, 0, ',', '.') . " telah diverifikasi. Naskah Anda kini memasuki tahap: {$nextStep}.",
                'success',
                'payment',
                $payment->id
            );

            return back()->with('success', "Pembayaran {$typeLabel} berhasil diverifikasi! Naskah beralih ke tahap: {$nextStep}.");

        } elseif ($action === 'reject') {
            $validated = $request->validate([
                'rejection_notes' => ['required', 'string', 'min:5'],
            ], [
                'rejection_notes.required' => 'Alasan penolakan bukti transfer wajib diisi.',
            ]);

            $payment->update([
                'status' => 'rejected',
                'verified_by' => $admin->id,
                'verified_at' => now(),
                'rejection_notes' => $validated['rejection_notes'],
            ]);

            // Revert submission status so author can re-upload
            $revertStatus = $payment->type === 'dp' ? 'waiting_payment' : 'waiting_final_payment';
            $submission->update(['status' => $revertStatus]);

            $typeLabel = $payment->type === 'dp' ? 'DP (50%)' : 'Pelunasan (50%)';
            ActivityLog::record('payment.rejected', "Bukti transfer {$typeLabel} untuk naskah '{$submission->title}' ditolak", $payment, $admin);

            NotificationService::send(
                $submission->user_id,
                "Bukti Pembayaran {$typeLabel} Ditolak",
                "Bukti pembayaran {$typeLabel} belum dapat diverifikasi. Catatan admin: {$validated['rejection_notes']}. Silakan periksa kembali dan unggah bukti transfer yang sah.",
                'error',
                'payment',
                $payment->id
            );

            return back()->with('warning', "Pembayaran {$typeLabel} ditolak. Penulis telah dinotifikasi untuk mengunggah ulang bukti transfer.");
        }

        return back();
    }
}
