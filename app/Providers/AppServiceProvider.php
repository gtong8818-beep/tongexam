<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

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
        // Auto-run migrations in production if tables don't exist
        if (app()->isProduction()) {
            try {
                if (!DB::table('information_schema.tables')
                    ->where('table_schema', DB::getDatabaseName())
                    ->where('table_name', 'users')
                    ->exists()) {
                    Artisan::call('migrate:fresh', ['--force' => true]);
                }
            } catch (\Exception $e) {
                // Silently fail if migrations can't run
            }
        }
    }
}
