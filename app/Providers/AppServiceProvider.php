<?php

namespace App\Providers;

use App\Models\Genre;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
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
     */
    public function boot(): void
    {
        Paginator::useBootstrapThree();
        \Carbon\Carbon::setLocale('id');

        try {
            if (Schema::hasTable('genres')) {
                view()->share('genres', Genre::all());
            }
        } catch (\Throwable $e) {
            // Prevent commands like package:discover, migrate, etc. from crashing if database is not reachable
        }
    }
}
