<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookReview;
use Illuminate\Http\Request;

class BookReviewApiController extends Controller
{
    /**
     * Ambil daftar ulasan dan rekap rating buku dari database MySQL
     */
    public function index(Request $request)
    {
        $bookId = $request->query('book_id');
        $bookTitle = $request->query('book_title');

        $query = BookReview::query()->where('is_approved', true);

        if ($bookId) {
            $query->where('book_id', $bookId);
        } elseif ($bookTitle) {
            $query->where('book_title', 'like', "%{$bookTitle}%");
        }

        $reviews = $query->latest()->take(50)->get();

        $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 4.9;
        $totalReviews = $reviews->count();

        return response()->json([
            'success' => true,
            'message' => 'Daftar ulasan buku berhasil diambil dari database.',
            'data' => [
                'average_rating' => $avgRating,
                'total_reviews' => $totalReviews,
                'reviews' => $reviews,
            ],
        ]);
    }

    /**
     * Kirim ulasan dan rating buku baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => ['nullable', 'integer'],
            'book_title' => ['required', 'string', 'max:255'],
            'reviewer_name' => ['nullable', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'review_text' => ['required', 'string', 'max:500'],
        ]);

        $data = $validated;
        $data['reviewer_name'] = $validated['reviewer_name'] ?: 'Pembaca Pena Cendekia';
        $data['is_approved'] = true; // Langsung aktif agar muncul di aplikasi mobile dan web

        $review = BookReview::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan dan bintang rating Anda berhasil disimpan ke database!',
            'data' => [
                'id' => $review->id,
                'book_id' => $review->book_id,
                'book_title' => $review->book_title,
                'reviewer_name' => $review->reviewer_name,
                'rating' => $review->rating,
                'review_text' => $review->review_text,
                'created_at' => $review->created_at,
                'is_approved' => $review->is_approved,
            ],
        ], 201);
    }
}
