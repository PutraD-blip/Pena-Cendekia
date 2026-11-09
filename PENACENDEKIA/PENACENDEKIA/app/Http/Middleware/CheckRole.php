<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Akun Anda telah dinonaktifkan oleh administrator.']);
        }

        if (!in_array($user->role, $roles)) {
            $userDashboard = match ($user->role) {
                'admin' => route('admin.dashboard'),
                'designer' => route('designer.dashboard'),
                'author' => route('author.dashboard'),
                default => route('home'),
            };

            $currentRoleName = match ($user->role) {
                'admin' => 'Administrator',
                'designer' => 'Desainer / Editor',
                'author' => 'Penulis',
                default => $user->role,
            };

            $targetRolesName = implode(' / ', array_map(function ($r) {
                return match ($r) {
                    'admin' => 'Administrator',
                    'designer' => 'Desainer / Editor',
                    'author' => 'Penulis',
                    default => $r,
                };
            }, $roles));

            // Jika user membuka halaman dari role lain melalui browser, alihkan ke dashboard perannya dengan pesan jelas
            if (!$request->expectsJson()) {
                return redirect($userDashboard)->with('error', "Akses Ditolak: Halaman tersebut khusus untuk {$targetRolesName}. Saat ini Anda sedang login sebagai {$currentRoleName}. Silakan logout terlebih dahulu jika ingin beralih akun.");
            }

            abort(403, "Akses ditolak. Halaman ini memerlukan hak akses {$targetRolesName}. Saat ini Anda terautentikasi sebagai {$currentRoleName}.");
        }

        return $next($request);
    }
}
