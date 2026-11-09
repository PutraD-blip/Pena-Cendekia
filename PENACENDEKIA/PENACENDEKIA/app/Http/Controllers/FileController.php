<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Payment;
use App\Models\DesignTask;
use App\Models\AuthorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show(Request $request, string $type, string $filename)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $allowedTypes = ['ktp', 'manuscripts', 'layouts_from_author', 'statements', 'payment_proofs_dp', 'payment_proofs_final', 'design_layouts', 'design_covers'];
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'Tipe file tidak valid.');
        }

        $relativePath = match ($type) {
            'ktp' => 'private/ktp/' . $filename,
            'manuscripts' => 'private/manuscripts/' . $filename,
            'layouts_from_author' => 'private/layouts_from_author/' . $filename,
            'statements' => 'private/statements/' . $filename,
            'payment_proofs_dp' => 'private/payment_proofs/dp/' . $filename,
            'payment_proofs_final' => 'private/payment_proofs/final/' . $filename,
            'design_layouts' => 'private/design_results/layouts/' . $filename,
            'design_covers' => 'private/design_results/covers/' . $filename,
        };

        if (!Storage::disk('local')->exists($relativePath)) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        // Authorization check
        if (!$user->isAdmin()) {
            if ($type === 'ktp') {
                $profile = AuthorProfile::where('user_id', $user->id)->first();
                if (!$profile || !str_contains($profile->ktp_file, $filename)) {
                    abort(403, 'Akses ditolak.');
                }
            } elseif ($type === 'manuscripts' || $type === 'layouts_from_author' || $type === 'statements') {
                $submission = Submission::where('manuscript_file', 'LIKE', "%$filename%")
                    ->orWhere('layout_file', 'LIKE', "%$filename%")
                    ->orWhere('originality_statement_file', 'LIKE', "%$filename%")
                    ->first();
                if (!$submission) {
                    abort(403, 'Akses ditolak.');
                }
                if ($user->isAuthor() && $submission->user_id !== $user->id) {
                    abort(403, 'Akses ditolak.');
                }
                if ($user->isDesigner()) {
                    $hasTask = DesignTask::where('submission_id', $submission->id)
                        ->where('designer_id', $user->id)
                        ->exists();
                    if (!$hasTask) {
                        abort(403, 'Akses ditolak.');
                    }
                }
            } elseif (str_starts_with($type, 'payment_proofs')) {
                $payment = Payment::where('proof_file', 'LIKE', "%$filename%")->first();
                if (!$payment || ($user->isAuthor() && $payment->submission->user_id !== $user->id)) {
                    abort(403, 'Akses ditolak.');
                }
            } elseif ($type === 'design_layouts' || $type === 'design_covers') {
                $task = DesignTask::where('result_file', $filename)
                    ->orWhere('result_file', 'LIKE', "%$filename%")
                    ->first();
                if (!$task) {
                    abort(404, 'Berkas desain tidak ditemukan.');
                }
                if ($user->isAuthor() && $task->submission->user_id !== $user->id) {
                    abort(403, 'Akses ditolak.');
                }
                if ($user->isAuthor() && $task->submission->status !== 'completed' && $request->has('download')) {
                    abort(403, 'File master asli belum dapat diunduh sebelum pelunasan dan proses percetakan selesai. Silakan gunakan fitur pratinjau.');
                }
                if ($user->isDesigner() && $task->designer_id !== $user->id) {
                    abort(403, 'Akses ditolak.');
                }
            }
        }

        $fullPath = Storage::disk('local')->path($relativePath);
        $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filename) . '"',
        ];

        if ($user && $user->isAuthor() && in_array($type, ['design_layouts', 'design_covers'])) {
            $isCompleted = isset($task) && $task->submission->status === 'completed';
            if (!$isCompleted) {
                $headers['Cache-Control'] = 'no-store, no-cache, must-revalidate, max-age=0';
                $headers['Pragma'] = 'no-cache';
                $headers['X-Content-Type-Options'] = 'nosniff';
            }
        }

        return response()->file($fullPath, $headers);
    }
}
