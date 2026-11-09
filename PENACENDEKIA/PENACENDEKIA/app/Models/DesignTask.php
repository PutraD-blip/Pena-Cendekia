<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignTask extends Model
{
    protected $fillable = [
        'submission_id',
        'designer_id',
        'assigned_by',
        'task_type',
        'status',
        'result_file',
        'designer_notes',
        'admin_approved',
        'admin_notes',
        'author_approved',
        'author_notes',
        'author_approved_at',
        'assigned_at',
        'started_at',
        'completed_at',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'admin_approved' => 'boolean',
            'author_approved' => 'boolean',
            'assigned_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'approved_at' => 'datetime',
            'author_approved_at' => 'datetime',
        ];
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function getTaskTypeLabelAttribute(): string
    {
        return $this->task_type === 'layout' ? 'Layout Isi Buku' : 'Desain Cover';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'not_started' => 'Belum Dimulai',
            'in_progress' => 'Sedang Dikerjakan',
            'done' => 'Selesai Dikerjakan',
            'revision' => 'Perlu Revisi',
            default => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'not_started' => 'secondary',
            'in_progress' => 'primary',
            'done' => 'success',
            'revision' => 'warning text-dark',
            default => 'secondary',
        };
    }
}
