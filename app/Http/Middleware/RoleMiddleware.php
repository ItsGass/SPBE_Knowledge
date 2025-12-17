<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Map alias names ke array role yang diizinkan.
     * Di sini kita pastikan 'public' dan 'verif' -> verifikator + super_admin
     */
    protected array $aliasMap = [
        'public'      => ['verifikator', 'super_admin'],
        'verif'       => ['verifikator', 'super_admin'],
        'verification'=> ['verifikator', 'super_admin'], // optional variasi
        // tetap bisa menambahkan alias lain bila perlu
    ];

    /**
     * Handle an incoming request.
     * Usage:
     *   ->middleware('role:public')
     *   ->middleware('role:verif,draf')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Jika tidak login, tangani berbeda untuk JSON/API vs web biasa
        if (! $user) {
            if ($request->expectsJson()) {
                abort(401, 'Unauthenticated.');
            }
            return redirect()->route('login');
        }

        // Jika roles diberikan sebagai satu string "a,b"
        if (count($roles) === 1 && is_string($roles[0]) && strpos($roles[0], ',') !== false) {
            $roles = explode(',', $roles[0]);
        }

        // Normalisasi input role parameter
        $roles = array_map(fn($r) => trim(strtolower($r)), $roles);

        // Expand aliases: jika parameter adalah alias, ganti/digabung dengan mapping
        $expanded = [];
        foreach ($roles as $r) {
            if (isset($this->aliasMap[$r])) {
                foreach ($this->aliasMap[$r] as $mapped) {
                    $expanded[] = strtolower(trim($mapped));
                }
            } else {
                $expanded[] = $r;
            }
        }
        // Unikkan
        $allowedRoles = array_values(array_unique($expanded));

        // Ambil roles user:
        $userRolesNormalized = [];

        // Jika user->roles ada dan iterable (Collection/array)
        if (isset($user->roles) && (is_array($user->roles) || method_exists($user->roles, 'pluck'))) {
            try {
                if (method_exists($user->roles, 'pluck')) {
                    // coba 'name' dahulu, fallback ke 'role' atau 'title' jika ada
                    if ($user->roles->pluck('name')->isNotEmpty()) {
                        $userRolesNormalized = $user->roles->pluck('name')->map(fn($x) => strtolower((string) $x))->toArray();
                    } elseif ($user->roles->pluck('role')->isNotEmpty()) {
                        $userRolesNormalized = $user->roles->pluck('role')->map(fn($x) => strtolower((string) $x))->toArray();
                    } else {
                        $userRolesNormalized = $user->roles->map(fn($x) => strtolower((string) $x))->toArray();
                    }
                } else {
                    $userRolesNormalized = array_map(fn($x) => strtolower((string) $x), (array) $user->roles);
                }
            } catch (\Throwable $e) {
                $userRolesNormalized = [];
            }
        }

        // Jika masih kosong, cek properti single role: $user->role
        if (empty($userRolesNormalized)) {
            $userRolesNormalized = [strtolower((string) ($user->role ?? ''))];
        }

        // Periksa intersection antara role user dan allowedRoles
        $intersect = array_intersect($userRolesNormalized, $allowedRoles);

        if (empty($intersect)) {
            if ($request->expectsJson()) {
                abort(403, 'Forbidden.');
            }
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
