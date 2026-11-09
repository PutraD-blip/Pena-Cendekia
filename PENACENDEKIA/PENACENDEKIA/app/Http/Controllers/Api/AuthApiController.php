<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    /**
     * Mobile login endpoint.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi tidak cocok.',
                'data' => null,
            ], 401);
        }

        if (isset($user->is_active) && !$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sedang dinonaktifkan.',
                'data' => null,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil. Selamat datang kembali!',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
            ],
        ]);
    }

    /**
     * Mobile registration endpoint.
     */
    public function register(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:20'],
            'google_id' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan login atau gunakan email lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        $validated = $validator->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'google_id' => $request->input('google_id'),
            'avatar' => $request->input('avatar'),
            'role' => 'author',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        try {
            \App\Models\ActivityLog::record(
                'auth.register.mobile',
                "Pengguna baru ({$user->email}) mendaftar akun e-book via aplikasi mobile",
                $user,
                $user
            );
        } catch (\Throwable $e) {
            // Abaikan jika tabel log belum ada
        }

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran akun e-book berhasil. Selamat datang di Pena Cendekia!',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
            ],
        ], 201);
    }

    /**
     * Profile info endpoint.
     */
    public function user(Request $request)
    {
        $userId = $request->query('user_id');
        $user = $userId ? User::find($userId) : User::first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
            ],
        ]);
    }

    /**
     * Mobile Google OAuth Sign-In endpoint.
     */
    public function googleLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'name' => ['nullable', 'string', 'max:255'],
            'google_id' => ['nullable', 'string', 'max:255'],
            'id_token' => ['nullable', 'string'],
            'avatar' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $email = trim(strtolower($validated['email']));
        $googleId = $validated['google_id'] ?? null;
        $name = $validated['name'] ?? null;
        $avatar = $validated['avatar'] ?? null;
        $phone = $validated['phone'] ?? null;
        $customPassword = !empty($validated['password']) ? Hash::make($validated['password']) : null;

        // Jika ada id_token dari Google, coba verifikasi via endpoint tokeninfo Google
        if (!empty($validated['id_token'])) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(3)->get('https://oauth2.googleapis.com/tokeninfo', [
                    'id_token' => $validated['id_token'],
                ]);
                if ($response->successful()) {
                    $payload = $response->json();
                    if (!empty($payload['email'])) {
                        $email = strtolower($payload['email']);
                    }
                    if (!empty($payload['sub'])) {
                        $googleId = $payload['sub'];
                    }
                    if (!empty($payload['name']) && empty($name)) {
                        $name = $payload['name'];
                    }
                    if (!empty($payload['picture']) && empty($avatar)) {
                        $avatar = $payload['picture'];
                    }
                }
            } catch (\Throwable $e) {
                // Lanjutkan secara toleran jika verifikasi tokeninfo gagal karena offline/koneksi
            }
        }

        // 1. Cari user berdasarkan google_id jika ada
        $user = null;
        if ($googleId) {
            $user = User::where('google_id', $googleId)->first();
        }

        // 2. Jika tidak ada, cari berdasarkan email
        if (!$user && $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                if ($googleId) $user->google_id = $googleId;
                if ($avatar && empty($user->avatar)) $user->avatar = $avatar;
                if ($phone && empty($user->phone)) $user->phone = $phone;
                if ($customPassword) $user->password = $customPassword;
                $user->save();
            }
        }

        // 3. Jika belum terdaftar, buat akun author baru secara otomatis
        if (!$user) {
            $user = User::create([
                'name' => $name ?: 'Pengguna Google',
                'email' => $email,
                'phone' => $phone,
                'google_id' => $googleId,
                'avatar' => $avatar,
                'role' => 'author',
                'password' => $customPassword ?: Hash::make(\Illuminate\Support\Str::random(24)),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        if (isset($user->is_active) && !$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sedang dinonaktifkan oleh administrator.',
                'data' => null,
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login Google berhasil. Selamat datang di Pena Cendekia!',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
            ],
        ]);
    }

    /**
     * Mobile forgot password endpoint.
     */
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'new_password' => ['required', 'string', 'min:6'],
        ]);

        $email = trim(strtolower($validated['email']));
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email tidak terdaftar di sistem Pena Cendekia.',
                'data' => null,
            ], 404);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui. Silakan login dengan kata sandi baru Anda.',
            'data' => [
                'email' => $user->email,
            ],
        ]);
    }
}
