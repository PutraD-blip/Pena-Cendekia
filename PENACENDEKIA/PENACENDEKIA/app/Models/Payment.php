<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'submission_id',
        'payment_account_id',
        'type',
        'amount_due',
        'proof_file',
        'proof_uploaded_at',
        'status',
        'verified_by',
        'verified_at',
        'rejection_notes',
    ];

    protected function casts(): array
    {
        return [
            'amount_due' => 'decimal:2',
            'proof_uploaded_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function account()
    {
        return $this->belongsTo(PaymentAccount::class, 'payment_account_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'dp' ? 'Down Payment (DP 50%)' : 'Pelunasan (50%)';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning text-dark',
            'verified' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}
