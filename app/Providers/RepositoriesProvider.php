<?php

namespace App\Providers;

use App\Interface\Backend\{
    RoleInterface,
    UserInterface,
};

use App\Repositories\Backend\{
    RoleRepository,
    UserRepository,
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

        // Repositroy & Inderface bind for User
        $this->app->bind(
            UserInterface::class,
            UserRepository::class
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
