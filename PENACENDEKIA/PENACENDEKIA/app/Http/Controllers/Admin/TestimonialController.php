<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhere('quote', 'like', "%{$search}%");
            });
        }

        $testimonials = $query->orderBy('order', 'asc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'quote' => ['required', 'string', 'min:10'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama penulis wajib diisi.',
            'role.required' => 'Profesi / gelar penulis wajib diisi.',
            'quote.required' => 'Isi testimoni kepuasan wajib diisi.',
            'quote.min' => 'Isi testimoni minimal 10 karakter.',
            'photo.image' => 'Berkas foto harus berupa gambar.',
            'rating.required' => 'Rating bintang wajib dipilih (1 - 5).',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('testimonials', 'public');
            $validated['photo'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $testimonial = Testimonial::create($validated);

        ActivityLog::record(
            'admin.testimonial.create',
            "Menambahkan testimoni baru dari: \"{$testimonial->name}\"",
            $testimonial,
            auth()->user()
        );

        return redirect()->route('admin.testimonials.index')
            ->with('success', "Testimoni dari \"{$testimonial->name}\" berhasil ditambahkan!");
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'quote' => ['required', 'string', 'min:10'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama penulis wajib diisi.',
            'role.required' => 'Profesi / gelar penulis wajib diisi.',
            'quote.required' => 'Isi testimoni kepuasan wajib diisi.',
            'quote.min' => 'Isi testimoni minimal 10 karakter.',
            'photo.image' => 'Berkas foto harus berupa gambar.',
            'rating.required' => 'Rating bintang wajib dipilih (1 - 5).',
        ]);

        if ($request->hasFile('photo')) {
            if ($testimonial->photo && !str_starts_with($testimonial->photo, 'images/')) {
                Storage::disk('public')->delete($testimonial->photo);
            }
            $path = $request->file('photo')->store('testimonials', 'public');
            $validated['photo'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $testimonial->update($validated);

        ActivityLog::record(
            'admin.testimonial.update',
            "Memperbarui testimoni dari: \"{$testimonial->name}\"",
            $testimonial,
            auth()->user()
        );

        return redirect()->route('admin.testimonials.index')
            ->with('success', "Testimoni dari \"{$testimonial->name}\" berhasil diperbarui!");
    }

    public function destroy(Testimonial $testimonial)
    {
        $name = $testimonial->name;

        if ($testimonial->photo && !str_starts_with($testimonial->photo, 'images/')) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $testimonial->delete();

        ActivityLog::record(
            'admin.testimonial.delete',
            "Menghapus testimoni dari: \"{$name}\"",
            auth()->user()
        );

        return redirect()->route('admin.testimonials.index')
            ->with('success', "Testimoni dari \"{$name}\" berhasil dihapus.");
    }

    public function toggleStatus(Testimonial $testimonial)
    {
        $testimonial->is_active = !$testimonial->is_active;
        $testimonial->save();

        $statusText = $testimonial->is_active ? 'ditampilkan di beranda' : 'disembunyikan (draft)';

        ActivityLog::record(
            'admin.testimonial.toggle',
            "Mengubah status testimoni \"{$testimonial->name}\" menjadi {$statusText}",
            $testimonial,
            auth()->user()
        );

        return back()->with('success', "Status testimoni \"{$testimonial->name}\" kini {$statusText}.");
    }
}
