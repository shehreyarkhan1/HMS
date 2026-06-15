<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\ActivityLogger;  // ✅ fix
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('audit.log', function () {
            return new ActivityLogger;
        });
    }

    public function boot(): void
    {
        View::share('layoutsSetting', Setting::allCached());
    }
}
