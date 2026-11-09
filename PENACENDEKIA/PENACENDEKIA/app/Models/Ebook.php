<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Ebook extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'author',
        'editor',
        'publisher',
        'category',
        'isbn',
        'edition',
        'publication_year',
        'pages',
        'language',
        'synopsis',
        'cover_image',
        'file_path',
        'file_format',
        'file_size',
        'sample_file_path',
        'price',
        'is_free',
        'access_type',
        'download_count',
        'view_count',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'publication_year' => 'integer',
        'pages' => 'integer',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ebook) {
            if (empty($ebook->slug)) {
                $baseSlug = Str::slug($ebook->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $ebook->slug = $slug;
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'desc');
    }

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
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

    public function getFormattedPriceAttribute(): string
    {
        if ($this->is_free || $this->price <= 0) {
            return 'Gratis';
        }

        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
