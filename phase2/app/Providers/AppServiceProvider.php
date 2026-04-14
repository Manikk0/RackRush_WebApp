<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Global service provider for app bootstrapping.
class AppServiceProvider extends ServiceProvider
{
    // Register container bindings and app services.
    public function register(): void
    {
    }

    // Run startup logic after all services are registered.
    public function boot(): void
    {
    }
}
