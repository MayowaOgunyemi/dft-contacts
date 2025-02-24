<?php

namespace App\Providers;

use App\Services\ContactService;
use App\Repositories\ContactRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\ContactServiceInterface;
use App\Interfaces\ContactRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the repository interface to its concrete implementation
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);

        // Bind the service interface to its concrete implementation
        $this->app->bind(ContactServiceInterface::class, ContactService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
