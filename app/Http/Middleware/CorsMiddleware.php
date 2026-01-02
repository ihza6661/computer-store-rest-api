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
        // Allow requests from the frontend only
        $allowedOrigins = [
            // Local development
            'http://localhost:5173',
            'http://localhost:3000',
            'http://localhost:8000',
            // Production frontend
            'https://computer-store.ihza.me',
            // Legacy Vercel domains (keep for transition period)
            'https://computer-store-pontianak-landing.vercel.app',
            'https://computer-store-pontianak-landing-*.vercel.app',
        ];

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
