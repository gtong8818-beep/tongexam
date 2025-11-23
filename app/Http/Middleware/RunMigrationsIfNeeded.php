<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;

class RunMigrationsIfNeeded
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Try to run a simple query to check if tables exist
            DB::connection()->getPdo();
            
            // Check if users table exists
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
            if (empty($tables)) {
                // Try MySQL check as well
                $tables = DB::select("SHOW TABLES LIKE 'users'");
            }
            
            if (empty($tables)) {
                // Tables don't exist, run migrations
                Artisan::call('migrate:fresh', ['--force' => true]);
            }
        } catch (\Exception $e) {
            // Log but don't break
            \Log::warning('Migration check failed: ' . $e->getMessage());
        }
        
        return $next($request);
    }
}
