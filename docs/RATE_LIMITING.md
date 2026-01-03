# Rate Limiting Implementation

This document describes the rate limiting implementation for the Computer Store REST API.

## ⚠️ Critical Production Requirement

**Before deploying to production behind a reverse proxy (Nginx, Heroku, Cloudflare, etc.), you MUST configure trusted proxies.**

Without proper proxy configuration, rate limiting will fail catastrophically:

- All users will appear to have the same IP (the proxy's IP)
- After 60 total requests, ALL users will be blocked
- Your API will become unusable

**See:** [`docs/TRUSTED_PROXIES.md`](./TRUSTED_PROXIES.md) for complete proxy configuration instructions.

✅ **Status:** Proxy configuration is already implemented in `bootstrap/app.php`.

---

## Overview

Rate limiting has been implemented using Laravel's native `RateLimiter` facade to protect the application from abuse, spam, and brute-force attacks. Different endpoints have different rate limits based on their sensitivity and expected usage patterns.

## Implementation Date

**January 4, 2026**

## Rate Limiters Configured

All rate limiters are defined in `app/Providers/AppServiceProvider.php` in the `configureRateLimiting()` method.

### 1. Global API Limiter (`api`)

**Limit:** 60 requests per minute per IP address

**Applied to:**

- `GET /api/products`
- `GET /api/products/{product}`
- `GET /api/categories`

**Purpose:** Prevents general API abuse and excessive scraping of public endpoints.

**Key:** IP address (`$request->ip()`)

**⚠️ Requires:** Trusted proxy configuration for correct IP detection in production.

---

### 2. Contact Form Limiter (`contacts`)

**Limit:** 3 requests per minute per IP address

**Applied to:**

- `POST /api/contacts`

**Purpose:** Prevents spam and bot abuse of the contact form. Stricter limit due to the nature of form submissions.

**Key:** IP address (`$request->ip()`)

**⚠️ Requires:** Trusted proxy configuration for correct IP detection in production.

**Custom Response:**

```json
{
    "message": "Too many contact form submissions. Please try again later."
}
```

---

### 3. Login Limiter (`login`)

**Limit:**

- 5 requests per minute per email address
- 10 requests per minute per IP address (across all emails)

**Applied to:**

- `POST /login`

**Purpose:** Prevents brute-force attacks on user accounts. Uses dual-layer protection with both email-based and IP-based limiting.

**Keys:**

- Email address for per-account protection
- IP address for distributed attack prevention

**Custom Response:**

- Email limit exceeded: "Too many login attempts. Please try again in 1 minute."
- IP limit exceeded: "Too many login attempts from your IP address. Please try again later."

---

### 4. Authenticated User Limiter (`api-authenticated`)

**Limit:** 120 requests per minute per user ID

**Applied to:**

- All routes under `/api/admin/*` (authenticated API routes)
    - Products management (POST, PUT, DELETE)
    - Categories management (POST, PUT, DELETE)
    - Contacts management (GET, PUT, DELETE)
    - Users management (GET, POST, PUT, DELETE)

**Purpose:** Higher limits for authenticated users performing administrative tasks. This is 2x the public API limit.

**Key:** User ID (`$request->user()?->id`) or IP address as fallback

---

### 5. Product Import Limiter (`product-imports`)

**Limit:** 600 requests per minute per user ID (10 requests per second)

**Applied to:**

- `POST /admin/products/import/preview`
- `POST /admin/products/import/store`
- `GET /admin/products/import/status/{jobId}`
- `GET /admin/products/import/template`

**Purpose:** Very high limits for bulk import operations. Ensures admins can upload large product datasets without hitting rate limits.

**Key:** User ID (`$request->user()?->id`) or IP address as fallback

---

## HTTP Response Headers

When rate limiting is active, Laravel automatically includes the following headers in responses:

- `X-RateLimit-Limit` - Total number of requests allowed in the time window
- `X-RateLimit-Remaining` - Number of requests remaining in the current window
- `Retry-After` - Seconds until the rate limit resets (only present when limit is exceeded)

### Example Response Headers

```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 45
```

### When Rate Limit is Exceeded

**HTTP Status Code:** `429 Too Many Requests`

**Response Headers:**

```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 0
Retry-After: 45
```

---

## Implementation Files

### 1. `app/Providers/AppServiceProvider.php`

Contains all rate limiter definitions in the `configureRateLimiting()` method. This is the central configuration point for all rate limiters.

**Key Changes:**

- Added imports for `RateLimiter`, `Limit`, and `Request`
- Created `configureRateLimiting()` method
- Defined 5 named rate limiters with custom responses

### 2. `routes/api.php`

Applied rate limiting middleware to API routes.

**Key Changes:**

- Wrapped public API routes in `throttle:api` middleware group
- Applied `throttle:contacts` to contact form endpoint
- Applied `throttle:api-authenticated` to all admin API routes

### 3. `routes/web.php`

Applied rate limiting middleware to authentication and import routes.

**Key Changes:**

- Added `throttle:login` middleware to `POST /login` route
- Wrapped product import routes in `throttle:product-imports` middleware group

---

## Testing Rate Limits

### Automated Test Suite

A comprehensive automated test suite has been created at `tests/Feature/RateLimitTest.php`.

**Test Results (January 4, 2026):**

- **13 tests passed** with **1,408 assertions**
- **Test Duration:** ~7 seconds
- **Coverage:** All rate limiters and edge cases

**To run the test suite:**

```bash
php artisan test --filter=RateLimitTest
```

**Test Coverage:**

1. **Public API Rate Limit** - Verifies 60/min limit on `/api/products`
2. **Contact Form Rate Limit** - Confirms 3/min limit blocks 4th request
3. **Login Email Rate Limit** - Tests 5/min per email address
4. **Login Email Independence** - Ensures different emails have separate limits
5. **Login IP Rate Limit** - Validates 10/min per IP across all emails
6. **Authenticated API Higher Limit** - Confirms 120/min for admin routes
7. **Product Import High Limit** - Verifies 600/min for bulk operations
8. **Rate Limit Headers** - Checks proper header presence and values
9. **Per-IP Rate Limits** - Tests IP-based isolation for public endpoints
10. **Per-User Rate Limits** - Confirms user-based isolation for authenticated routes
11. **429 Response Format** - Validates error responses and retry headers
12. **Categories Endpoint** - Tests rate limiting on `/api/categories`
13. **Product Detail Endpoint** - Tests rate limiting on `/api/products/{id}`

All tests successfully validate that:

- Rate limits are enforced correctly
- Headers are properly set
- Error messages are user-friendly
- Different users/IPs have independent limits
- Import operations have high enough limits for bulk operations

### Manual Testing

#### 1. Test Public API Rate Limit (60/min)

```bash
# Make 60+ requests to the products API
for i in {1..65}; do
  curl -i http://localhost:8000/api/products
  echo "Request $i"
done

# Expected: First 60 succeed, subsequent requests return 429
```

#### 2. Test Contact Form Rate Limit (3/min)

```bash
# Make 4 contact form submissions
for i in {1..4}; do
  curl -X POST http://localhost:8000/api/contacts \
    -H "Content-Type: application/json" \
    -d '{"name":"Test","email":"test@example.com","message":"Test"}'
  echo "Request $i"
  sleep 1
done

# Expected: First 3 succeed, 4th returns 429
```

#### 3. Test Login Rate Limit (5/min per email)

```bash
# Attempt 6 logins with the same email
for i in {1..6}; do
  curl -X POST http://localhost:8000/login \
    -H "Content-Type: application/json" \
    -d '{"email":"test@example.com","password":"wrongpassword"}'
  echo "Attempt $i"
done

# Expected: First 5 attempts processed, 6th returns rate limit error
```

#### 4. Test Authenticated API Rate Limit (120/min)

```bash
# Make 125 authenticated requests (requires valid session cookie)
for i in {1..125}; do
  curl -i http://localhost:8000/api/admin/contacts \
    -H "Cookie: laravel_session=YOUR_SESSION_COOKIE"
  echo "Request $i"
done

# Expected: First 120 succeed, subsequent requests return 429
```

### Automated Testing

**Test File:** `tests/Feature/RateLimitTest.php`

The automated test suite has been fully implemented and is passing all tests.

**Test Results:**

```
PASS  Tests\Feature\RateLimitTest
✓ public api has rate limit (60/min)
✓ contact form rate limit blocks after 3 requests
✓ login rate limit blocks after 5 attempts per email
✓ login rate limit is per email
✓ login rate limit has ip based protection (10/min per IP)
✓ authenticated api has higher rate limit (120/min)
✓ product import has high rate limit (600/min)
✓ rate limit headers are present
✓ rate limits are per ip for public endpoints
✓ authenticated rate limits are per user
✓ rate limit exceeded response format
✓ categories endpoint has rate limit
✓ product detail endpoint has rate limit

Tests:    13 passed (1408 assertions)
Duration: ~7 seconds
```

**Run the test suite:**

```bash
# Run all rate limit tests
php artisan test --filter=RateLimitTest

# Run a specific test
php artisan test --filter=RateLimitTest::test_contact_form_rate_limit_blocks_after_3_requests
```

**Test Coverage Details:**

The test suite validates:

- Contact form blocks after exactly 3 requests/minute
- Login endpoint has dual protection (5/min per email + 10/min per IP)
- Product import routes support 600 requests/minute for bulk operations
- Authenticated users get 2x the rate limit of public users (120 vs 60)
- Rate limit headers (`X-RateLimit-Limit`, `X-RateLimit-Remaining`, `Retry-After`) are present
- Different IPs and users have independent rate limit counters
- 429 responses include proper error messages and retry information

**Example test from the suite:**

```php
public function test_contact_form_rate_limit_blocks_after_3_requests(): void
{
    $contactData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'category' => 'general',
        'message' => 'This is a test message for rate limiting.',
    ];

    // Make 3 requests (should all succeed)
    for ($i = 1; $i <= 3; $i++) {
        $response = $this->postJson('/api/contacts', $contactData);
        $response->assertStatus(201);

        // Verify rate limit headers
        $this->assertEquals(3, $response->headers->get('X-RateLimit-Limit'));
        $this->assertEquals(3 - $i, $response->headers->get('X-RateLimit-Remaining'));
    }

    // 4th request should be blocked with 429 status
    $response = $this->postJson('/api/contacts', $contactData);
    $response->assertStatus(429);
    $response->assertJson([
        'message' => 'Too many contact form submissions. Please try again later.',
    ]);
}
```

See `tests/Feature/RateLimitTest.php` for the complete test suite with 13 comprehensive test cases.

---

### Continuous Integration

Add to your CI/CD pipeline (`.github/workflows/tests.yml`):

```yaml
- name: Run Rate Limit Tests
  run: php artisan test --filter=RateLimitTest
```

This ensures rate limiting remains functional and prevents regressions in future updates.

---

## Monitoring Rate Limits

### Check Rate Limit Headers

```bash
# Check remaining requests
curl -i http://localhost:8000/api/products | grep X-RateLimit

# Output:
# X-RateLimit-Limit: 60
# X-RateLimit-Remaining: 59
```

### Log Rate Limit Hits

Add logging to track when users hit rate limits. Add to `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\Log;

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)
        ->by($request->ip())
        ->response(function (Request $request, array $headers) {
            Log::warning('API rate limit exceeded', [
                'ip' => $request->ip(),
                'endpoint' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'message' => 'Too many requests. Please try again later.',
            ], 429, $headers);
        });
});
```

---

## Troubleshooting

### Issue: Rate limit applies across different users on the same network

**Cause:** Rate limiting uses IP address, so multiple users behind the same NAT/proxy share the same limit.

**Solution:** For authenticated endpoints, we use user ID as the key, which prevents this issue. For public endpoints, this is expected behavior to prevent abuse.

### Issue: Rate limits not resetting

**Cause:** Cache configuration issue or time synchronization problem.

**Solution:**

1. Clear cache: `php artisan cache:clear`
2. Verify system time is correct
3. Check cache driver configuration in `config/cache.php`

### Issue: Development testing hits rate limits quickly

**Solution:** Disable rate limiting in local environment by adding to `.env`:

```env
RATE_LIMITING_ENABLED=false
```

Then update `AppServiceProvider.php`:

```php
protected function configureRateLimiting(): void
{
    if (!config('app.rate_limiting_enabled', true)) {
        return;
    }

    // ... rest of rate limiter definitions
}
```

---

## Future Enhancements

### 1. Redis-Based Rate Limiting

For distributed/scaled environments, consider using Redis for rate limiting:

**Benefits:**

- Shared rate limit state across multiple servers
- Better performance for high-traffic applications
- More accurate rate limiting in load-balanced setups

**Implementation:**

```bash
# Install Redis driver
composer require predis/predis

# Update .env
CACHE_DRIVER=redis
```

### 2. Per-User Rate Limit Tiers

Implement different rate limits for different user roles:

```php
RateLimiter::for('api-authenticated', function (Request $request) {
    $user = $request->user();

    return match($user?->role) {
        'super_admin' => Limit::perMinute(1000),
        'admin' => Limit::perMinute(120),
        'user' => Limit::perMinute(60),
        default => Limit::perMinute(60)->by($request->ip()),
    };
});
```

### 3. Rate Limit Dashboard

Create an admin dashboard to monitor rate limit hits:

- Track which IPs are hitting limits most frequently
- Identify potential abuse patterns
- Whitelist/blacklist IP addresses
- Adjust limits dynamically based on traffic patterns

### 4. Custom Rate Limit Responses

Provide more user-friendly error messages with:

- Countdown timer until limit resets
- Upgrade prompts for higher limits (if applicable)
- Support contact information

---

## Security Considerations

1. **DDoS Protection:** Rate limiting provides basic protection but is not a complete DDoS solution. Consider using Cloudflare or AWS Shield for production.

2. **Bypass Prevention:** Rate limits use IP addresses for public endpoints. Advanced attackers can rotate IPs, but this makes attacks more expensive.

3. **Authentication Bypass:** Login rate limiting uses both email and IP to prevent credential stuffing attacks from distributed sources.

4. **Resource Exhaustion:** Import operations have high limits but still prevent a single user from exhausting server resources.

---

## Configuration Summary

| Endpoint                   | Limiter             | Limit                        | Key        | File                 |
| -------------------------- | ------------------- | ---------------------------- | ---------- | -------------------- |
| `GET /api/products`        | `api`               | 60/min                       | IP         | `routes/api.php:10`  |
| `GET /api/categories`      | `api`               | 60/min                       | IP         | `routes/api.php:12`  |
| `POST /api/contacts`       | `contacts`          | 3/min                        | IP         | `routes/api.php:16`  |
| `POST /login`              | `login`             | 5/min (email)<br>10/min (IP) | Email + IP | `routes/web.php:20`  |
| `/api/admin/*`             | `api-authenticated` | 120/min                      | User ID    | `routes/api.php:20`  |
| `/admin/products/import/*` | `product-imports`   | 600/min                      | User ID    | `routes/web.php:132` |

---

## Support

For questions or issues with rate limiting:

1. Check the logs in `storage/logs/laravel.log`
2. Review rate limit headers in API responses
3. Consult this documentation
4. Contact the development team

---

**Last Updated:** January 4, 2026
**Implemented By:** System Administrator
**Laravel Version:** 11.x
