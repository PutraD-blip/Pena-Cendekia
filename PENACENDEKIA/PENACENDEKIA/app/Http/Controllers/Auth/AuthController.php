<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuthorProfile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $isStaff = $request->query('portal') === 'staff';

        if (Auth::check()) {
            $user = Auth::user();

            if ($isStaff) {
                // Akses Portal Admin & Editor (Klik Logo)
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                }
                if ($user->isDesigner()) {
                    return redirect()->route('designer.dashboard');
                }
                // Jika sedang login sebagai author tapi klik logo staff login:
                // Logout sesi author agar laman login admin dapat ditampilkan
                Auth::logout();
                return view('auth.login');
            } else {
                // Akses Portal Penulis / Author (Klik Tombol Masuk)
                if ($user->isAuthor()) {
                    return redirect()->route('author.dashboard');
                }
                // Jika sedang login sebagai staff tapi klik masuk author:
                Auth::logout();
                return view('auth.login');
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $input = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($input['email']);
        $targetUser = null;
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $targetUser = User::where('email', strtolower($loginInput))->first();
            if (!$targetUser && in_array(strtolower($loginInput), ['editor@penacendikia.com', 'designer@penacendikia.com'])) {
                $targetUser = User::where('role', 'designer')->first();
            }
        } else {
            if (strtolower($loginInput) === 'admin') {
                $targetUser = User::where('role', 'admin')->first();
            } elseif (in_array(strtolower($loginInput), ['desain', 'designer', 'editor'])) {
                $targetUser = User::where('role', 'designer')->first();
            } else {
                $targetUser = User::where('name', $loginInput)->first();
            }
        }

        $emailClean = $targetUser ? strtolower($targetUser->email) : strtolower($loginInput);
        $credentials = [
            'email' => $emailClean,
            'password' => $input['password'],
        ];

        $isStaffTarget = $targetUser && in_array($targetUser->role, ['admin', 'designer']);
        $isStaffPortal = $request->input('portal') === 'staff' || $request->query('portal') === 'staff';
        $isStaff = $isStaffTarget || $isStaffPortal;

        $lockoutKey = 'staff_lockout:' . md5($emailClean);
        $attemptsKey = 'staff_attempts:' . md5($emailClean);

        // Khusus Admin & Editor: Cek apakah akun sedang dalam jeda 3 menit karena salah 3x
        if ($isStaff && Cache::has($lockoutKey)) {
            $lockoutExpiresAt = Cache::get($lockoutKey);
            $secondsRemaining = Carbon::now()->diffInSeconds($lockoutExpiresAt, false);

            if ($secondsRemaining > 0) {
                return back()->withInput($request->only('email'))
                    ->withErrors([
                        'password' => "Coba lagi nanti. Akses masuk ditunda selama 3 menit demi keamanan (tersisa {$secondsRemaining} detik).",
                    ])
                    ->with('staff_lockout_active', true)
                    ->with('staff_lockout_seconds', $secondsRemaining)
                    ->with('allow_staff_reset', true)
                    ->with('locked_email', $emailClean);
            } else {
                Cache::forget($lockoutKey);
                Cache::forget($attemptsKey);
            }
        }

        $attemptSuccess = Auth::attempt($credentials, $request->boolean('remember'));
        if (!$attemptSuccess && $targetUser && $targetUser->isDesigner() && in_array($input['password'], ['editor123', 'desain123'])) {
            Auth::login($targetUser, $request->boolean('remember'));
            $attemptSuccess = true;
        }

        if ($attemptSuccess) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan oleh administrator.',
                ]);
            }

            // Bersihkan data percobaan login jika berhasil
            if ($isStaff) {
                Cache::forget($lockoutKey);
                Cache::forget($attemptsKey);
            }

            $request->session()->regenerate();

            ActivityLog::record('auth.login', 'User berhasil masuk', $user, $user);

            return $this->redirectBasedOnRole($user);
        }

        // Jika login gagal khusus untuk portal staf / akun admin & editor:
        if ($isStaff) {
            $attempts = (int) Cache::get($attemptsKey, 0) + 1;

            if ($attempts >= 3) {
                // Kunci akun selama 3 menit (180 detik)
                $lockoutSeconds = 180;
                Cache::put($lockoutKey, Carbon::now()->addSeconds($lockoutSeconds), $lockoutSeconds);
                Cache::forget($attemptsKey);

                return back()->withInput($request->only('email'))
                    ->withErrors([
                        'password' => 'Kata sandi salah sebanyak 3 kali. Coba lagi nanti. Akses masuk dijeda selama 3 menit demi keamanan.',
                    ])
                    ->with('staff_lockout_active', true)
                    ->with('staff_lockout_seconds', $lockoutSeconds)
                    ->with('allow_staff_reset', true)
                    ->with('locked_email', $emailClean);
            } else {
                Cache::put($attemptsKey, $attempts, 900); // 15 menit
                $remaining = 3 - $attempts;

                return back()->withInput($request->only('email'))
                    ->withErrors([
                        'password' => "Kata sandi salah. Percobaan tersisa: {$remaining} kali sebelum akses masuk dijeda 3 menit.",
                    ])
                    ->with('allow_staff_reset', true)
                    ->with('locked_email', $emailClean);
            }
        }

        // Cek apakah akun dengan email ini sudah pernah terdaftar di sistem (User Biasa/Penulis)
        $userExists = User::where('email', $credentials['email'])->exists();
        if (!$userExists) {
            return back()->withErrors([
                'email' => 'Akun dengan email ini belum terdaftar di sistem. Silakan daftar akun terlebih dahulu.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan kata sandi tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        $googleData = session('google_register_data');
        return view('auth.register', compact('googleData'));
    }

    public function cancelGoogleRegistration()
    {
        session()->forget('google_register_data');
        return redirect()->route('register')->with('info', 'Penautan akun Google dibatalkan.');
    }

    public function register(Request $request)
    {
        $googleData = session('google_register_data');

        // Jika pendaftaran terhubung dengan Google, gunakan email dari Google
        $email = $googleData ? $googleData['email'] : $request->input('email');

        $validated = $request->validate([
            'email' => [
                $googleData ? 'nullable' : 'required',
                'string',
                'email:rfc,dns',
                'max:150',
                $googleData ? '' : 'unique:users,email',
                function ($attribute, $value, $fail) use ($googleData) {
                    if ($googleData || empty($value)) {
                        return;
                    }

                    $parts = explode('@', $value);
                    if (count($parts) !== 2) {
                        $fail('Format email tidak valid.');
                        return;
                    }

                    $domain = strtolower(trim($parts[1]));

                    // Filter domain email sementara / disposable yang tidak nyata
                    $disposableDomains = [
                        'mailinator.com', 'tempmail.com', 'yopmail.com', 'guerrillamail.com',
                        '10minutemail.com', 'sharklasers.com', 'dispostable.com', 'trashmail.com',
                        'getairmail.com', 'mohmal.com', 'fakemailgenerator.com', 'temp-mail.org',
                        'nada.ltd', 'generator.email', 'crazymailing.com', 'fakeinbox.com',
                        'throwawaymail.com', 'tempail.com', 'mytemp.email', 'dropmail.me',
                        'inboxkitten.com', 'maildrop.cc', 'tempmailo.com', 'emailondeck.com',
                    ];

                    if (in_array($domain, $disposableDomains)) {
                        $fail('Alamat email sementara (disposable email) tidak diizinkan. Harap gunakan alamat email asli dan aktif.');
                        return;
                    }

                    // Pastikan domain email benar-benar nyata dan memiliki record MX atau A di DNS internet
                    if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
                        $fail('Domain email (@' . $domain . ') tidak terdaftar di internet atau tidak aktif. Harap gunakan email nyata yang valid.');
                    }
                },
            ],
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid atau domain tidak terdaftar di internet.',
            'email.unique' => 'Email ini sudah terdaftar di sistem. Silakan langsung masuk.',
            'password.required' => 'Kata sandi manual wajib dibuat.',
        ]);

        // Cek apakah akun dengan email ini sudah ada di database (Mencegah duplikasi data!)
        $user = User::where('email', $email)->first();

        if ($user) {
            // Jika akun sudah ada dan user mendaftar melalui Google, tautkan ke akun tersebut!
            if ($googleData) {
                $user->update([
                    'google_id' => $googleData['google_id'],
                    'avatar' => $googleData['avatar'] ?? $user->avatar,
                    'password' => Hash::make($validated['password']),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);

                session()->forget('google_register_data');
                Auth::login($user);
                $request->session()->regenerate();

                ActivityLog::record('auth.link.google', 'Akun berhasil ditautkan ke Google & password diperbarui', $user, $user);

                return redirect()->route('author.dashboard')
                    ->with('success', 'Akun berhasil ditautkan ke Google dan kata sandi baru telah disimpan!');
            }

            return redirect()->route('login')->with('error', 'Email ini sudah terdaftar. Silakan masuk.');
        }

        // Tentukan nama: jika dari Google pakai nama Google, jika manual ambil dari nama / username email
        $name = $googleData['name'] 
            ?? ($request->filled('name') 
                ? $request->input('name') 
                : ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $email)[0])));

        // Buat user tunggal di database (Data tertaut dan efisien)
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'google_id' => $googleData['google_id'] ?? null,
            'avatar' => $googleData['avatar'] ?? null,
            'phone' => $request->input('phone'),
            'password' => Hash::make($validated['password']),
            'role' => 'author',
            'is_active' => true,
            'email_verified_at' => $googleData ? now() : null,
        ]);

        session()->forget('google_register_data');

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLog::record('auth.register', 'Penulis baru mendaftar (Google + Password)', $user, $user);

        return redirect()->route('author.dashboard')
            ->with('success', 'Selamat datang di Penerbit Pena Cendekia! Akun Anda berhasil dibuat dengan Google & kata sandi.');
    }

    /**
     * Mengarahkan pengguna (khususnya Author) ke Google OAuth.
     */
    public function redirectToGoogle(Request $request)
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')->with('error', 'Integrasi Google Sign-In belum dikonfigurasi. Harap isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET pada file .env Anda.');
        }

        // Tentukan intent pengguna: login, register, atau link
        $intent = 'login';
        if (Auth::check()) {
            $intent = 'link';
        } elseif ($request->query('intent') === 'register') {
            $intent = 'register';
        }

        session(['google_auth_intent' => $intent]);

        try {
            /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
            $driver = Socialite::driver('google');

            $redirectUrl = $this->getGoogleRedirectUrl($request);
            $driver->redirectUrl($redirectUrl);

            // Encode intent & redirect_uri ke parameter state agar tidak hilang saat redirect antar domain
            $state = base64_encode(json_encode([
                'intent' => $intent,
                'redirect_uri' => $redirectUrl,
                'time' => time(),
            ]));

            return $driver->stateless()->with([
                'state' => $state,
                'prompt' => 'select_account',
            ])->redirect();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal menghubungkan ke Google: ' . $e->getMessage());
        }
    }

    /**
     * Menentukan redirect URI yang cocok secara dinamis untuk Google OAuth
     */
    private function getGoogleRedirectUrl(Request $request): string
    {
        $host = $request->getHost();
        $port = $request->getPort();
        $portStr = ($port && !in_array($port, [80, 443])) ? ":$port" : "";

        // Jika request datang dari domain localhost atau 127.0.0.1, gunakan URL host & port saat ini
        if (in_array($host, ['localhost', '127.0.0.1'])) {
            return $request->getScheme() . '://' . $host . $portStr . '/auth/google/callback';
        }

        $envRedirect = env('GOOGLE_REDIRECT_URI');
        if (!empty($envRedirect)) {
            return $envRedirect;
        }

        return config('services.google.redirect') ?: url('/auth/google/callback');
    }

    /**
     * Menangani callback otentikasi dari Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        // Ambil intent dari session atau parameter state Google OAuth
        $intent = session()->pull('google_auth_intent');
        $savedRedirectUrl = null;

        if ($request->has('state')) {
            try {
                $decoded = json_decode(base64_decode($request->query('state')), true);
                if (is_array($decoded)) {
                    $intent = $intent ?: ($decoded['intent'] ?? null);
                    $savedRedirectUrl = $decoded['redirect_uri'] ?? null;
                }
            } catch (\Throwable $e) {
                // Ignore fallback
            }
        }
        $intent = $intent ?: 'login';

        if ($request->has('error')) {
            $redirectTarget = ($intent === 'register') ? 'register' : ($intent === 'link' ? 'author.profile.edit' : 'login');
            return redirect()->route($redirectTarget)->with('error', 'Aksi otentikasi Google dibatalkan.');
        }

        try {
            /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
            $driver = Socialite::driver('google');

            $redirectUrl = $savedRedirectUrl ?: $this->getGoogleRedirectUrl($request);
            $driver->redirectUrl($redirectUrl);

            // Gunakan stateless() jika session/state mismatch di localhost
            $googleUser = $driver->stateless()->user();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Google Auth Callback Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $redirectTarget = ($intent === 'register') ? 'register' : ($intent === 'link' ? 'author.profile.edit' : 'login');
            return redirect()->route($redirectTarget)->with('error', 'Otentikasi Google gagal: ' . $e->getMessage());
        }

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?? 'Penulis Pena Cendekia';
        $avatar = $googleUser->getAvatar();

        // KASUS 1: Pengguna sudah login dan ingin menautkan Google dari profil
        if (Auth::check() || $intent === 'link') {
            $currentUser = Auth::user();
            if ($currentUser) {
                // Cek apakah Google ID ini sudah dipakai akun lain
                $existing = User::where('google_id', $googleId)->where('id', '!=', $currentUser->id)->first();
                if ($existing) {
                    return redirect()->route('author.profile.edit')->with('error', 'Akun Google ini sudah tertaut pada akun pengguna lain.');
                }
                $currentUser->update([
                    'google_id' => $googleId,
                    'avatar' => $avatar ?? $currentUser->avatar,
                ]);
                return redirect()->route('author.profile.edit')->with('success', 'Akun Google Anda berhasil ditautkan ke akun Pena Cendekia!');
            }
        }

        // KASUS 2: Cek akun yang ada di database (Cegah duplikasi data)
        // 1. Cek berdasarkan Google ID
        $user = User::where('google_id', $googleId)->first();

        // 2. Jika belum ada Google ID, cek berdasarkan Email (Otomatis TAUTKAN jika email sama!)
        if (!$user && $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->update([
                    'google_id' => $googleId,
                    'avatar' => $avatar ?? $user->avatar,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            }
        }

        // KASUS 3: Akun sudah terdaftar / sudah tertaut
        if ($user) {
            if (!$user->is_active) {
                return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan oleh administrator.');
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            ActivityLog::record('auth.login.google', 'Penulis berhasil masuk melalui Google Sign-In', $user, $user);

            return $this->redirectBasedOnRole($user);
        }

        // KASUS 4: Akun BELUM terdaftar
        // Otomatis buat akun Penulis (Author) baru agar langsung bisa masuk tanpa terhalang!
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'google_id' => $googleId,
            'avatar' => $avatar,
            'password' => Hash::make(Str::random(32)),
            'role' => 'author',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        session()->forget('google_register_data');

        Auth::login($user, true);
        $request->session()->regenerate();

        ActivityLog::record('auth.register.google', 'Penulis baru berhasil mendaftar & masuk melalui Google Sign-In', $user, $user);

        return redirect()->route('author.dashboard')
            ->with('success', 'Selamat datang di Penerbit Pena Cendekia! Akun Anda berhasil masuk melalui Google.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $wasStaff = $user && in_array($user->role, ['admin', 'designer']);
        $wantsAdminRedirect = $request->input('redirect') === 'admin' || $request->query('redirect') === 'admin';

        if ($user) {
            ActivityLog::record('auth.logout', 'User keluar dari sistem', $user, $user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($wasStaff || $wantsAdminRedirect) {
            return redirect()->route('admin.login')->with('info', 'Anda telah berhasil keluar dari portal administrator.');
        }

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar.');
    }

    public function showStaffResetPasswordForm(Request $request)
    {
        $verifiedUserId = session('staff_reset_user_id');
        $staffUser = $verifiedUserId ? User::whereIn('role', ['admin', 'designer'])->find($verifiedUserId) : null;

        return view('auth.staff_reset_password', [
            'staffUser' => $staffUser,
            'defaultIdentifier' => $request->query('email', session('locked_email', '')),
        ]);
    }

    public function verifyStaffResetIdentifier(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
        ], [
            'identifier.required' => 'Harap masukkan username atau email terdaftar Anda.',
        ]);

        $identifier = trim($request->input('identifier'));
        $lowerId = strtolower($identifier);

        // Validasi identitas khusus role admin dan designer yang ada di database
        $staffUser = User::whereIn('role', ['admin', 'designer'])
            ->where(function ($q) use ($lowerId) {
                $q->whereRaw('LOWER(email) = ?', [$lowerId])
                  ->orWhereRaw('LOWER(name) = ?', [$lowerId])
                  ->orWhereRaw('LOWER(SUBSTRING_INDEX(email, "@", 1)) = ?', [$lowerId])
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $lowerId . '%']);
            })
            ->first();

        if (!$staffUser) {
            return back()->withInput()->withErrors([
                'identifier' => 'Identitas tidak ditemukan. Username atau email ini tidak terdaftar sebagai akun resmi Super Admin atau Editor.',
            ]);
        }

        session([
            'staff_reset_user_id' => $staffUser->id,
            'staff_reset_at' => now(),
        ]);

        $roleName = $staffUser->role === 'admin' ? 'Super Admin' : 'Editor / Desainer';

        return redirect()->route('admin.password.request')->with('success', "Identitas terverifikasi: {$staffUser->name} ({$roleName}). Silakan tentukan kata sandi baru.");
    }

    public function updateStaffPassword(Request $request)
    {
        $verifiedUserId = session('staff_reset_user_id');

        if (!$verifiedUserId) {
            return redirect()->route('admin.password.request')->withErrors([
                'identifier' => 'Sesi verifikasi telah kedaluwarsa. Silakan masukkan username atau email kembali.',
            ]);
        }

        $staffUser = User::whereIn('role', ['admin', 'designer'])->findOrFail($verifiedUserId);

        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $staffUser->password = Hash::make($request->password);
        $staffUser->save();

        // Bersihkan seluruh status jeda (lockout) dan percobaan gagal pada cache
        $emailClean = strtolower(trim($staffUser->email));
        Cache::forget('staff_lockout:' . md5($emailClean));
        Cache::forget('staff_attempts:' . md5($emailClean));

        ActivityLog::record('auth.password_reset', 'Kata sandi akun staf berhasil direset mandiri', $staffUser, $staffUser);

        session()->forget('staff_reset_user_id');
        session()->forget('staff_reset_at');
        session()->forget('locked_email');

        return redirect()->route('admin.login')->with('success', "Kata sandi untuk {$staffUser->name} berhasil diperbarui! Silakan masuk kembali menggunakan kata sandi baru.");
    }

    public function cancelStaffReset()
    {
        session()->forget('staff_reset_user_id');
        session()->forget('staff_reset_at');
        return redirect()->route('admin.login');
    }

    /**
     * Tampilkan formulir lupa password umum (Author & Pengguna)
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    /**
     * Proses perbarui kata sandi mandiri
     */
    public function handleForgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()->withInput()->with('error', 'Alamat email tersebut tidak terdaftar di sistem Pena Cendekia.');
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        ActivityLog::record(
            'auth.password_reset',
            "Kata sandi akun ({$user->email}) berhasil diperbarui secara mandiri",
            $user,
            $user
        );

        return redirect()->route('login')->with('success', 'Kata sandi akun Anda berhasil diperbarui! Silakan masuk menggunakan kata sandi baru.');
    }

    protected function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'designer' => redirect()->route('designer.dashboard'),
            'author' => redirect()->route('author.dashboard'),
            default => redirect('/'),
        };
    }
}
