<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Policies\DashboardPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

// use Spatie\Permission\Models\Role;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureSuperAdmin();

        Gate::define('access-dashboard', [DashboardPolicy::class, 'viewAny']);
    }

    /**
     * Configure the super admin for have all permissions.
     */
    private function configureSuperAdmin(): void
    {
        Gate::before(fn (User $user): ?true => $user->hasRole('admin') ? true : null);
    }
}
