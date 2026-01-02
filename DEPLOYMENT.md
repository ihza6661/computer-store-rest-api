# Deployment Guide - Computer Store Backend

## Production URLs

- **API & Admin Panel**: https://api.computer-store.ihza.me
- **Frontend**: https://computer-store.ihza.me
- **Legacy Heroku URL**: https://computer-store-api-dd14765dc7ef.herokuapp.com (redirects to custom domain)

## Heroku Deployment

### Required Environment Variables

Configure the following environment variables in Heroku:

```bash
# Application Settings
APP_NAME="Computer Store"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.computer-store.ihza.me

# Database (automatically set by Heroku Postgres addon)
# DATABASE_URL is automatically set by Heroku
# Laravel will parse this automatically

# Session Configuration (IMPORTANT for authentication)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.ihza.me  # Cross-subdomain cookie sharing
SESSION_SECURE_COOKIE=true  # Required for HTTPS
SESSION_SAME_SITE=lax  # Default, secure for same-site requests

# Queue and Cache
QUEUE_CONNECTION=database
CACHE_STORE=database

# IMPORTANT: Queue Worker Configuration
# The Product Import feature uses background jobs that require a queue worker.
# See "Queue Worker Setup" section below for configuration details.

# Cloudinary (for image uploads)
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
CLOUDINARY_URL=cloudinary://api_key:api_secret@cloud_name

# Mail (SendGrid)
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.your_sendgrid_api_key
MAIL_FROM_ADDRESS="noreply@store.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Setting Environment Variables on Heroku

#### Via Heroku CLI:

```bash
# Set custom domain URL
heroku config:set APP_URL=https://api.computer-store.ihza.me

# Set session configuration for custom domain
heroku config:set SESSION_DOMAIN=.ihza.me
heroku config:set SESSION_SECURE_COOKIE=true

# Set other required variables
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set SESSION_DRIVER=database
heroku config:set QUEUE_CONNECTION=database
heroku config:set CACHE_STORE=database

# Set Cloudinary credentials
heroku config:set CLOUDINARY_CLOUD_NAME=your_cloud_name
heroku config:set CLOUDINARY_API_KEY=your_api_key
heroku config:set CLOUDINARY_API_SECRET=your_api_secret
heroku config:set CLOUDINARY_URL=cloudinary://api_key:api_secret@cloud_name
```

#### Via Heroku Dashboard:

1. Go to your app's dashboard: https://dashboard.heroku.com/apps/computer-store-api
2. Navigate to **Settings** tab
3. Click **Reveal Config Vars**
4. Add/update the variables listed above

### SSL Certificate Management (Heroku ACM)

Heroku's Automatic Certificate Management (ACM) is enabled for the custom domain to provide free SSL certificates via Let's Encrypt.

#### ACM Status Check

```bash
# Check ACM status and certificate details
heroku certs:auto

# View domain and SSL endpoint information
heroku domains

# Get specific domain information
heroku domains:info api.computer-store.ihza.me
```

#### Certificate Details

- **Provider**: Let's Encrypt (via Heroku ACM)
- **Type**: SNI-based SSL/TLS
- **Renewal**: Automatic (every 90 days)
- **Cost**: Free (included with Heroku)
- **Issuer**: Let's Encrypt R13
- **Validity**: 90 days (auto-renewed at 60 days)

#### Current Certificate Info

```
Common Name: api.computer-store.ihza.me
SNI Endpoint: brachiosaurus-39937
Status: Active
Renewal: Automatic
Expires: 2026-04-02 (auto-renews)
```

#### ACM Management Commands

```bash
# Enable ACM (already enabled)
heroku certs:auto:enable

# Check ACM status
heroku certs:auto

# Wait for certificate provisioning (if needed)
heroku certs:auto --wait

# Disable ACM (not recommended)
heroku certs:auto:disable
```

#### SSL Verification

**Test SSL connection:**
```bash
# Quick test
curl -I https://api.computer-store.ihza.me

# Detailed certificate info
openssl s_client -connect api.computer-store.ihza.me:443 -servername api.computer-store.ihza.me
```

**Expected response:**
- HTTP/2 or HTTP/1.1 200 OK
- No SSL certificate errors
- Valid Let's Encrypt certificate

#### Troubleshooting SSL Issues

**Issue: SSL certificate not provisioning**

Check domain DNS:
```bash
heroku domains:info api.computer-store.ihza.me
```

Ensure DNS status shows `succeeded` and CNAME points to Heroku DNS target.

**Issue: Certificate expired or renewal failed**

ACM auto-renews, but if manual intervention is needed:
```bash
# Disable and re-enable ACM
heroku certs:auto:disable
heroku certs:auto:enable
```

**Issue: Browser shows SSL error**

1. Wait 5-10 minutes for global propagation
2. Clear browser cache and SSL state
3. Try incognito/private browsing mode
4. Verify certificate is valid via command line

### Important Notes

#### Session Cookies in Production:

- **SESSION_SECURE_COOKIE=true** is REQUIRED for HTTPS (Heroku uses HTTPS)
- **SESSION_SAME_SITE=lax** (default) works for same-site requests
- If you need cross-domain cookies, set **SESSION_SAME_SITE=none** AND **SESSION_SECURE_COOKIE=true**

#### Authentication Issue Fix:

The recent fix ensures:

- ✅ Session cookies are automatically secured in production
- ✅ CORS allows credentials from the app's own URL
- ✅ API fetch calls include credentials (cookies) for authentication

#### CORS Configuration:

The app automatically allows requests from:

- `http://localhost:5173` (local dev - Vite)
- `http://localhost:3000` (local dev - alternative)
- `http://localhost:8000` (local dev - Laravel)
- `https://computer-store.ihza.me` (production frontend)
- `https://computer-store-pontianak-landing.vercel.app` (legacy Vercel URL)
- `https://computer-store-pontianak-landing-*.vercel.app` (Vercel preview deployments)

### Database Migrations

Migrations run automatically on deployment via `Procfile`:

```
release: php artisan migrate --force
```

### Queue Worker Setup (Required for Product Import Feature)

The Product Import feature uses Laravel's queue system to process Excel imports in the background. Without a queue worker running, import jobs will remain stuck in "processing" state.

#### Option 1: Heroku Worker Dyno (Recommended for Production)

**Add a worker dyno to your Procfile:**

```
web: vendor/bin/heroku-php-apache2 public/
release: php artisan migrate --force
worker: php artisan queue:work --tries=3 --timeout=300
```

**Enable the worker dyno:**

```bash
# Scale up worker dyno
heroku ps:scale worker=1

# Check dyno status
heroku ps

# View worker logs
heroku logs --tail --dyno=worker
```

**Cost Consideration:**

- Basic worker dyno: $7/month (25 Eco hours free per month)
- For low-volume imports, consider Option 2 or 3

#### Option 2: Heroku Scheduler (Free Alternative)

For low-frequency imports (e.g., weekly/daily), use Heroku Scheduler addon:

**Install Scheduler:**

```bash
heroku addons:create scheduler:standard
```

**Add scheduled job (via Heroku Dashboard):**

- Command: `php artisan queue:work --stop-when-empty --tries=3`
- Frequency: Every 10 minutes (or as needed)

**Pros:**

- Free
- No permanent worker dyno needed

**Cons:**

- Jobs may be delayed up to 10 minutes
- Not suitable for real-time processing

#### Option 3: Local Development Queue Worker

For local development, run the queue worker in a separate terminal:

**Using Laravel's dev script (includes queue worker):**

```bash
composer run dev
```

This runs:

- Laravel server on port 8000
- Queue worker with auto-reload on code changes

**Or manually:**

```bash
php artisan queue:work --tries=3
```

#### Queue Configuration Best Practices

**Timeout Settings:**

- Default: 60 seconds
- Recommended for imports: 300 seconds (5 minutes)
- Large files may need 600 seconds (10 minutes)

**Retry Settings:**

- Tries: 3 attempts before marking as failed
- Delay: Exponential backoff (1s, 5s, 15s)

**Monitor Queue Health:**

```bash
# Check pending jobs
heroku run php artisan queue:monitor

# Failed jobs list
heroku run php artisan queue:failed

# Retry all failed jobs
heroku run php artisan queue:retry all

# Clear failed jobs
heroku run php artisan queue:flush
```

#### Troubleshooting Queue Issues

**Problem: Import stuck at "processing"**

**Symptoms:**

- Import status shows "processing" indefinitely
- No products created after import

**Solutions:**

1. Check if worker dyno is running: `heroku ps`
2. Check worker logs: `heroku logs --tail --dyno=worker`
3. Verify `QUEUE_CONNECTION=database` is set
4. Check jobs table: `heroku run php artisan tinker` → `DB::table('jobs')->count()`

**Problem: Worker dyno crashes**

**Symptoms:**

- Worker dyno shows "crashed" state
- Logs show memory errors

**Solutions:**

1. Increase timeout: Update `worker:` command to `--timeout=600`
2. Reduce batch size in import logic
3. Upgrade to Standard 1X dyno (512MB RAM)

**Problem: Jobs fail with "Maximum execution time exceeded"**

**Solutions:**

1. Increase `--timeout` value in worker command
2. Add `set_time_limit(300)` in ImportProductsJob
3. Process smaller batches

#### Queue Monitoring Dashboard

For production, consider installing Laravel Horizon (optional):

```bash
composer require laravel/horizon
php artisan horizon:install
```

Horizon provides:

- Real-time queue monitoring
- Failed job management
- Metrics and analytics

**Access Horizon dashboard:**
`https://your-app.herokuapp.com/horizon`

_Note: Requires authentication middleware_

### Deployment Checklist

Before deploying to Heroku:

- [ ] Set all required environment variables
- [ ] Ensure `APP_KEY` is generated (Heroku should auto-generate)
- [ ] Verify `APP_URL` matches your custom domain (`https://api.computer-store.ihza.me`)
- [ ] Set `SESSION_DOMAIN=.ihza.me` for cross-subdomain cookies
- [ ] Set `SESSION_SECURE_COOKIE=true` for production
- [ ] Set `QUEUE_CONNECTION=database` for background jobs
- [ ] Configure Cloudinary credentials
- [ ] Configure SendGrid API key (if using email)
- [ ] **Add custom domain to Heroku** (if not already added)
    - [ ] Add domain: `heroku domains:add api.computer-store.ihza.me`
    - [ ] Configure DNS CNAME record in Vercel DNS
- [ ] **Enable SSL Certificate Management**
    - [ ] Enable ACM: `heroku certs:auto:enable`
    - [ ] Verify certificate: `heroku certs:auto`
    - [ ] Check SNI endpoint: `heroku domains`
- [ ] Run database migrations (automatic via Procfile)
- [ ] Seed admin users if needed: `heroku run php artisan db:seed --class=AdminSeeder`
- [ ] **Set up queue worker (required for product import feature)**
    - [ ] Add `worker:` line to Procfile
    - [ ] Scale worker dyno: `heroku ps:scale worker=1`
    - [ ] Or configure Heroku Scheduler for periodic processing
- [ ] Test production deployment
    - [ ] Visit `https://api.computer-store.ihza.me/` (should load without SSL errors)
    - [ ] Test admin login and session persistence
    - [ ] Test product import feature
    - [ ] Verify frontend-backend communication

### Deployment Commands

```bash
# Deploy to Heroku
git push heroku master

# Run artisan commands
heroku run php artisan migrate --force
heroku run php artisan db:seed --class=AdminSeeder
heroku run php artisan cache:clear
heroku run php artisan config:clear

# View logs
heroku logs --tail

# Check environment variables
heroku config
```

### Troubleshooting

#### 401 Unauthorized on Admin Operations:

**Symptoms:**

- Can login but cannot delete/update products
- API returns 401 Unauthorized
- Works locally but not in production

**Solutions:**

1. Ensure `SESSION_SECURE_COOKIE=true` is set in Heroku config
2. Verify `APP_URL` is set to your Heroku URL
3. Check browser cookies are being saved (DevTools → Application → Cookies)
4. Ensure cookies are marked as `Secure` and `SameSite=Lax`

#### Session Not Persisting:

**Symptoms:**

- Logged out after navigation
- Session appears to reset

**Solutions:**

1. Verify `SESSION_DRIVER=database` is set
2. Ensure `sessions` table exists (run migrations)
3. Check `APP_KEY` is set in Heroku config
4. Verify `SESSION_DOMAIN` is null or correctly set

#### CORS Errors:

**Symptoms:**

- Browser console shows CORS policy errors
- Preflight requests failing

**Solutions:**

1. Verify your frontend URL is in `CorsMiddleware.php` allowed origins
2. Check `APP_URL` is correctly set in Heroku
3. Ensure credentials are included in fetch requests (`credentials: 'include'`)

#### SSL Certificate Errors:

**Symptoms:**

- Browser shows "Your connection is not private" or SSL warning
- Error: `ERR_SSL_UNRECOGNIZED_NAME_ALERT`
- curl shows SSL/TLS errors

**Solutions:**

1. **Verify ACM is enabled:**
   ```bash
   heroku certs:auto
   ```
   Should show certificate details and "Cert issued" status

2. **Check domain DNS configuration:**
   ```bash
   heroku domains:info api.computer-store.ihza.me
   ```
   Ensure status is "succeeded" and CNAME points to Heroku DNS target

3. **Check SNI endpoint exists:**
   ```bash
   heroku domains
   ```
   SNI Endpoint column should show endpoint name (not "undefined")

4. **If certificate not provisioning:**
   ```bash
   # Disable and re-enable ACM
   heroku certs:auto:disable
   heroku certs:auto:enable
   
   # Wait for provisioning (5-15 minutes)
   heroku certs:auto --wait
   ```

5. **If domain not responding:**
   - Wait 5-30 minutes for DNS propagation
   - Clear browser cache and SSL state
   - Test in incognito/private mode
   - Verify DNS: `curl -I https://api.computer-store.ihza.me`

6. **Verify certificate is valid:**
   ```bash
   openssl s_client -connect api.computer-store.ihza.me:443 -servername api.computer-store.ihza.me < /dev/null
   ```
   Should show Let's Encrypt certificate with your domain in Subject Alternative Name (SAN)

**Common Issues:**

- **ACM disabled**: Enable with `heroku certs:auto:enable`
- **DNS not configured**: Add CNAME record in DNS provider pointing to Heroku DNS target
- **Certificate provisioning in progress**: Wait 5-15 minutes for Let's Encrypt to issue certificate
- **Browser cache**: Clear SSL state and try incognito mode

### Testing Production Deployment

1. **Login Test:**

    ```
    Visit: https://api.computer-store.ihza.me/login
    Login with: admin@store.test / password
    ```

2. **Session Test:**
    - Navigate to different admin pages
    - Verify you stay logged in

3. **CRUD Operations Test:**
    - Try creating a category
    - Try deleting a product
    - Verify operations succeed (no 401 errors)

4. **Cookie Verification:**
    - Open DevTools → Application → Cookies
    - Look for `laravel-session` cookie
    - Verify it has: `Domain=.ihza.me`, `Secure=true`, `SameSite=Lax`

5. **SSL Certificate Test:**
    - Check SSL padlock in browser address bar
    - Click padlock → View certificate
    - Verify:
      - Issuer: Let's Encrypt R13
      - Valid for: api.computer-store.ihza.me
      - Expiration date in the future
    - Or test via command line:
      ```bash
      curl -I https://api.computer-store.ihza.me
      # Should return HTTP/2 200 with no SSL errors
      ```

6. **API Endpoint Test:**
    ```bash
    # Test products API
    curl https://api.computer-store.ihza.me/api/products
    
    # Should return JSON with product data
    ```

7. **Frontend Integration Test:**
    - Visit https://computer-store.ihza.me
    - Verify products load from API
    - Check browser console for CORS errors (should be none)
    - Test navigation and user interactions

### Support

For deployment issues:

- Check Heroku logs: `heroku logs --tail`
- Review Laravel logs: `heroku run tail storage/logs/laravel.log`
- Check database connection: `heroku run php artisan tinker`
