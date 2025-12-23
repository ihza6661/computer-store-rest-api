<?php

namespace App\Console\Commands;

use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MigrateImagesToCloudinary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:migrate-to-cloudinary {--dry-run : Run without making changes} {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate product images from local storage to Cloudinary';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('🔍 Searching for products with local storage images...');
        $this->newLine();

        // Find products with local storage URLs
        $products = Product::where(function ($query) {
            $query->where('image_url', 'like', '%/storage/%')
                ->orWhere('image_url', 'like', '%127.0.0.1%')
                ->orWhere('image_url', 'like', '%localhost%');
        })->get();

        if ($products->isEmpty()) {
            $this->info('✅ No products found with local storage images.');

            return Command::SUCCESS;
        }

        $this->info("Found {$products->count()} product(s) with local storage images:");
        $this->newLine();

        // Display products
        $tableData = [];
        foreach ($products as $product) {
            $tableData[] = [
                'ID' => $product->id,
                'Name' => substr($product->name, 0, 30),
                'Current URL' => substr($product->image_url, 0, 50).'...',
            ];
        }
        $this->table(['ID', 'Name', 'Current URL'], $tableData);
        $this->newLine();

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be made');
            $this->newLine();
        }

        // Confirmation
        if (! $force && ! $isDryRun) {
            if (! $this->confirm('Do you want to migrate these images to Cloudinary?', true)) {
                $this->warn('Migration cancelled.');

                return Command::SUCCESS;
            }
        }

        $successCount = 0;
        $failedCount = 0;
        $skippedCount = 0;

        $this->newLine();
        $this->info('🚀 Starting migration...');
        $this->newLine();

        $progressBar = $this->output->createProgressBar($products->count());
        $progressBar->start();

        foreach ($products as $product) {
            try {
                // Extract filename from URL
                $imagePath = $this->extractLocalPath($product->image_url);

                if (! $imagePath) {
                    $this->newLine();
                    $this->warn("⚠️  Could not extract path from URL: {$product->image_url}");
                    $skippedCount++;
                    $progressBar->advance();

                    continue;
                }

                // Check if file exists
                if (! Storage::disk('public')->exists($imagePath)) {
                    $this->newLine();
                    $this->warn("⚠️  File not found: {$imagePath}");
                    $skippedCount++;
                    $progressBar->advance();

                    continue;
                }

                if (! $isDryRun) {
                    // Get full file path
                    $fullPath = Storage::disk('public')->path($imagePath);

                    // Upload to Cloudinary
                    $uploadedFile = Cloudinary::uploadApi()->upload($fullPath, [
                        'folder' => 'r-tech-products',
                        'transformation' => [
                            'width' => 1000,
                            'height' => 1000,
                            'crop' => 'limit',
                            'quality' => 'auto',
                            'fetch_format' => 'auto',
                        ],
                    ]);

                    // Update product with new URLs
                    $product->update([
                        'image_url' => $uploadedFile['secure_url'],
                        'image_thumbnail_url' => $uploadedFile['secure_url'],
                    ]);

                    Log::info('Product image migrated to Cloudinary', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'old_url' => $product->image_url,
                        'new_url' => $uploadedFile['secure_url'],
                    ]);
                }

                $successCount++;
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("❌ Failed to migrate product #{$product->id}: {$e->getMessage()}");

                Log::error('Failed to migrate product image to Cloudinary', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'error' => $e->getMessage(),
                ]);

                $failedCount++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('📊 Migration Summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['✅ Successful', $successCount],
                ['❌ Failed', $failedCount],
                ['⚠️  Skipped', $skippedCount],
                ['📋 Total', $products->count()],
            ]
        );

        if ($isDryRun) {
            $this->newLine();
            $this->info('ℹ️  This was a dry run. Run without --dry-run to apply changes.');
        }

        return $successCount > 0 && $failedCount === 0
            ? Command::SUCCESS
            : Command::FAILURE;
    }

    /**
     * Extract local storage path from URL
     */
    private function extractLocalPath(string $url): ?string
    {
        // Handle URLs like: http://127.0.0.1:8000/storage/products/filename.jpg
        // Extract: products/filename.jpg

        if (preg_match('/\/storage\/(.+)$/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
