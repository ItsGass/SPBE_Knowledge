<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Knowledge;
use App\Policies\KnowledgePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Knowledge::class => KnowledgePolicy::class,
        // Jika nanti menambah policy lain, daftar di sini:
        // \App\Models\OtherModel::class => \App\Policies\OtherModelPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // -------------------------------------------
        // Satu tempat untuk override global authorization
        // -------------------------------------------
        // Gunakan Gate::before untuk memberikan hak istimewa
        // (misal: super_admin diberi akses penuh ke semua ability).
        Gate::before(function ($user, $ability) {
            // Pastikan $user ada — Gate::before tidak dipanggil untuk guest.
            if ($user && isset($user->role) && $user->role === 'super_admin') {
                return true; // super_admin melewati semua policy/gate
            }
            return null; // biarkan proses normal (policy/gate) berjalan
        });

        // -------------------------------------------
        // Contoh definisi Gate tambahan (opsional)
        // -------------------------------------------
        // Anda bisa mendefinisikan gate khusus jika ingin memanggilnya
        // tanpa harus memanggil policy secara langsung.
        // Contoh: Gate::allows('verify-knowledge', $knowledge)
        Gate::define('verify-knowledge', function ($user, $knowledge) {
            // ini duplikat dari KnowledgePolicy::verify, tapi berguna jika
            // Anda ingin memanggil Gate langsung (mis. di middleware custom).
            return in_array($user->role ?? '', ['verifikator', 'admin', 'super_admin']);
        });

        // Tambahan: definisikan gate lain bila diperlukan
        // Gate::define('some-ability', fn($user) => $user->role === 'admin');
}
}