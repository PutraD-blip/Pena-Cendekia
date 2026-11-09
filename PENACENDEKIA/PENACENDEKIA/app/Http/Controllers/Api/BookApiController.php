<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeaturedBook;
use App\Models\Ebook;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookApiController extends Controller
{
    /**
     * Display a listing of books with search and filter.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $type = $request->query('type'); // 'ebook', 'physical_book', or null
        $limit = (int) $request->query('limit', 50);

        $isEbookFilter = ($type === 'ebook') || ($category === 'E-Book') || ($category === 'Digital');
        $isPhysicalFilter = ($type === 'physical_book');

        $mergedList = collect();

        // 1. Fetch E-Books if requested or in general list
        if (!$isPhysicalFilter) {
            $ebookQuery = Ebook::query()->active()->ordered();
            if ($search) {
                $ebookQuery->search($search);
            }
            if ($category && $category !== 'Semua' && !$isEbookFilter) {
                $ebookQuery->where('category', 'like', "%{$category}%");
            }
            $ebooks = $ebookQuery->take($limit)->get();
            foreach ($ebooks as $eb) {
                $mergedList->push($this->formatEbook($eb));
            }
        }

        // 2. Fetch Physical/Featured Books if not an ebook-only filter
        if (!$isEbookFilter) {
            $query = FeaturedBook::query()->active()->ordered();
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
                });
            }
            if ($category && $category !== 'Semua') {
                $query->where('category', 'like', "%{$category}%");
            }
            $featuredBooks = $query->take($limit)->get();
            foreach ($featuredBooks as $fb) {
                $mergedList->push($this->formatFeaturedBook($fb));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar buku & e-book berhasil dimuat.',
            'data' => $mergedList->values(),
        ]);
    }

    /**
     * Display the specified book.
     */
    public function show(int $id)
    {
        // Check if ID belongs to offset ebook ID (1000 + ebook_id)
        if ($id >= 1000) {
            $ebook = Ebook::find($id - 1000);
            if ($ebook) {
                return response()->json([
                    'success' => true,
                    'message' => 'Detail e-book berhasil dimuat.',
                    'data' => $this->formatEbook($ebook),
                ]);
            }
        }

        $book = FeaturedBook::find($id);
        if ($book) {
            return response()->json([
                'success' => true,
                'message' => 'Detail buku berhasil dimuat.',
                'data' => $this->formatFeaturedBook($book),
            ]);
        }

        $ebook = Ebook::find($id);
        if ($ebook) {
            return response()->json([
                'success' => true,
                'message' => 'Detail e-book berhasil dimuat.',
                'data' => $this->formatEbook($ebook),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Buku dengan ID ' . $id . ' tidak ditemukan.',
            'data' => null,
        ], 404);
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'pages' => ['nullable', 'string', 'max:50'],
            'synopsis' => ['nullable', 'string'],
        ]);

        $validated['order'] = FeaturedBook::max('order') + 1;
        $validated['is_active'] = true;
        $validated['rating'] = 5;

        $book = FeaturedBook::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan ke katalog.',
            'data' => $this->formatFeaturedBook($book),
        ], 201);
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, int $id)
    {
        $book = FeaturedBook::find($id);
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan untuk diperbarui.',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'author' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'pages' => ['nullable', 'string', 'max:50'],
            'synopsis' => ['nullable', 'string'],
        ]);

        $book->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diperbarui.',
            'data' => $this->formatFeaturedBook($book),
        ]);
    }

    /**
     * Remove the specified book.
     */
    public function destroy(int $id)
    {
        $book = FeaturedBook::find($id);
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan untuk dihapus.',
                'data' => null,
            ], 404);
        }

        $title = $book->title;
        $book->delete();

        return response()->json([
            'success' => true,
            'message' => "Buku '{$title}' berhasil dihapus.",
            'data' => null,
        ]);
    }

    /**
     * Get book categories for filter and chips.
     */
    public function categories()
    {
        $categories = BookCategory::orderBy('name', 'asc')->get()->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
            ];
        });

        // Add unique categories from active featured books AND ebooks
        $featuredCategories = FeaturedBook::active()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        $ebookCategories = Ebook::active()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        $availableGenres = collect(['E-Book'])
            ->merge($featuredCategories)
            ->merge($ebookCategories)
            ->unique()
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori berhasil dimuat.',
            'data' => [
                'master_categories' => $categories,
                'available_genres' => $availableGenres,
            ],
        ]);
    }

    /**
     * Curated Home payload for mobile dashboard.
     */
    public function home()
    {
        $featured = FeaturedBook::active()->ordered()->take(10)->get();
        $ebooks = Ebook::active()->ordered()->take(10)->get();

        $popularBooks = $featured->take(4)->map(fn($b) => $this->formatFeaturedBook($b))->values();
        $formattedEbooks = $ebooks->map(fn($eb) => $this->formatEbook($eb))->values();

        // Determine latest book (can be the most recent ebook or featured book)
        $latestFeatured = $featured->first();
        $latestEbook = $ebooks->first();

        $latestBook = null;
        if ($latestEbook && (!$latestFeatured || $latestEbook->created_at >= $latestFeatured->created_at)) {
            $latestBook = $this->formatEbook($latestEbook);
        } elseif ($latestFeatured) {
            $latestBook = $this->formatFeaturedBook($latestFeatured);
        }

        $editorPick = $featured->skip(1)->first() ? $this->formatFeaturedBook($featured->skip(1)->first()) : $latestBook;

        $banners = [
            [
                'id' => 1,
                'title' => 'Pena Cendekia Pustaka',
                'subtitle' => 'Penerbit Resmi Anggota IKAPI & Terdaftar Perpusnas RI',
                'tag' => 'Resmi & Ber-ISBN',
            ],
            [
                'id' => 2,
                'title' => 'Layanan Penerbitan Buku',
                'subtitle' => 'Konversi naskah skripsi/tesis/disertasi menjadi buku referensi ber-ISBN',
                'tag' => 'Gratis Konsultasi',
            ],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Beranda mobile berhasil dimuat.',
            'data' => [
                'greeting' => 'Selamat membaca di Pena Cendekia',
                'banners' => $banners,
                'popular_books' => $popularBooks,
                'latest_book' => $latestBook,
                'editor_pick' => $editorPick,
                'ebooks' => $formattedEbooks,
                'total_books' => FeaturedBook::active()->count() + Ebook::active()->count(),
                'total_ebooks' => Ebook::active()->count(),
            ],
        ]);
    }

    /**
     * Format a FeaturedBook instance to API response.
     */
    private function formatFeaturedBook(FeaturedBook $book): array
    {
        $reviews = \App\Models\BookReview::where('is_approved', true)
            ->where(function ($q) use ($book) {
                $q->where('book_id', $book->id)
                  ->orWhere('book_title', $book->title);
            })->get();
        $rating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 4.9;
        $ratingCount = $reviews->count() > 0 ? $reviews->count() : 120;

        return [
            'id' => $book->id,
            'type' => 'physical_book',
            'title' => $book->title,
            'subtitle' => $book->subtitle ?? '',
            'author' => $book->author,
            'editor' => $book->editor ?? 'Tim Redaksi Pena Cendekia',
            'publisher' => 'PT. Pena Cendekia Pustaka',
            'category' => $book->category ?? 'Buku Ajar / Umum',
            'isbn' => $book->isbn ?? '978-623-8123-01-4',
            'year' => (string) ($book->year ?? '2024'),
            'pages' => (string) ($book->pages ?? '200 hlm'),
            'size' => $book->size ?? '15.5 x 23 cm (UNESCO)',
            'synopsis' => $book->synopsis ?? 'Buku karya akademisi terbitan resmi PT. Pena Cendekia Pustaka.',
            'cover_url' => $this->resolveCoverUrl($book->cover_image),
            'rating' => $rating,
            'rating_count' => $ratingCount,
            'price' => 0.0,
            'formatted_price' => 'Tersedia di Perpustakaan',
            'is_free' => true,
            'is_read_ready' => true,
        ];
    }

    /**
     * Format an Ebook instance to API response.
     */
    private function formatEbook(Ebook $ebook): array
    {
        $host = request()->getSchemeAndHttpHost();

        $reviews = \App\Models\BookReview::where('is_approved', true)
            ->where(function ($q) use ($ebook) {
                $q->where('book_id', 1000 + $ebook->id)
                  ->orWhere('book_id', $ebook->id)
                  ->orWhere('book_title', $ebook->title);
            })->get();
        $rating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 5.0;
        $ratingCount = $reviews->count() > 0 ? $reviews->count() : max(1, (int)$ebook->view_count);

        $fileUrl = $ebook->file_path ? "{$host}/storage/" . ltrim($ebook->file_path, '/') : null;
        $sampleUrl = $ebook->sample_file_path ? "{$host}/storage/" . ltrim($ebook->sample_file_path, '/') : null;

        return [
            'id' => 1000 + $ebook->id, // Offset ID to avoid collision
            'type' => 'ebook',
            'title' => $ebook->title,
            'subtitle' => $ebook->edition ?? 'E-Book Digital',
            'author' => $ebook->author,
            'editor' => $ebook->editor ?? 'Tim Redaksi Pena Cendekia',
            'publisher' => $ebook->publisher ?? 'PT. Pena Cendekia Pustaka',
            'category' => $ebook->category ?? 'E-Book / Digital',
            'isbn' => $ebook->isbn ?? '',
            'year' => (string) ($ebook->publication_year ?? date('Y')),
            'pages' => $ebook->pages ? "{$ebook->pages} hlm" : 'E-Book',
            'size' => $ebook->file_size ?? 'PDF Document',
            'synopsis' => $ebook->synopsis ?? 'E-Book terbitan digital resmi PT. Pena Cendekia Pustaka.',
            'cover_url' => $this->resolveCoverUrl($ebook->cover_image),
            'rating' => $rating,
            'rating_count' => $ratingCount,
            'price' => (float) $ebook->price,
            'formatted_price' => $ebook->formatted_price,
            'is_free' => (bool) $ebook->is_free,
            'is_read_ready' => true,
            'file_url' => $fileUrl,
            'sample_url' => $sampleUrl,
            'download_url' => "{$host}/admin/ebooks/{$ebook->id}/download",
        ];
    }

    /**
     * Endpoint Notifikasi Buku Baru yang diunggah di admin/katalog
     */
    public function notifications()
    {
        $notifications = [];

        // 1. Ebook terbaru dari dashboard admin
        $ebooks = Ebook::where('is_active', true)->latest()->take(10)->get();
        foreach ($ebooks as $ebook) {
            $notifications[] = [
                'id' => 1000 + $ebook->id,
                'book_id' => 1000 + $ebook->id,
                'title' => 'E-Book Baru Dirilis: ' . $ebook->title,
                'message' => 'Karya baru oleh ' . $ebook->author . ' kini resmi tersedia dan siap dibaca di aplikasi.',
                'author' => $ebook->author,
                'category' => $ebook->category ?? 'E-Book Digital',
                'cover_url' => $this->resolveCoverUrl($ebook->cover_image),
                'date' => $ebook->created_at ? $ebook->created_at->diffForHumans() : 'Baru saja',
                'type' => 'new_book',
            ];
        }

        // 2. Featured Books terbaru
        $featured = FeaturedBook::where('is_active', true)->latest()->take(5)->get();
        foreach ($featured as $book) {
            $notifications[] = [
                'id' => $book->id,
                'book_id' => $book->id,
                'title' => 'Buku Baru Ditambahkan: ' . $book->title,
                'message' => 'Buku cetak terbaru oleh ' . $book->author . ' telah hadir di katalog perpustakaan.',
                'author' => $book->author,
                'category' => $book->category ?? 'Buku Akademik',
                'cover_url' => $this->resolveCoverUrl($book->cover_image),
                'date' => $book->created_at ? $book->created_at->diffForHumans() : 'Minggu ini',
                'type' => 'new_book',
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar notifikasi buku baru berhasil diambil.',
            'data' => $notifications,
        ]);
    }

    /**
     * Convert relative path to full absolute URL.
     */
    private function resolveCoverUrl(?string $path): string
    {
        $host = request()->getSchemeAndHttpHost();

        if (!$path) {
            return "{$host}/images/books/administrasi-rekam-medis.jpg";
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'images/')) {
            return "{$host}/" . ltrim($path, '/');
        }

        return "{$host}/storage/" . ltrim($path, '/');
    }
}
