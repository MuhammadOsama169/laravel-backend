<?php

namespace App\Providers;

use App\Models\Workspace;
use App\Observers\WorkspaceObserver;
use Illuminate\Support\ServiceProvider;

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
        Workspace::observe(WorkspaceObserver::class);
    }
}
