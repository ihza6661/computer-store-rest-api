<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow requests from the frontend and the app itself
        $allowedOrigins = [
            'http://localhost:5173',
            'http://localhost:3000',
            'http://localhost:8000',
            'https://r-tech-pontianak-landing.vercel.app',
            'https://r-tech-pontianak-landing-*.vercel.app',
        ];

        // Add the app's own URL for Inertia admin panel
        if (env('APP_URL')) {
            $allowedOrigins[] = env('APP_URL');
        }

        $origin = $request->header('Origin');

        // Check if origin is allowed (including wildcard patterns)
        $isAllowed = false;
        foreach ($allowedOrigins as $allowed) {
            if ($allowed === $origin) {
                $isAllowed = true;
                break;
            }
            // Check wildcard patterns
            if (strpos($allowed, '*') !== false) {
                $pattern = str_replace('*', '.*', preg_quote($allowed, '/'));
                if (preg_match('/^'.$pattern.'$/', $origin)) {
                    $isAllowed = true;
                    break;
                }
            }
        }

        if ($isAllowed) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Cookie')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400');
        }

        return $next($request);
    }
}
