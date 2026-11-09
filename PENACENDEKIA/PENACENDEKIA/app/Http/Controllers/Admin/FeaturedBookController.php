<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedBook;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeaturedBookController extends Controller
{
    public function index(Request $request)
    {
        $query = FeaturedBook::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        $books = $query->orderBy('order', 'asc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        return view('admin.featured_books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.featured_books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'editor' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'year' => ['nullable', 'string', 'max:10'],
            'pages' => ['nullable', 'string', 'max:50'],
            'size' => ['nullable', 'string', 'max:50'],
            'synopsis' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('featured_books/covers', 'public');
            $validated['cover_image'] = $path;
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $request->input('order', 0);

        $book = FeaturedBook::create($validated);

        ActivityLog::record(
            'admin.featured_book.create',
            "Menambahkan koleksi buku pilihan baru: \"{$book->title}\"",
            $book,
            auth()->user()
        );

        return redirect()->route('admin.featuredBooks.index')
            ->with('success', "Buku \"{$book->title}\" berhasil ditambahkan ke koleksi pilihan!");
    }

    public function edit(FeaturedBook $featuredBook)
    {
        return view('admin.featured_books.edit', ['book' => $featuredBook]);
    }

    public function update(Request $request, FeaturedBook $featuredBook)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'editor' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'year' => ['nullable', 'string', 'max:10'],
            'pages' => ['nullable', 'string', 'max:50'],
            'size' => ['nullable', 'string', 'max:50'],
            'synopsis' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('cover_image')) {
            // Hapus cover lama jika ada di storage public
            if ($featuredBook->cover_image && !str_starts_with($featuredBook->cover_image, 'images/')) {
                Storage::disk('public')->delete($featuredBook->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('featured_books/covers', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['order'] = $request->input('order', 0);

        $featuredBook->update($validated);

        ActivityLog::record(
            'admin.featured_book.update',
            "Memperbarui data koleksi buku pilihan: \"{$featuredBook->title}\"",
            $featuredBook,
            auth()->user()
        );

        return redirect()->route('admin.featuredBooks.index')
            ->with('success', "Perubahan buku \"{$featuredBook->title}\" berhasil disimpan!");
    }

    public function destroy(FeaturedBook $featuredBook)
    {
        $title = $featuredBook->title;

        if ($featuredBook->cover_image && !str_starts_with($featuredBook->cover_image, 'images/')) {
            Storage::disk('public')->delete($featuredBook->cover_image);
        }
        if ($featuredBook->testimonial_photo && !str_starts_with($featuredBook->testimonial_photo, 'images/')) {
            Storage::disk('public')->delete($featuredBook->testimonial_photo);
        }

        $featuredBook->delete();

        ActivityLog::record(
            'admin.featured_book.delete',
            "Menghapus buku pilihan: \"{$title}\"",
            auth()->user()
        );

        return redirect()->route('admin.featuredBooks.index')
            ->with('success', "Buku \"{$title}\" berhasil dihapus dari koleksi pilihan.");
    }

    public function toggleStatus(FeaturedBook $featuredBook)
    {
        $featuredBook->is_active = !$featuredBook->is_active;
        $featuredBook->save();

        $statusText = $featuredBook->is_active ? 'ditampilkan di website' : 'disembunyikan dari website';

        ActivityLog::record(
            'admin.featured_book.toggle',
            "Mengubah status tayang buku \"{$featuredBook->title}\" menjadi {$statusText}",
            $featuredBook,
            auth()->user()
        );

        return back()->with('success', "Status buku \"{$featuredBook->title}\" kini {$statusText}.");
    }
}
