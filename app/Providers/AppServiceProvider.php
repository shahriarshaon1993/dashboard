<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @codeCoverageIgnoreStart
     */
    public function boot(): void
    {
        $this->configureVite();
        $this->configureCommands();
        $this->configureModels();

        if (app()->isProduction()) {
            $this->configureUrl();
        }

        $this->configureJson();
        $this->configureDates();
        $this->setDefaultStringLength();
    }

    /**
     * Configure the application's Vite.
     */
    private function configureVite(): void
    {
        Vite::prefetch(concurrency: 3);
        Vite::usePrefetchStrategy('aggressive');
    }

    /**
     * Configure the application's commands
     */
    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );
    }

    /**
     * Configure the application's models.
     */
    private function configureModels(): void
    {
        Model::unguard();
        Model::shouldBeStrict(
            app()->isProduction()
        );
    }

    /**
     * Configure the application's URL.
     *
     * @codeCoverageIgnore
     */
    private function configureUrl(): void
    {
        URL::forceScheme('https');
    }

    /**
     * Configure the application's Json.
     */
    private function configureJson(): void
    {
        JsonResource::withoutWrapping();
    }

    /**
     * Configure the application's dates
     */
    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure default length for migration
     */
    private function setDefaultStringLength(): void
    {
        Schema::defaultStringLength(191);
    }
}
