<?php

namespace App\Providers;

use App\Models\Config;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
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
        $config = Config::with('media')->first();
        view()->share('config', $config);
    }
}
