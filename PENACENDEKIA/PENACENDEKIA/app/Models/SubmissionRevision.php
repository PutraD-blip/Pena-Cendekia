<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionRevision extends Model
{
    protected $fillable = [
        'submission_id',
        'admin_id',
        'revision_notes',
        'revised_at',
    ];

    protected function casts(): array
    {
        return [
            'revised_at' => 'datetime',
        ];
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
