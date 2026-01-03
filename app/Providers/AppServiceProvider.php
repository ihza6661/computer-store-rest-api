<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
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
        // Force HTTPS URLs in production (Heroku uses load balancer with SSL termination)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // Global API limiter for public endpoints
        // 60 requests per minute per IP address
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Contact form limiter - stricter to prevent spam
        // 3 requests per minute per IP address
        RateLimiter::for('contacts', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many contact form submissions. Please try again later.',
                    ], 429, $headers);
                });
        });

        // Login limiter - prevents brute-force attacks
        // 5 requests per minute per email + IP combination
        RateLimiter::for('login', function (Request $request) {
            $email = $request->input('email');
            $ip = $request->ip();
            
            return [
                // Limit by email (5 attempts per email)
                Limit::perMinute(5)->by($email ?: $ip)
                    ->response(function (Request $request, array $headers) {
                        return back()->withErrors([
                            'email' => 'Too many login attempts. Please try again in 1 minute.',
                        ])->withHeaders($headers);
                    }),
                // Limit by IP (10 attempts per IP across all emails)
                Limit::perMinute(10)->by($ip)
                    ->response(function (Request $request, array $headers) {
                        return back()->withErrors([
                            'email' => 'Too many login attempts from your IP address. Please try again later.',
                        ])->withHeaders($headers);
                    }),
            ];
        });

        // Authenticated user limiter - higher limits for logged-in users
        // 120 requests per minute per user ID
        RateLimiter::for('api-authenticated', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        // Product import limiter - very high limits for bulk operations
        // 600 requests per minute per user ID (essentially 10 req/sec for imports)
        RateLimiter::for('product-imports', function (Request $request) {
            return Limit::perMinute(600)->by($request->user()?->id ?: $request->ip());
        });
    }
}
