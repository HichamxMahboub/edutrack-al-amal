<?php

namespace App\Providers;

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
        if (env('VERCEL') || env('VIEW_COMPILED_PATH')) {
            $compiledPath = env('VIEW_COMPILED_PATH', '/tmp/views');

            if (! is_dir($compiledPath)) {
                mkdir($compiledPath, 0777, true);
            }

            config([
                'view.compiled' => $compiledPath,
                'cache.default' => env('CACHE_STORE', 'array'),
                'session.driver' => env('SESSION_DRIVER', 'cookie'),
                'logging.default' => env('LOG_CHANNEL', 'stderr'),
            ]);
        }
    }
}
