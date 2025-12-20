# Image Migration to Cloudinary

This document explains how to use the image migration command to move product images from local storage to Cloudinary.

## Overview

The `images:migrate-to-cloudinary` command automatically uploads product images stored locally to Cloudinary CDN and updates the database with new URLs.

## Usage

### Basic Command

```bash
php artisan images:migrate-to-cloudinary
```

### Options

- `--dry-run` : Preview which products will be migrated without making any changes
- `--force` : Skip confirmation prompt (useful for automation)

### Examples

#### 1. Dry Run (Preview Only)

```bash
php artisan images:migrate-to-cloudinary --dry-run
```

This will show you which products have local storage images without migrating them.

#### 2. Interactive Migration

```bash
php artisan images:migrate-to-cloudinary
```

This will display the products to be migrated and ask for confirmation.

#### 3. Automated Migration

```bash
php artisan images:migrate-to-cloudinary --force
```

This will migrate all products without asking for confirmation (useful for scripts).

## How It Works

1. **Searches for products** with local storage URLs:
   - URLs containing `/storage/`
   - URLs containing `127.0.0.1`
   - URLs containing `localhost`

2. **Validates each image**:
   - Checks if the file exists in `storage/app/public/`
   - Skips missing files

3. **Uploads to Cloudinary**:
   - Uploads to `r-tech-products` folder
   - Applies transformations (1000x1000, limit crop, auto quality)
   - Uses HTTPS secure URLs

4. **Updates database**:
   - Sets `image_url` to Cloudinary secure URL
   - Sets `image_thumbnail_url` to same URL
   - Logs all changes

5. **Provides summary**:
   - Shows successful, failed, and skipped counts
   - Logs errors for debugging

## Output Example

```
🔍 Searching for products with local storage images...

Found 2 product(s) with local storage images:

+----+---------------+-------------------------------------------------------+
| ID | Name          | Current URL                                           |
+----+---------------+-------------------------------------------------------+
| 16 | Laptop Gaming | http://127.0.0.1:8000/storage/products/MVH9WXb4sjr... |
| 17 | Laptop Baru   | http://127.0.0.1:8000/storage/products/ITASxgpCg4Y... |
+----+---------------+-------------------------------------------------------+

🚀 Starting migration...
 2/2 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

📊 Migration Summary:
+---------------+-------+
| Status        | Count |
+---------------+-------+
| ✅ Successful | 2     |
| ❌ Failed     | 0     |
| ⚠️  Skipped    | 0     |
| 📋 Total      | 2     |
+---------------+-------+
```

## When to Use

### Scenarios

1. **After importing old data** with local image paths
2. **When switching from local to Cloudinary** storage
3. **Before deploying** to ensure all images are on CDN
4. **Periodic cleanup** to migrate any missed images

### Best Practices

1. **Run dry-run first** to preview changes:
   ```bash
   php artisan images:migrate-to-cloudinary --dry-run
   ```

2. **Backup your database** before running (optional but recommended):
   ```bash
   php artisan db:backup # if you have a backup command
   ```

3. **Check logs** after migration:
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Verify images** on the landing page after migration

## Troubleshooting

### Issue: "File not found"

**Cause**: Image file doesn't exist in local storage

**Solution**: Check if the file exists:
```bash
ls -la storage/app/public/products/
```

### Issue: "Failed to upload to Cloudinary"

**Cause**: Cloudinary API error or invalid credentials

**Solution**: 
1. Check Cloudinary credentials in `.env`:
   ```
   CLOUDINARY_CLOUD_NAME=your_cloud_name
   CLOUDINARY_API_KEY=your_api_key
   CLOUDINARY_API_SECRET=your_api_secret
   ```

2. Test Cloudinary connection:
   ```bash
   php artisan tinker
   >>> app(\Cloudinary\Cloudinary::class)->configuration->cloud->cloudName
   ```

### Issue: Images still not showing on landing page

**Cause**: Cache or API not returning updated URLs

**Solution**:
1. Clear Laravel cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

2. Verify API response:
   ```bash
   curl http://localhost:8000/api/products?per_page=4
   ```

3. Check browser console for errors

## Technical Details

### Files Modified

- **Command**: `app/Console/Commands/MigrateImagesToCloudinary.php`
- **Database**: `products` table (`image_url`, `image_thumbnail_url` columns)
- **Storage**: `storage/app/public/products/` (source files)

### Cloudinary Settings

- **Folder**: `r-tech-products`
- **Max Dimensions**: 1000x1000 pixels
- **Crop Mode**: limit (maintains aspect ratio)
- **Quality**: auto
- **Format**: auto (optimized for web)

### Database Changes

The command updates two columns in the `products` table:
- `image_url`: Set to Cloudinary secure HTTPS URL
- `image_thumbnail_url`: Set to same Cloudinary URL

## Logs

All migration activities are logged to `storage/logs/laravel.log`:

- **Success**: Logs product ID, name, old URL, and new URL
- **Errors**: Logs product ID, name, and error message

Example log entry:
```
[2025-12-20 18:05:00] local.INFO: Product image migrated to Cloudinary
{
  "product_id": 17,
  "product_name": "Laptop Baru",
  "old_url": "http://127.0.0.1:8000/storage/products/ITASxgpCg4Y...",
  "new_url": "https://res.cloudinary.com/drcy0kzdm/image/upload/v1766253874/r-tech-products/imama3vxieeu0xtrindg.jpg"
}
```

## Future Enhancements

Potential improvements for this command:

1. Add option to delete local files after successful migration
2. Support batch size limits for large datasets
3. Add retry logic for failed uploads
4. Generate actual thumbnails (different sizes)
5. Support migration from other storage disks

## Support

If you encounter issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Run with verbose output: Add `--verbose` flag (if implemented)
3. Test Cloudinary connection manually
4. Verify file permissions on storage directory

---

**Last Updated**: December 20, 2025
**Command Version**: 1.0.0
