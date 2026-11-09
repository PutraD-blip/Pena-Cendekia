<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'related_type',
        'related_id',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get direct destination URL for this notification based on current user role.
     */
    public function getTargetUrlAttribute(): ?string
    {
        if (!$this->related_type || !$this->related_id) {
            return null;
        }

        try {
            $user = auth()->user();
            if (!$user) {
                return null;
            }

            $type = strtolower($this->related_type);

            if ($type === 'submission') {
                if ($user->isAuthor()) {
                    return route('author.submissions.show', $this->related_id);
                } elseif ($user->isAdmin()) {
                    return route('admin.submissions.show', $this->related_id);
                } elseif ($user->isDesigner()) {
                    return route('designer.dashboard');
                }
            }

            if ($type === 'payment') {
                if ($user->isAuthor()) {
                    $payment = \App\Models\Payment::find($this->related_id);
                    if ($payment && $payment->submission_id) {
                        return route('author.payments.show', $payment->submission_id);
                    }
                    return route('author.dashboard');
                } elseif ($user->isAdmin()) {
                    return route('admin.payments.index');
                }
            }

            if ($type === 'task') {
                if ($user->isDesigner()) {
                    return route('designer.tasks.show', $this->related_id);
                } elseif ($user->isAdmin()) {
                    return route('admin.submissions.index');
                }
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }

    /**
     * Get icon classes based on notification type and related entity.
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'success' => 'bi-check2-circle',
            'warning' => 'bi-exclamation-triangle',
            'error'   => 'bi-x-circle',
            default   => 'bi-info-circle',
        };
    }
}

