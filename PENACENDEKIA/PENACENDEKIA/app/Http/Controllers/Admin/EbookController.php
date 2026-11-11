<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ebook::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('format')) {
            $query->where('file_format', $request->format);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $stats = [
            'total' => Ebook::count(),
            'active' => Ebook::where('is_active', true)->count(),
            'downloads' => (int) Ebook::sum('download_count'),
            'pdf_count' => Ebook::where('file_format', 'pdf')->count(),
            'epub_count' => Ebook::where('file_format', 'epub')->count(),
        ];

        $categories = Ebook::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        $ebooks = $query->orderBy('order', 'asc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        return view('admin.ebooks.index', compact('ebooks', 'stats', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ebooks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:500'],
            'editor' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'edition' => ['nullable', 'string', 'max:50'],
            'publication_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 5)],
            'pages' => ['nullable', 'integer', 'min:1'],
            'language' => ['nullable', 'string', 'max:50'],
            'synopsis' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'ebook_file' => ['required', 'file', 'mimes:pdf,epub', 'max:102400'], // max 100MB
            'sample_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'], // max 20MB
            'access_type' => ['required', 'in:public,registered,restricted'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ], [
            'title.required' => 'Judul E-Book wajib diisi.',
            'author.required' => 'Nama penulis wajib diisi.',
            'ebook_file.required' => 'Berkas e-book (PDF atau EPUB) wajib diunggah.',
            'ebook_file.mimes' => 'Format berkas e-book harus PDF atau EPUB.',
            'ebook_file.max' => 'Ukuran berkas e-book maksimal 100 MB.',
            'cover_image.image' => 'Berkas cover harus berupa gambar.',
            'cover_image.max' => 'Ukuran cover maksimal 5 MB.',
        ]);

        // Upload Cover
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('ebooks/covers', 'public');
        }

        // Upload E-Book File
        $uploadedEbook = $request->file('ebook_file');
        $format = strtolower($uploadedEbook->getClientOriginalExtension());
        $sizeBytes = $uploadedEbook->getSize();
        $validated['file_path'] = $uploadedEbook->store('ebooks/files', 'public');
        $validated['file_format'] = $format;
        $validated['file_size'] = $this->formatBytes($sizeBytes);

        // Upload Sample File
        if ($request->hasFile('sample_file')) {
            $validated['sample_file_path'] = $request->file('sample_file')->store('ebooks/samples', 'public');
        }

        $validated['publisher'] = $validated['publisher'] ?: 'PT. Pena Cendekia Pustaka';
        $validated['language'] = $validated['language'] ?: 'Indonesia';
        $hasPrice = !empty($validated['price']) && (float)$validated['price'] > 0;
        $validated['is_free'] = $request->has('is_free') ? ($request->boolean('is_free') && !$hasPrice) : !$hasPrice;
        $validated['price'] = $validated['is_free'] ? 0 : ($validated['price'] ?? 0);
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
        $validated['order'] = $request->input('order', 0);

        unset($validated['ebook_file'], $validated['sample_file']);

        $ebook = Ebook::create($validated);

        ActivityLog::record(
            'admin.ebook.create',
            "Menambahkan publikasi e-book baru: \"{$ebook->title}\" ({$ebook->file_format}, {$ebook->file_size})",
            $ebook,
            auth()->user()
        );

        return redirect()->route('admin.ebooks.index')
            ->with('success', "E-Book \"{$ebook->title}\" berhasil ditambahkan ke katalog!");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ebook $ebook)
    {
        return view('admin.ebooks.edit', compact('ebook'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ebook $ebook)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:500'],
            'editor' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'edition' => ['nullable', 'string', 'max:50'],
            'publication_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 5)],
            'pages' => ['nullable', 'integer', 'min:1'],
            'language' => ['nullable', 'string', 'max:50'],
            'synopsis' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'ebook_file' => ['nullable', 'file', 'mimes:pdf,epub', 'max:102400'],
            'sample_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'access_type' => ['required', 'in:public,registered,restricted'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ], [
            'title.required' => 'Judul E-Book wajib diisi.',
            'author.required' => 'Nama penulis wajib diisi.',
            'ebook_file.mimes' => 'Format berkas e-book harus PDF atau EPUB.',
            'ebook_file.max' => 'Ukuran berkas e-book maksimal 100 MB.',
            'cover_image.image' => 'Berkas cover harus berupa gambar.',
            'cover_image.max' => 'Ukuran cover maksimal 5 MB.',
        ]);

        // Cover replacement
        if ($request->hasFile('cover_image')) {
            if ($ebook->cover_image && !str_starts_with($ebook->cover_image, 'images/')) {
                Storage::disk('public')->delete($ebook->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('ebooks/covers', 'public');
        }

        // Ebook file replacement
        if ($request->hasFile('ebook_file')) {
            if ($ebook->file_path) {
                Storage::disk('public')->delete($ebook->file_path);
            }
            $uploadedEbook = $request->file('ebook_file');
            $format = strtolower($uploadedEbook->getClientOriginalExtension());
            $sizeBytes = $uploadedEbook->getSize();
            $validated['file_path'] = $uploadedEbook->store('ebooks/files', 'public');
            $validated['file_format'] = $format;
            $validated['file_size'] = $this->formatBytes($sizeBytes);
        }

        // Sample file replacement
        if ($request->hasFile('sample_file')) {
            if ($ebook->sample_file_path) {
                Storage::disk('public')->delete($ebook->sample_file_path);
            }
            $validated['sample_file_path'] = $request->file('sample_file')->store('ebooks/samples', 'public');
        }

        $validated['publisher'] = $validated['publisher'] ?: 'PT. Pena Cendekia Pustaka';
        $validated['language'] = $validated['language'] ?: 'Indonesia';
        $hasPrice = !empty($validated['price']) && (float)$validated['price'] > 0;
        $validated['is_free'] = $request->has('is_free') ? ($request->boolean('is_free') && !$hasPrice) : !$hasPrice;
        $validated['price'] = $validated['is_free'] ? 0 : ($validated['price'] ?? 0);
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
        $validated['order'] = $request->input('order', 0);

        unset($validated['ebook_file'], $validated['sample_file']);

        $ebook->update($validated);

        ActivityLog::record(
            'admin.ebook.update',
            "Memperbarui data e-book: \"{$ebook->title}\"",
            $ebook,
            auth()->user()
        );

        return redirect()->route('admin.ebooks.index')
            ->with('success', "E-Book \"{$ebook->title}\" berhasil diperbarui!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ebook $ebook)
    {
        $title = $ebook->title;

        if ($ebook->cover_image && !str_starts_with($ebook->cover_image, 'images/')) {
            Storage::disk('public')->delete($ebook->cover_image);
        }
        if ($ebook->file_path) {
            Storage::disk('public')->delete($ebook->file_path);
        }
        if ($ebook->sample_file_path) {
            Storage::disk('public')->delete($ebook->sample_file_path);
        }

        $ebook->delete();

        ActivityLog::record(
            'admin.ebook.delete',
            "Menghapus e-book: \"{$title}\"",
            null,
            auth()->user()
        );

        return redirect()->route('admin.ebooks.index')
            ->with('success', "E-Book \"{$title}\" berhasil dihapus.");
    }

    /**
     * Toggle active publication status.
     */
    public function toggleStatus(Ebook $ebook)
    {
        $ebook->is_active = !$ebook->is_active;
        $ebook->save();

        $statusLabel = $ebook->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record(
            'admin.ebook.toggle_status',
            "E-Book \"{$ebook->title}\" {$statusLabel} oleh Admin",
            $ebook,
            auth()->user()
        );

        return redirect()->back()
            ->with('success', "Status e-book \"{$ebook->title}\" berhasil diubah menjadi " . ($ebook->is_active ? 'Aktif' : 'Non-aktif') . ".");
    }

    /**
     * Download the e-book file safely.
     */
    public function download(Ebook $ebook)
    {
        if (!$ebook->file_path || !Storage::disk('public')->exists($ebook->file_path)) {
            return redirect()->back()->with('error', 'Berkas e-book tidak ditemukan di penyimpanan server.');
        }

        $ebook->increment('download_count');

        $downloadName = Str::slug($ebook->title) . '.' . $ebook->file_format;
        return Storage::disk('public')->download($ebook->file_path, $downloadName);
    }

    /**
     * Preview the e-book or sample inline.
     */
    public function preview(Request $request, Ebook $ebook)
    {
        $useSample = $request->query('sample') === '1' && $ebook->sample_file_path;
        $filePath = $useSample ? $ebook->sample_file_path : $ebook->file_path;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'Berkas pratinjau e-book tidak ditemukan di server.');
        }

        $ebook->increment('view_count');

        $fullPath = Storage::disk('public')->path($filePath);
        $mime = $ebook->file_format === 'pdf' ? 'application/pdf' : 'application/epub+zip';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    /**
     * Helper to format bytes to human-readable size.
     */
    private function formatBytes(int $bytes, int $precision = 1): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
