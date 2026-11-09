<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nik',
        'address',
        'ktp_file',
        'profile_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isComplete(): bool
    {
        return $this->profile_status === 'complete';
    }

    public function getMaskedNikAttribute(): string
    {
        if (strlen($this->nik) === 16) {
            return substr($this->nik, 0, 4) . '********' . substr($this->nik, -4);
        }
        return '****';
    }
}
