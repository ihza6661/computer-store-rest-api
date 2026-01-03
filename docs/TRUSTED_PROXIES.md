# Trusted Proxy Configuration

## Critical Issue: Why This Configuration is Required

When deploying behind a reverse proxy (Nginx, Apache, Heroku load balancers, Cloudflare, etc.), Laravel needs to be configured to trust proxy headers. **Without this configuration, rate limiting will catastrophically fail in production.**

### The Problem

```php
// Without trusted proxy configuration:
$request->ip() // Returns: 10.0.0.1 (proxy IP)

// ALL users appear to have the same IP address!
// Result: After 60 total requests, EVERYONE is blocked
```

### The Solution

```php
// With trusted proxy configuration:
$request->ip() // Returns: 203.0.113.45 (real client IP)

// Each user has their own independent rate limit
// Result: Rate limiting works correctly per user
```

## Implementation

### 1. Trusted Proxy Middleware

**File:** `app/Http/Middleware/TrustProxies.php`

This middleware is created but not actively used (we use the newer `bootstrap/app.php` configuration instead). It's kept for reference and compatibility.

```php
protected $proxies = '*'; // Trust all proxies

protected $headers =
    Request::HEADER_X_FORWARDED_FOR |      // Client's real IP
    Request::HEADER_X_FORWARDED_HOST |     // Original host
    Request::HEADER_X_FORWARDED_PROTO |    // Original protocol (http/https)
    Request::HEADER_X_FORWARDED_PORT |     // Original port
    Request::HEADER_X_FORWARDED_AWS_ELB;   // AWS ELB support
```

### 2. Bootstrap Configuration

**File:** `bootstrap/app.php`

The primary configuration is in the application bootstrap file:

```php
->withMiddleware(function (Middleware $middleware): void {
    // Trust proxies for correct IP detection
    $middleware->trustProxies(
        at: '*', // Trust all proxies
        headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                 \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                 \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                 \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO |
                 \Illuminate\Http\Request::HEADER_X_FORWARDED_AWS_ELB
    );

    // ... rest of middleware configuration
})
```

## Trusted Headers Explained

| Header                | Purpose                                                      | Example                      |
| --------------------- | ------------------------------------------------------------ | ---------------------------- |
| `X-Forwarded-For`     | **Critical for rate limiting** - Contains the real client IP | `203.0.113.45`               |
| `X-Forwarded-Host`    | Original host requested by client                            | `api.computer-store.ihza.me` |
| `X-Forwarded-Proto`   | Original protocol (needed for SSL redirects)                 | `https`                      |
| `X-Forwarded-Port`    | Original port                                                | `443`                        |
| `X-Forwarded-AWS-ELB` | AWS Elastic Load Balancer support                            | (varies)                     |

## Deployment Scenarios

### Heroku (Current Deployment)

Heroku uses a load balancer that:

- Terminates SSL/TLS
- Sets `X-Forwarded-For` header with client's real IP
- Sets `X-Forwarded-Proto` to indicate original protocol

**Configuration:** `$proxies = '*'` (trust all proxies)

✅ This is configured correctly.

### Cloudflare

Cloudflare adds its own layer of proxying:

- Sets `CF-Connecting-IP` header (Cloudflare-specific)
- Also sets standard `X-Forwarded-For` header

**Configuration:** `$proxies = '*'` works correctly

If you want Cloudflare-specific IP trust, you can restrict to Cloudflare's IP ranges:

```php
protected $proxies = [
    // Cloudflare IPv4 ranges
    '173.245.48.0/20',
    '103.21.244.0/22',
    '103.22.200.0/22',
    // ... (see Cloudflare docs for complete list)
];
```

### Nginx Reverse Proxy

When using Nginx as a reverse proxy:

**Nginx Configuration:**

```nginx
location / {
    proxy_pass http://backend;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_set_header X-Forwarded-Host $host;
    proxy_set_header X-Forwarded-Port $server_port;
}
```

**Laravel Configuration:** Trust Nginx's IP address

```php
protected $proxies = ['127.0.0.1', '10.0.0.1']; // Your Nginx server IP
```

Or trust all proxies with `'*'`.

### Apache Reverse Proxy

**Apache Configuration:**

```apache
ProxyPreserveHost On
RequestHeader set X-Forwarded-Proto https
RequestHeader set X-Forwarded-Port 443
ProxyPass / http://backend/
ProxyPassReverse / http://backend/
```

**Laravel Configuration:** Same as Nginx

## Security Considerations

### Why Trust All Proxies (`*`)?

For platforms like Heroku, Cloudflare, or AWS ELB where you don't control the proxy IPs, trusting all proxies is necessary and safe **if you're not directly exposed to the internet**.

**Safe scenario:**

```
Internet → Cloudflare/Heroku/AWS → Your Laravel App
```

In this case, all requests go through the trusted platform's infrastructure.

**Unsafe scenario:**

```
Internet → Your Laravel App (no proxy)
```

In this case, malicious users could forge `X-Forwarded-For` headers.

### More Restrictive Configuration (Optional)

If you want to be more restrictive and you know your proxy IPs:

```php
// bootstrap/app.php
$middleware->trustProxies(
    at: [
        '10.0.0.1',      // Your load balancer
        '172.16.0.0/12', // Private network range
    ],
    headers: // ... same headers
);
```

### Production Security Checklist

- ✅ Always use HTTPS in production (`APP_URL=https://...`)
- ✅ Set `SESSION_SECURE_COOKIE=true` in production
- ✅ Verify `X-Forwarded-For` is not spoofable (only possible if using trusted proxy)
- ✅ Monitor rate limit logs for suspicious patterns
- ✅ Use `APP_DEBUG=false` in production

## Testing Proxy Configuration

### 1. Debug Route

A debug route has been added to `routes/web.php`:

```php
Route::get('/debug/ip', function (Request $request) {
    return response()->json([
        'detected_ip' => $request->ip(),
        'remote_addr' => $request->server('REMOTE_ADDR'),
        'x_forwarded_for' => $request->header('X-Forwarded-For'),
        'all_ips' => $request->ips(),
        'proxy_headers' => [
            'X-Forwarded-For' => $request->header('X-Forwarded-For'),
            'X-Forwarded-Host' => $request->header('X-Forwarded-Host'),
            'X-Forwarded-Proto' => $request->header('X-Forwarded-Proto'),
            'X-Forwarded-Port' => $request->header('X-Forwarded-Port'),
        ],
    ]);
});
```

**⚠️ IMPORTANT: Remove this route after testing in production!**

### 2. Test in Production

After deployment, test the configuration:

```bash
# Visit your debug endpoint
curl https://api.computer-store.ihza.me/debug/ip

# Expected response:
{
  "detected_ip": "YOUR_REAL_IP",  # Should be your actual IP, not proxy IP
  "remote_addr": "10.0.0.1",      # Proxy/load balancer IP
  "x_forwarded_for": "YOUR_REAL_IP",
  "all_ips": ["YOUR_REAL_IP", "10.0.0.1"],
  "proxy_headers": {
    "X-Forwarded-For": "YOUR_REAL_IP",
    "X-Forwarded-Host": "api.computer-store.ihza.me",
    "X-Forwarded-Proto": "https",
    "X-Forwarded-Port": "443"
  }
}
```

**Verification:**

- ✅ `detected_ip` should be YOUR actual IP (not 10.x or 172.x)
- ✅ `x_forwarded_for` should contain your IP
- ✅ `remote_addr` will be the proxy IP (this is normal)

### 3. Test Rate Limiting

After verifying IP detection, test that rate limiting works per-user:

```bash
# User A makes 60 requests
for i in {1..60}; do
  curl https://api.computer-store.ihza.me/api/products
done

# User B (different IP) should NOT be rate limited
curl https://api.computer-store.ihza.me/api/products
# Should return 200, not 429
```

If User B gets a 429 error, the proxy configuration is incorrect.

## Troubleshooting

### Issue: All users share the same rate limit

**Symptom:** After 60 total requests (from all users combined), everyone gets 429 errors.

**Cause:** Trusted proxy configuration is not working.

**Solution:**

1. Check `bootstrap/app.php` has the `trustProxies()` call
2. Verify headers are set correctly by visiting `/debug/ip`
3. Ensure your proxy/load balancer is setting `X-Forwarded-For` header
4. Check Laravel logs for any errors related to trusted proxies

### Issue: Rate limiting doesn't work at all

**Symptom:** Users can make unlimited requests.

**Cause:** Rate limiter might not be configured or cache is not working.

**Solution:**

1. Verify `app/Providers/AppServiceProvider.php` has rate limiter configuration
2. Check `CACHE_STORE` is set correctly (database or redis)
3. Clear cache: `php artisan cache:clear`

### Issue: `detected_ip` shows `null` or incorrect value

**Symptom:** `/debug/ip` shows null or unexpected IP.

**Cause:** Headers not being set by proxy or wrong header names.

**Solution:**

1. Check your proxy configuration (Nginx/Apache config)
2. Verify proxy is setting `X-Forwarded-For` header
3. Some proxies use different header names - adjust configuration accordingly

## Environment-Specific Configuration

### Development (localhost)

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

No proxy configuration needed. Rate limiting works on `127.0.0.1`.

### Staging/Production (Heroku)

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.computer-store.ihza.me
SESSION_SECURE_COOKIE=true
```

Proxy configuration is active and required.

## Impact on Rate Limiting

With proper trusted proxy configuration:

| Endpoint                   | Rate Limit      | IP Source        | Impact                   |
| -------------------------- | --------------- | ---------------- | ------------------------ |
| `/api/products`            | 60/min          | `$request->ip()` | ✅ Per real user IP      |
| `/api/contacts`            | 3/min           | `$request->ip()` | ✅ Per real user IP      |
| `/login`                   | 5/min per email | `$request->ip()` | ✅ Per real user IP      |
| `/login`                   | 10/min per IP   | `$request->ip()` | ✅ Per real user IP      |
| `/api/admin/*`             | 120/min         | `$user->id`      | ✅ Not affected by proxy |
| `/admin/products/import/*` | 600/min         | `$user->id`      | ✅ Not affected by proxy |

**Note:** Authenticated routes use user ID, so they're not affected by proxy issues. However, public and login endpoints critically depend on correct IP detection.

## Additional Resources

- [Laravel Trusted Proxies Documentation](https://laravel.com/docs/11.x/requests#configuring-trusted-proxies)
- [Heroku HTTP Routing](https://devcenter.heroku.com/articles/http-routing)
- [Cloudflare IP Ranges](https://www.cloudflare.com/ips/)
- [X-Forwarded-For Header Specification](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Forwarded-For)

---

**Last Updated:** January 4, 2026  
**Critical for:** Rate limiting, security, correct IP logging  
**Deployment:** Heroku with SSL termination  
**Status:** ✅ Configured and tested
