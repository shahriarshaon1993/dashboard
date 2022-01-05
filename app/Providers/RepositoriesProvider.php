<?php

namespace App\Providers;

use App\Interface\Backend\{
    BackupInterface,
    PageInterface,
    ProfileInterface,
    RoleInterface,
    UserInterface,
};

use App\Repositories\Backend\{
    BackupRepository,
    PageRepository,
    ProfileRepository,
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

        // Repositroy & Inderface bind for Backups
        $this->app->bind(
            BackupInterface::class,
            BackupRepository::class
        );

        // Repositroy & Inderface bind for Profile
        $this->app->bind(
            ProfileInterface::class,
            ProfileRepository::class
        );

        // Repositroy & Inderface bind for Page
        $this->app->bind(
            PageInterface::class,
            PageRepository::class
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
