<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FeaturedBook extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'author',
        'editor',
        'category',
        'isbn',
        'year',
        'pages',
        'size',
        'synopsis',
        'cover_image',
        'testimonial_quote',
        'testimonial_author',
        'testimonial_role',
        'testimonial_photo',
        'rating',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'desc');
    }

    public function getCoverUrlAttribute(): string
    {
        if (!$this->cover_image) {
            return asset('images/books/administrasi-rekam-medis.jpg');
        }

        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }

        if (str_starts_with($this->cover_image, 'images/')) {
            return asset($this->cover_image);
        }

        return Storage::disk('public')->url($this->cover_image);
    }

    public function getTestimonialPhotoUrlAttribute(): string
    {
        if (!$this->testimonial_photo) {
            return asset('images/yuni-hd.jpg');
        }

        if (str_starts_with($this->testimonial_photo, 'http://') || str_starts_with($this->testimonial_photo, 'https://')) {
            return $this->testimonial_photo;
        }

        if (str_starts_with($this->testimonial_photo, 'images/')) {
            return asset($this->testimonial_photo);
        }

        return Storage::disk('public')->url($this->testimonial_photo);
    }
}

