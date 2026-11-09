<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Models\DesignTask;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tasks = DesignTask::where('designer_id', $user->id)
            ->with(['submission.user', 'assigner'])
            ->latest('assigned_at')
            ->paginate(15);

        // Naskah yang sedang dalam proses cetak mesin untuk desainer/editor ini
        $printingSubmissions = Submission::where('status', 'printing')
            ->whereHas('designTasks', function ($q) use ($user) {
                $q->where('designer_id', $user->id);
            })
            ->with(['user', 'category', 'designTasks'])
            ->latest('printing_started_at')
            ->get();

        $stats = [
            'total' => DesignTask::where('designer_id', $user->id)->count(),
            'pending' => DesignTask::where('designer_id', $user->id)->where('status', 'not_started')->count(),
            'in_progress' => DesignTask::where('designer_id', $user->id)->where('status', 'in_progress')->count(),
            'revision' => DesignTask::where('designer_id', $user->id)->where('status', 'revision')->count(),
            'done' => DesignTask::where('designer_id', $user->id)->where('status', 'done')->count(),
            'printing' => $printingSubmissions->count(),
        ];

        return view('designer.dashboard', compact('tasks', 'stats', 'printingSubmissions'));
    }
}
