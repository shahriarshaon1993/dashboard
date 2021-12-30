<?php

namespace App\Providers;

use App\Interface\Backend\{
    RoleInterface,
};

use App\Repositories\Backend\{
    RoleRepository,
};

use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Repositroy & Inderface bind for Role
        $this->app->bind(
            RoleInterface::class,
            RoleRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}