<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\DesignTask;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DesignTaskController extends Controller
{
    public function show(DesignTask $task)
    {
        $user = Auth::user();
        if ($task->designer_id !== $user->id) {
            abort(403, 'Anda tidak ditugaskan pada pekerjaan ini.');
        }

        $task->load(['submission.user', 'submission.category', 'assigner']);
        return view('designer.tasks.show', compact('task'));
    }

    public function start(DesignTask $task)
    {
        $user = Auth::user();
        if ($task->designer_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $task->update([
            'status' => 'in_progress',
            'started_at' => $task->started_at ?? now(),
        ]);

        $taskLabel = $task->task_type === 'layout' ? 'Layout' : 'Cover';
        ActivityLog::record('task.started', "Desainer mulai mengerjakan {$taskLabel} untuk '{$task->submission->title}'", $task, $user);

        return back()->with('success', 'Status pengerjaan berhasil diubah menjadi: Sedang Dikerjakan.');
    }

    public function uploadResult(Request $request, DesignTask $task)
    {
        $user = Auth::user();
        if ($task->designer_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $isLayout = $task->task_type === 'layout';

        $rules = [
            'result_file' => ['required', 'file', $isLayout ? 'mimes:pdf,docx,zip' : 'mimes:jpg,jpeg,png,pdf,zip', 'max:102400'],
            'designer_notes' => ['nullable', 'string', 'max:1000'],
        ];

        $messages = [
            'result_file.required' => 'File hasil karya desain wajib diunggah.',
            'result_file.mimes' => $isLayout ? 'Format hasil layout harus PDF, DOCX, atau ZIP.' : 'Format hasil cover harus JPG, PNG, PDF, atau ZIP.',
            'result_file.max' => 'Ukuran file maksimal 100MB.',
        ];

        $validated = $request->validate($rules, $messages);

        $folder = $isLayout ? 'private/design_results/layouts' : 'private/design_results/covers';
        $file = $request->file('result_file');
        $fileName = 'hasil_' . $task->task_type . '_' . $task->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($folder, $fileName, 'local');

        $task->update([
            'status' => 'done',
            'result_file' => $fileName,
            'designer_notes' => $validated['designer_notes'],
            'completed_at' => now(),
            'admin_approved' => null, // Reset approval status so admin reviews fresh deliverable
        ]);

        $taskLabel = $isLayout ? 'Layout Isi Buku' : 'Desain Cover';
        ActivityLog::record('task.result_uploaded', "Desainer mengunggah hasil karya {$taskLabel} untuk '{$task->submission->title}'", $task, $user);

        NotificationService::notifyAdmins(
            "Hasil Desain Siap Ditinjau ({$taskLabel})",
            "Desainer {$user->name} telah menyelesaikan dan mengunggah hasil karya {$taskLabel} untuk naskah '{$task->submission->title}' ({$task->submission->code}). Harap diperiksa.",
            'info',
            'task',
            $task->id
        );

        return redirect()->route('designer.tasks.show', $task)
            ->with('success', "Hasil karya {$taskLabel} berhasil diunggah! Admin akan meninjau hasil pekerjaan Anda.");
    }

    public function finishPrinting(Request $request, \App\Models\Submission $submission)
    {
        $user = Auth::user();

        // Pastikan editor ini yang ditugaskan pada naskah ini
        $isAssigned = $submission->designTasks()->where('designer_id', $user->id)->exists();
        if (!$isAssigned) {
            abort(403, 'Anda tidak memiliki hak akses pada naskah ini.');
        }

        if ($submission->status !== 'printing') {
            return back()->with('warning', 'Naskah ini tidak dalam status proses cetak.');
        }

        $submission->update([
            'status' => 'waiting_final_payment',
            'printed_at' => now(),
            'printed_by' => $user->id,
        ]);

        ActivityLog::record('submission.printed', "Editor {$user->name} menyelesaikan proses cetak naskah '{$submission->title}'", $submission, $user);

        // Notifikasi ke Admin
        NotificationService::notifyAdmins(
            "Percetakan Naskah Selesai!",
            "Editor {$user->name} telah menyelesaikan proses pencetakan untuk buku '{$submission->title}' ({$submission->code}). Naskah kini beralih ke status Menunggu Pelunasan oleh Penulis.",
            'success',
            'submission',
            $submission->id
        );

        // Notifikasi ke Penulis
        NotificationService::send(
            $submission->user_id,
            'Buku Fisik Anda Telah Selesai Dicetak!',
            "Alhamdulillah! Buku '{$submission->title}' ({$submission->code}) sebanyak {$submission->quantity} eksemplar telah selesai dicetak oleh tim percetakan. Silakan lakukan pembayaran Pelunasan sisa 50% untuk pengiriman/serah terima karya Anda.",
            'success',
            'submission',
            $submission->id
        );

        return back()->with('success', "Alhamdulillah! Proses cetak naskah '{$submission->title}' telah selesai ditandai. Sistem otomatis membuka tagihan Pelunasan (50%) untuk Penulis.");
    }
}
