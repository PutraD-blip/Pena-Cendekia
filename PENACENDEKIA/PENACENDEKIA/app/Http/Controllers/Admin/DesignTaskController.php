<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\DesignTask;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignTaskController extends Controller
{
    public function assign(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'designer_id' => ['required', 'exists:users,id'],
            'task_type' => ['required', 'in:layout,cover'],
        ], [
            'designer_id.required' => 'Pilih desainer dari daftar.',
            'task_type.required' => 'Pilih jenis tugas desain.',
        ]);

        $admin = Auth::user();

        $task = DesignTask::updateOrCreate(
            [
                'submission_id' => $submission->id,
                'task_type' => $validated['task_type'],
            ],
            [
                'designer_id' => $validated['designer_id'],
                'assigned_by' => $admin->id,
                'status' => 'not_started',
                'assigned_at' => now(),
                'admin_approved' => null,
                'admin_notes' => null,
            ]
        );

        $taskLabel = $validated['task_type'] === 'layout' ? 'Layout Isi Buku' : 'Desain Cover';
        ActivityLog::record('task.assigned', "Tugas {$taskLabel} untuk naskah '{$submission->title}' ditugaskan kepada desainer ID {$validated['designer_id']}", $task, $admin);

        NotificationService::send(
            $validated['designer_id'],
            'Tugas Desain Baru!',
            "Anda telah ditugaskan untuk mengerjakan {$taskLabel} naskah '{$submission->title}' ({$submission->code}). Silakan unduh naskah dan mulai pengerjaan.",
            'info',
            'task',
            $task->id
        );

        return back()->with('success', "Tugas {$taskLabel} berhasil ditugaskan ke tim desainer!");
    }

    public function review(Request $request, DesignTask $task)
    {
        $action = $request->input('action'); // 'approve' or 'revision'
        $admin = Auth::user();
        $submission = $task->submission;
        $taskLabel = $task->task_type === 'layout' ? 'Layout Isi Buku' : 'Desain Cover';

        if ($action === 'approve') {
            $task->update([
                'admin_approved' => true,
                'approved_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            ActivityLog::record('task.approved', "Hasil desain {$taskLabel} untuk '{$submission->title}' disetujui Admin", $task, $admin);

            NotificationService::send(
                $task->designer_id,
                "Desain {$taskLabel} Disetujui!",
                "Karya desain {$taskLabel} Anda untuk '{$submission->title}' telah disetujui oleh Admin. Terima kasih!",
                'success',
                'task',
                $task->id
            );

            // Check if all assigned tasks for this submission are approved by BOTH admin and author
            $allTasks = DesignTask::where('submission_id', $submission->id)->get();
            $allApproved = $allTasks->isNotEmpty() && $allTasks->every(fn($t) => $t->admin_approved === true && $t->author_approved === true);

            if ($allApproved && in_array($submission->status, ['in_design'])) {
                if ($submission->service_type === 'editing') {
                    $submission->update(['status' => 'waiting_final_payment']);

                    NotificationService::send(
                        $submission->user_id,
                        'Editing Naskah Selesai Disetujui!',
                        "Kabar gembira! Seluruh hasil editing & tata letak naskah '{$submission->title}' telah disetujui. Silakan lakukan pelunasan biaya untuk menerima berkas naskah final.",
                        'success',
                        'submission',
                        $submission->id
                    );
                } else {
                    $submission->update(['status' => 'ready_to_print']);

                    NotificationService::send(
                        $submission->user_id,
                        'Desain Buku Telah Disetujui!',
                        "Kabar gembira! Seluruh desain untuk buku '{$submission->title}' telah disetujui dan siap masuk ke proses percetakan fisik.",
                        'success',
                        'submission',
                        $submission->id
                    );
                }
            } else {
                NotificationService::send(
                    $submission->user_id,
                    "Desain {$taskLabel} Siap Anda Tinjau!",
                    "Hasil pengerjaan {$taskLabel} untuk naskah '{$submission->title}' telah diverifikasi oleh Admin Redaksi. Silakan buka dashboard untuk memeriksa dan menyetujui hasil desain.",
                    'info',
                    'submission',
                    $submission->id
                );
            }

            return back()->with('success', "Desain {$taskLabel} berhasil disetujui dan langsung diteruskan ke dashboard Penulis!");

        } elseif ($action === 'revision') {
            $validated = $request->validate([
                'admin_notes' => ['required', 'string', 'min:5'],
            ], [
                'admin_notes.required' => 'Catatan revisi desain wajib diisi.',
            ]);

            $task->update([
                'status' => 'revision',
                'admin_approved' => false,
                'admin_notes' => $validated['admin_notes'],
            ]);

            ActivityLog::record('task.revision', "Desain {$taskLabel} untuk '{$submission->title}' memerlukan revisi", $task, $admin);

            NotificationService::send(
                $task->designer_id,
                "Revisi Desain {$taskLabel}",
                "Desain {$taskLabel} untuk '{$submission->title}' memerlukan perbaikan. Catatan admin: {$validated['admin_notes']}.",
                'warning',
                'task',
                $task->id
            );

            return back()->with('warning', "Catatan revisi desain {$taskLabel} telah dikirimkan ke desainer.");
        }

        return back();
    }
}
