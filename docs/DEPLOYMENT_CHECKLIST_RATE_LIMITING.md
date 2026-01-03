# Production Deployment Checklist - Rate Limiting

Use this checklist before deploying rate limiting changes to production.

## Pre-Deployment

### 1. Code Review

- [x] Rate limiters configured in `AppServiceProvider.php`
- [x] Rate limiting middleware applied to all routes
- [x] Trusted proxy configuration in `bootstrap/app.php`
- [x] Frontend error handling implemented
- [x] All tests passing (13/13)

### 2. Configuration Verification

- [ ] `.env` has `APP_ENV=production`
- [ ] `.env` has `APP_DEBUG=false`
- [ ] `.env` has `APP_URL=https://...`
- [ ] `.env` has `SESSION_SECURE_COOKIE=true`
- [ ] `.env` has `CACHE_STORE=database` (or redis)

### 3. Test Suite

- [ ] Run full test suite: `php artisan test`
- [ ] Verify rate limit tests pass: `php artisan test --filter=RateLimitTest`
- [ ] All 13 rate limit tests passing

## Deployment Steps

### 1. Deploy Backend Changes

```bash
# Push to Heroku
git push heroku main

# OR if using a different branch
git push heroku your-branch:main

# Verify deployment succeeded
heroku logs --tail
```

### 2. Deploy Frontend Changes

```bash
# If using separate frontend deployment
npm run build
# Deploy built assets to your hosting
```

## Post-Deployment Verification

### 1. Verify Trusted Proxy Configuration

```bash
# Test the debug endpoint
curl https://api.computer-store.ihza.me/debug/ip

# Check the response:
# - "detected_ip" should be YOUR actual IP (not 10.x or 172.x)
# - "x_forwarded_for" should contain your real IP
# - "remote_addr" will be proxy IP (this is normal)
```

**Expected Response:**

```json
{
    "detected_ip": "203.0.113.45", // Your real IP
    "remote_addr": "10.0.0.1", // Proxy IP (normal)
    "x_forwarded_for": "203.0.113.45", // Your real IP
    "all_ips": ["203.0.113.45", "10.0.0.1"]
}
```

✅ If `detected_ip` matches your real IP → **Proxy config is correct**  
❌ If `detected_ip` shows 10.x or 172.x → **Proxy config FAILED - DO NOT PROCEED**

### 2. Test Public API Rate Limiting (60/min)

```bash
# Make 61 requests to test rate limit
for i in {1..61}; do
  echo "Request $i"
  curl -i https://api.computer-store.ihza.me/api/products | grep -E "HTTP|X-RateLimit"
done

# Expected:
# - Requests 1-60: HTTP 200, X-RateLimit-Remaining decreases
# - Request 61: HTTP 429, Retry-After header present
```

### 3. Test Contact Form Rate Limiting (3/min)

```bash
# Make 4 contact submissions
for i in {1..4}; do
  echo "Request $i"
  curl -X POST https://api.computer-store.ihza.me/api/contacts \
    -H "Content-Type: application/json" \
    -d '{"name":"Test","email":"test@example.com","category":"general","message":"Test message"}' \
    -i | grep -E "HTTP|X-RateLimit"
done

# Expected:
# - Requests 1-3: HTTP 201, X-RateLimit-Limit: 3
# - Request 4: HTTP 429, Custom error message
```

### 4. Test Login Rate Limiting (5/min per email)

```bash
# Make 6 login attempts
for i in {1..6}; do
  echo "Attempt $i"
  curl -X POST https://api.computer-store.ihza.me/login \
    -H "Content-Type: application/json" \
    -d '{"email":"test@example.com","password":"wrong"}' \
    -i | grep -E "HTTP"
done

# Expected:
# - Attempts 1-5: Processed (even if auth fails)
# - Attempt 6: Rate limited with error message
```

### 5. Verify Multi-User Independence

**Critical Test:** Ensure different users have independent rate limits.

```bash
# From Computer A (your IP):
# Make 60 requests
for i in {1..60}; do
  curl -s https://api.computer-store.ihza.me/api/products > /dev/null
done

# From Computer B (different IP, e.g., mobile hotspot):
# This should succeed (not be rate limited)
curl https://api.computer-store.ihza.me/api/products

# Expected: HTTP 200 (not 429)
```

✅ If Computer B gets 200 → **Rate limiting is per-user (CORRECT)**  
❌ If Computer B gets 429 → **All users share limit (CRITICAL BUG)**

### 6. Test Frontend Error Handling

1. Open browser console
2. Make multiple rapid contact form submissions
3. Verify:
    - Toast notification appears on 4th submission
    - Error message includes retry time
    - Form button is disabled during cooldown
    - No console errors

### 7. Monitor Production Logs

```bash
# Watch for rate limit events
heroku logs --tail | grep -i "rate\|429"

# Look for:
# - Rate limit hits (expected occasionally)
# - Errors in IP detection (should be none)
# - Suspicious patterns (many 429s from same IP)
```

## Post-Deployment Cleanup

### 1. Remove Debug Route

**IMPORTANT:** Remove the debug endpoint from production:

```php
// routes/web.php
// DELETE THIS ROUTE:
Route::get('/debug/ip', function (Request $request) { ... });
```

Commit and redeploy:

```bash
git add routes/web.php
git commit -m "Remove debug IP endpoint from production"
git push heroku main
```

### 2. Verify Debug Route is Gone

```bash
curl https://api.computer-store.ihza.me/debug/ip
# Expected: 404 Not Found
```

## Monitoring (Ongoing)

### Daily Checks (First Week)

- [ ] Check error logs for rate limit issues
- [ ] Monitor user complaints about access issues
- [ ] Review rate limit hit patterns

### Weekly Checks (Ongoing)

- [ ] Review rate limit metrics
- [ ] Identify if limits need adjustment
- [ ] Check for abuse patterns

## Rollback Plan

If rate limiting causes issues:

### Option 1: Disable Rate Limiting Temporarily

```php
// app/Providers/AppServiceProvider.php
protected function configureRateLimiting(): void
{
    // Temporarily disable by returning early
    return;

    // ... rest of rate limiter configuration
}
```

### Option 2: Increase Limits

```php
// Increase limits if they're too strict
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(300)->by($request->ip()); // Increased from 60
});
```

### Option 3: Full Rollback

```bash
# Revert to previous commit
git revert HEAD
git push heroku main
```

## Success Criteria

All of the following must be true:

- ✅ Trusted proxy configuration correctly detects user IPs
- ✅ Rate limits apply per-user, not globally
- ✅ Frontend shows friendly error messages
- ✅ Contact form blocks after 3 requests
- ✅ Login blocks after 5 attempts per email
- ✅ No user complaints about false rate limiting
- ✅ All tests passing
- ✅ Production logs show no proxy-related errors

## Troubleshooting

### Issue: All users blocked after 60 requests

**Cause:** Trusted proxy configuration not working

**Fix:**

1. Check `/debug/ip` - if `detected_ip` shows proxy IP, proxy config failed
2. Verify `bootstrap/app.php` has `trustProxies()` call
3. Check Heroku is setting `X-Forwarded-For` header
4. Review `docs/TRUSTED_PROXIES.md`

### Issue: Rate limit not working at all

**Cause:** Cache driver not configured

**Fix:**

1. Check `CACHE_STORE` in `.env`
2. Run `php artisan cache:clear`
3. Verify database cache table exists

### Issue: Frontend doesn't show error messages

**Cause:** Toast handler not configured

**Fix:**

1. Verify `setRateLimitToastHandler()` is called in `app.tsx`
2. Check browser console for errors
3. Review `docs/FRONTEND_RATE_LIMITING.md`

## Related Documentation

- [`docs/RATE_LIMITING.md`](./RATE_LIMITING.md) - Complete rate limiting guide
- [`docs/TRUSTED_PROXIES.md`](./TRUSTED_PROXIES.md) - Proxy configuration (CRITICAL)
- [`docs/FRONTEND_RATE_LIMITING.md`](./FRONTEND_RATE_LIMITING.md) - Frontend implementation
- [`tests/Feature/RateLimitTest.php`](../tests/Feature/RateLimitTest.php) - Test suite

## Contact

If issues arise during deployment, refer to the documentation above or check:

- Application logs: `heroku logs --tail`
- Error monitoring (if configured)
- Test suite results

---

**Last Updated:** January 4, 2026  
**Deployment Target:** Heroku (with Cloudflare optional)  
**Critical Dependencies:** Trusted proxy configuration
