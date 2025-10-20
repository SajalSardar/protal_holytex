<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
        Paginator::useBootstrapFive();

        $publicPath  = public_path('storage');
        $storagePath = storage_path('app/public');

        if (!file_exists($publicPath)) {
            @symlink($storagePath, $publicPath);
        }
    }
}
