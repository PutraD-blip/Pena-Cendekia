<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'service_type',
        'package_name',
        'package_price',
        'title',
        'pen_name',
        'editor_name',
        'category_id',
        'target_reader',
        'synopsis',
        'manuscript_file',
        'layout_file',
        'originality_statement_file',
        'estimated_pages',
        'quantity',
        'cover_type',
        'notes',
        'total_price',
        'dp_amount',
        'final_amount',
        'status',
        'printing_started_at',
        'printed_at',
        'printed_by',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
        'submitted_at',
        'completed_at',
    ];

    public function getServiceTypeLabelAttribute(): string
    {
        return match($this->service_type) {
            'cetak' => 'Cetak Buku',
            'hki' => 'Pengurusan HKI',
            'editing' => 'Editing & Tata Letak',
            'konversi' => 'Konversi KTI ke Buku',
            default => 'Penerbitan Buku',
        };
    }

    public function getServiceIconAttribute(): string
    {
        return match($this->service_type) {
            'cetak' => 'bi-printer-fill',
            'hki' => 'bi-shield-check',
            'editing' => 'bi-pencil-square',
            'konversi' => 'bi-journal-bookmark-fill',
            default => 'bi-book-half',
        };
    }

    protected function casts(): array
    {
        return [
            'package_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'dp_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'printing_started_at' => 'datetime',
            'printed_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'submitted_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public static function generateCode(): string
    {
        $year = date('Y');
        $lastSubmission = self::whereYear('created_at', $year)->latest('id')->first();
        $nextNumber = $lastSubmission ? ((int) substr($lastSubmission->code, -5)) + 1 : 1;
        return sprintf('PNC-%s-%05d', $year, $nextNumber);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function dpPayment()
    {
        return $this->hasOne(Payment::class)->where('type', 'dp');
    }

    public function finalPayment()
    {
        return $this->hasOne(Payment::class)->where('type', 'final');
    }

    public function designTasks()
    {
        return $this->hasMany(DesignTask::class);
    }

    public function layoutTask()
    {
        return $this->hasOne(DesignTask::class)->where('task_type', 'layout');
    }

    public function coverTask()
    {
        return $this->hasOne(DesignTask::class)->where('task_type', 'cover');
    }

    public function printer()
    {
        return $this->belongsTo(User::class, 'printed_by');
    }

    public function revisions()
    {
        return $this->hasMany(SubmissionRevision::class)->latest();
    }

    public function isDpVerified(): bool
    {
        return $this->dpPayment && $this->dpPayment->status === 'verified';
    }

    public function isFinalVerified(): bool
    {
        return $this->finalPayment && $this->finalPayment->status === 'verified';
    }

    public function isAllDesignApproved(): bool
    {
        if ($this->designTasks->isEmpty()) {
            return false;
        }
        return $this->designTasks->every(fn($t) => $t->admin_approved === true && $t->author_approved === true);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_review' => 'Menunggu Review',
            'revision_required' => 'Perlu Revisi',
            'waiting_payment' => 'Menunggu Pembayaran DP',
            'payment_verification' => 'Verifikasi Pembayaran',
            'in_design' => 'Proses Desain',
            'ready_to_print' => 'Siap Cetak',
            'printing' => 'Sedang Dicetak',
            'waiting_final_payment' => 'Menunggu Pelunasan (50%)',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending_review' => 'warning',
            'revision_required' => 'danger',
            'waiting_payment' => 'warning text-dark',
            'payment_verification' => 'info text-dark',
            'in_design' => 'primary',
            'ready_to_print' => 'primary',
            'printing' => 'info',
            'waiting_final_payment' => 'warning text-dark',
            'completed' => 'success',
            'cancelled' => 'light text-dark border',
            default => 'secondary',
        };
    }
}
