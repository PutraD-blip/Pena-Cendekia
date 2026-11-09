<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReview extends Model
{
    protected $fillable = [
        'book_id',
        'book_title',
        'reviewer_name',
        'rating',
        'review_text',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];
}
