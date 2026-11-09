<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\SubmissionRevision;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Submission::with(['user.profile', 'category', 'payments', 'designTasks']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%$search%")
                  ->orWhere('title', 'LIKE', "%$search%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%$search%");
                  });
            });
        }

        $submissions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.submissions.index', compact('submissions'));
    }

    public function show(Submission $submission)
    {
        $submission->load(['user.profile', 'category', 'payments.account', 'designTasks.designer', 'revisions.admin']);
        $designers = User::where('role', 'designer')->where('is_active', true)->get();

        return view('admin.submissions.show', compact('submission', 'designers'));
    }

    public function review(Request $request, Submission $submission)
    {
        $action = $request->input('action'); // 'approve' or 'reject'
        $admin = Auth::user();

        if ($action === 'approve') {
            $validated = $request->validate([
                'total_price' => ['required', 'numeric', 'min:0'],
                'admin_notes' => ['nullable', 'string'],
            ], [
                'total_price.required' => 'Total biaya wajib diisi saat menyetujui naskah.',
                'total_price.min' => 'Total biaya tidak boleh kurang dari Rp 0.',
            ]);

            $totalPrice = $validated['total_price'];
            $dpAmount = $totalPrice * 0.5;
            $finalAmount = $totalPrice * 0.5;

            $submission->update([
                'status' => 'waiting_payment',
                'total_price' => $totalPrice,
                'dp_amount' => $dpAmount,
                'final_amount' => $finalAmount,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'admin_notes' => $validated['admin_notes'],
            ]);

            ActivityLog::record('submission.approved', "Naskah '{$submission->title}' disetujui dengan total biaya Rp " . number_format($totalPrice, 0, ',', '.'), $submission, $admin);

            NotificationService::send(
                $submission->user_id,
                'Naskah Anda Disetujui!',
                "Selamat! Naskah '{$submission->title}' telah disetujui oleh Admin. Total biaya cetak adalah Rp " . number_format($totalPrice, 0, ',', '.') . ". Silakan lakukan transfer DP 50% (Rp " . number_format($dpAmount, 0, ',', '.') . ") untuk memulai proses desain.",
                'success',
                'submission',
                $submission->id
            );

            return redirect()->route('admin.submissions.show', $submission)
                ->with('success', "Naskah disetujui! Status berubah menjadi 'Menunggu Pembayaran' DP 50%.");

        } elseif ($action === 'reject') {
            $validated = $request->validate([
                'revision_notes' => ['required', 'string', 'min:10'],
            ], [
                'revision_notes.required' => 'Catatan revisi wajib diisi agar penulis mengetahui perbaikan yang diperlukan.',
                'revision_notes.min' => 'Catatan revisi minimal 10 karakter.',
            ]);

            $submission->update([
                'status' => 'revision_required',
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'admin_notes' => $validated['revision_notes'],
            ]);

            SubmissionRevision::create([
                'submission_id' => $submission->id,
                'admin_id' => $admin->id,
                'revision_notes' => $validated['revision_notes'],
            ]);

            ActivityLog::record('submission.rejected', "Naskah '{$submission->title}' meminta revisi", $submission, $admin);

            NotificationService::send(
                $submission->user_id,
                'Naskah Memerlukan Revisi',
                "Naskah '{$submission->title}' memerlukan perbaikan. Catatan admin: {$validated['revision_notes']}. Silakan perbaiki dan ajukan kembali.",
                'warning',
                'submission',
                $submission->id
            );

            return redirect()->route('admin.submissions.show', $submission)
                ->with('warning', "Status naskah diubah menjadi 'Perlu Revisi'. Catatan telah dikirimkan ke penulis.");
        }

        return back();
    }

    public function markPrinting(Submission $submission)
    {
        $submission->update([
            'status' => 'printing',
            'printing_started_at' => now(),
        ]);

        ActivityLog::record('submission.printing', "Admin mengirim naskah '{$submission->title}' ke tim percetakan/editor untuk dicetak", $submission, Auth::user());

        // Notifikasi ke desainer/editor yang ditugaskan pada naskah ini
        $designerIds = $submission->designTasks()->pluck('designer_id')->unique();
        foreach ($designerIds as $designerId) {
            NotificationService::send(
                $designerId,
                'Perintah Cetak Naskah!',
                "Naskah '{$submission->title}' ({$submission->code}) telah disetujui dan diperintahkan untuk dicetak ({$submission->quantity} eksemplar, {$submission->cover_type} cover). Silakan proses di mesin cetak.",
                'info',
                'task',
                $submission->id
            );
        }

        NotificationService::send(
            $submission->user_id,
            'Buku Anda Sedang Masuk Proses Cetak!',
            "Kabar gembira! Seluruh desain telah selesai dan Admin telah mengirimkan naskah '{$submission->title}' ke mesin percetakan PT. Pena Cendekia untuk diproses cetak.",
            'info',
            'submission',
            $submission->id
        );

        return back()->with('success', "Perintah cetak berhasil dikirim! Naskah '{$submission->title}' kini berstatus Sedang Dicetak dan masuk antrean Editor/Percetakan.");
    }

    public function markCompleted(Submission $submission)
    {
        $submission->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        ActivityLog::record('submission.completed', "Buku '{$submission->title}' telah selesai", $submission);

        NotificationService::send(
            $submission->user_id,
            'Penerbitan Buku Selesai!',
            "Alhamdulillah! Seluruh proses penerbitan dan pencetakan buku '{$submission->title}' telah selesai. Terima kasih telah mempercayakan karya Anda kepada PT. Pena Cendikia.",
            'success',
            'submission',
            $submission->id
        );

        return back()->with('success', 'Status buku berhasil diselesaikan!');
    }
}
