<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Payment;
use App\Models\PaymentAccount;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function show(Submission $submission)
    {
        $user = Auth::user();
        if ($submission->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        // Cek status DP dan Pelunasan yang sudah verified
        $dpPayment = Payment::where('submission_id', $submission->id)
            ->where('type', 'dp')
            ->first();
        $isDpVerified = $dpPayment && $dpPayment->status === 'verified';

        $finalPayment = Payment::where('submission_id', $submission->id)
            ->where('type', 'final')
            ->first();
        $isFinalVerified = $finalPayment && $finalPayment->status === 'verified';

        if ($isFinalVerified) {
            return redirect()->route('author.submissions.show', $submission)
                ->with('info', 'Seluruh pembayaran untuk naskah ini telah lunas dan diverifikasi.');
        }

        // Jika DP sudah lunas, maka pembayaran selanjutnya adalah Pelunasan 50%
        $paymentType = $isDpVerified ? 'final' : 'dp';
        $amountDue = $paymentType === 'dp' ? $submission->dp_amount : $submission->final_amount;
        if (!$amountDue || $amountDue <= 0) {
            $amountDue = ($submission->total_price ?? 0) * 0.5;
        }

        $accounts = PaymentAccount::where('is_active', true)->get();
        $existingPayment = $paymentType === 'dp' ? $dpPayment : $finalPayment;

        return view('author.payments.show', compact('submission', 'paymentType', 'amountDue', 'accounts', 'existingPayment'));
    }

    public function uploadProof(Request $request, Submission $submission)
    {
        $user = Auth::user();
        if ($submission->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        // Cek status DP dan Pelunasan yang sudah verified
        $dpPayment = Payment::where('submission_id', $submission->id)
            ->where('type', 'dp')
            ->first();
        $isDpVerified = $dpPayment && $dpPayment->status === 'verified';

        $finalPayment = Payment::where('submission_id', $submission->id)
            ->where('type', 'final')
            ->first();
        $isFinalVerified = $finalPayment && $finalPayment->status === 'verified';

        if ($isFinalVerified) {
            return redirect()->route('author.submissions.show', $submission)
                ->with('info', 'Seluruh pembayaran untuk naskah ini telah lunas.');
        }

        $paymentType = $isDpVerified ? 'final' : 'dp';
        $amountDue = $paymentType === 'dp' ? $submission->dp_amount : $submission->final_amount;
        if (!$amountDue || $amountDue <= 0) {
            $amountDue = ($submission->total_price ?? 0) * 0.5;
        }

        $validated = $request->validate([
            'payment_account_id' => ['required', 'exists:payment_accounts,id'],
            'proof_file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'payment_account_id.required' => 'Silakan pilih rekening bank tujuan transfer.',
            'proof_file.required' => 'Bukti screenshot/struk transfer wajib diunggah.',
            'proof_file.mimes' => 'Format file bukti transfer harus JPG, PNG, atau PDF.',
            'proof_file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        $folder = $paymentType === 'dp' ? 'private/payment_proofs/dp' : 'private/payment_proofs/final';
        $file = $request->file('proof_file');
        $fileName = 'bukti_' . $paymentType . '_' . $submission->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($folder, $fileName, 'local');

        $payment = Payment::updateOrCreate(
            [
                'submission_id' => $submission->id,
                'type' => $paymentType,
            ],
            [
                'payment_account_id' => $validated['payment_account_id'],
                'amount_due' => $amountDue,
                'proof_file' => $fileName,
                'proof_uploaded_at' => now(),
                'status' => 'pending',
                'rejection_notes' => null,
            ]
        );

        $submission->update([
            'status' => 'payment_verification',
        ]);

        $typeLabel = $paymentType === 'dp' ? 'DP (50%)' : 'Pelunasan (50%)';
        ActivityLog::record('payment.proof_uploaded', "Bukti transfer {$typeLabel} diunggah oleh {$user->name}", $payment, $user);

        NotificationService::notifyAdmins(
            "Bukti Pembayaran Baru ({$typeLabel})",
            "Penulis {$user->name} telah mengunggah bukti transfer untuk naskah '{$submission->title}' ({$submission->code}). Harap segera diverifikasi.",
            'info',
            'payment',
            $payment->id
        );

        return redirect()->route('author.submissions.show', $submission)
            ->with('success', "Bukti transfer {$typeLabel} berhasil diunggah! Admin akan segera memeriksa dan memverifikasi pembayaran Anda.");
    }
}
