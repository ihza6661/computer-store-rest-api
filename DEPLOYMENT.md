# Deployment Guide - R-Tech Computer Backend

## Heroku Deployment

### Required Environment Variables

Configure the following environment variables in Heroku:

```bash
# Application Settings
APP_NAME="R-Tech Computer"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://r-tech-computer-api-6fc0370b86dc.herokuapp.com

# Database (automatically set by Heroku Postgres addon)
# DATABASE_URL is automatically set by Heroku
# Laravel will parse this automatically

# Session Configuration (IMPORTANT for authentication)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true  # Required for HTTPS
# SESSION_SAME_SITE=lax  # Use 'none' only if cross-domain is needed

# Queue and Cache
QUEUE_CONNECTION=database
CACHE_STORE=database

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
MAIL_FROM_ADDRESS="noreply@rtech.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Setting Environment Variables on Heroku

#### Via Heroku CLI:

```bash
# Set APP_URL
heroku config:set APP_URL=https://r-tech-computer-api-6fc0370b86dc.herokuapp.com

# Set session configuration for production
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

1. Go to your app's dashboard: https://dashboard.heroku.com/apps/r-tech-computer-api
2. Navigate to **Settings** tab
3. Click **Reveal Config Vars**
4. Add/update the variables listed above

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
- `https://r-tech-pontianak-landing.vercel.app` (production landing)
- `https://r-tech-pontianak-landing-*.vercel.app` (Vercel preview deployments)
- **APP_URL** (the Heroku backend itself for Inertia admin panel)

### Database Migrations

Migrations run automatically on deployment via `Procfile`:
```
release: php artisan migrate --force
```

### Deployment Checklist

Before deploying to Heroku:

- [ ] Set all required environment variables
- [ ] Ensure `APP_KEY` is generated (Heroku should auto-generate)
- [ ] Verify `APP_URL` matches your Heroku app URL
- [ ] Set `SESSION_SECURE_COOKIE=true` for production
- [ ] Configure Cloudinary credentials
- [ ] Configure SendGrid API key (if using email)
- [ ] Run database migrations (automatic via Procfile)
- [ ] Seed admin users if needed: `heroku run php artisan db:seed --class=AdminSeeder`

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

### Testing Production Deployment

1. **Login Test:**
   ```
   Visit: https://r-tech-computer-api-6fc0370b86dc.herokuapp.com/login
   Login with: admin@rtech.test / password
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
   - Look for `r-tech-computer-session` cookie
   - Verify it has: `Secure=true`, `SameSite=Lax`

### Support

For deployment issues:
- Check Heroku logs: `heroku logs --tail`
- Review Laravel logs: `heroku run tail storage/logs/laravel.log`
- Check database connection: `heroku run php artisan tinker`
