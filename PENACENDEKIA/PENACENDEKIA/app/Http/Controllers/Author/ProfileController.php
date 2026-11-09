<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\AuthorProfile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new AuthorProfile();
        return view('author.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'numeric', 'digits_between:10,15'],
            'nik' => ['required', 'digits:16', 'unique:author_profiles,nik,' . ($profile?->id ?? 'NULL')],
            'address' => ['required', 'string', 'min:10', 'max:500'],
        ];

        $messages = [
            'name.required' => 'Nama lengkap sesuai KTP wajib diisi.',
            'phone.required' => 'Nomor HP / WhatsApp aktif wajib diisi.',
            'phone.numeric' => 'Nomor HP harus berupa angka.',
            'phone.digits_between' => 'Nomor HP harus antara 10 hingga 15 digit.',
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.digits' => 'NIK harus berjumlah 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar oleh pengguna lain.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'address.min' => 'Alamat minimal 10 karakter.',
        ];

        $validated = $request->validate($rules, $messages);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        if ($profile) {
            $profile->update([
                'nik' => $validated['nik'],
                'address' => $validated['address'],
                'profile_status' => 'complete',
            ]);
        } else {
            $profile = AuthorProfile::create([
                'user_id' => $user->id,
                'nik' => $validated['nik'],
                'address' => $validated['address'],
                'profile_status' => 'complete',
            ]);
        }

        ActivityLog::record('profile.updated', 'Penulis memperbarui profil & NIK', $profile, $user);

        return redirect()->route('author.dashboard')->with('success', 'Profil dan identitas Anda berhasil disimpan! Sekarang Anda dapat mengajukan naskah buku.');
    }
}
