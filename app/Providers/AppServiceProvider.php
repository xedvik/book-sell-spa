<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ApiService;
use App\Services\BookService;
use App\Services\ViewService;
use App\Services\ErrorHandlingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton(ApiService::class, function ($app) {
            return new ApiService();
        });

        $this->app->singleton(BookService::class, function ($app) {
            return new BookService();
        });

        $this->app->singleton(ViewService::class, function ($app) {
            return new ViewService();
        });

        $this->app->singleton(ErrorHandlingService::class, function ($app) {
            return new ErrorHandlingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
