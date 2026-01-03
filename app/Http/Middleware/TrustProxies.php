<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Heroku and Cloudflare terminate SSL and forward requests through load balancers.
     * We need to trust these proxies to correctly identify the real client IP address
     * for rate limiting and other security features.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * This configuration tells Laravel to trust the following headers from proxies:
     * - X-Forwarded-For: Client's real IP address
     * - X-Forwarded-Host: Original host requested by client
     * - X-Forwarded-Proto: Original protocol (http/https)
     * - X-Forwarded-Port: Original port
     *
     * CRITICAL for rate limiting: Without this, $request->ip() returns the proxy's IP,
     * causing all users to share the same rate limit!
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
