<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Jobs\ImportProductsJob;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductController extends Controller
{
    /**
     * Store a newly created product in storage.
     * POST /admin/products
     * 
     * Supports multiple image uploads (1-10 images)
     * First image becomes primary automatically
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'stock' => 'required|integer|min:0',
            'specifications' => 'nullable|array',
            'specifications.processor' => 'nullable|string|max:255',
            'specifications.gpu' => 'nullable|string|max:255',
            'specifications.ram' => 'nullable|string|max:100',
            'specifications.storage' => 'nullable|string|max:255',
            'specifications.display' => 'nullable|string|max:255',
            'specifications.keyboard' => 'nullable|string|max:255',
            'specifications.battery' => 'nullable|string|max:255',
            'specifications.warranty' => 'nullable|string|max:255',
            'specifications.condition' => 'nullable|string|in:new,excellent,good,fair,used-excellent,used-very-good,used-good',
            'specifications.extras' => 'nullable|string|max:500',
            'specifications.original_price' => 'nullable|numeric|min:0',
            'specifications.features' => 'nullable|string|max:1000',
            // Hardware (additional)
            'specifications.chipset' => 'nullable|string|max:255',
            'specifications.optical_drive' => 'nullable|string|max:255',
            'specifications.wireless_connectivity' => 'nullable|string|max:255',
            // Ports & Expansion
            'specifications.expansion_slots' => 'nullable|string|max:500',
            'specifications.external_ports' => 'nullable|string|max:500',
            // Physical Specs
            'specifications.dimensions_width' => 'nullable|string|max:100',
            'specifications.dimensions_depth' => 'nullable|string|max:100',
            'specifications.dimensions_height' => 'nullable|string|max:100',
            'specifications.weight' => 'nullable|string|max:100',
            // Power
            'specifications.power_supply_type' => 'nullable|string|max:255',
            // Multimedia
            'specifications.webcam' => 'nullable|string|max:255',
            'specifications.audio' => 'nullable|string|max:255',
            // Software
            'specifications.operating_system' => 'nullable|string|max:255',
            'specifications.software_included' => 'nullable|string|max:500',
            // Product Info
            'specifications.product_number' => 'nullable|string|max:255',
        ]);

        $primaryImageUrl = null;
        $primaryImageThumbnailUrl = null;

        // Create the product first (without images)
        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'brand' => $validated['brand'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'sku' => $validated['sku'],
            'stock' => $validated['stock'],
            'specifications' => $validated['specifications'] ?? null,
            'image_url' => '', // Temporary, will be updated with primary image
            'image_thumbnail_url' => null,
        ]);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $images = $request->file('images');

            foreach ($images as $index => $image) {
                try {
                    $uploadedFile = Cloudinary::uploadApi()->upload($image->getRealPath(), [
                        'folder' => 'computer-store-products',
                        'transformation' => [
                            'width' => 1000,
                            'height' => 1000,
                            'crop' => 'limit',
                            'quality' => 'auto',
                            'fetch_format' => 'auto',
                        ],
                    ]);

                    $imageUrl = $uploadedFile['secure_url'];
                    $isPrimary = ($index === 0); // First image is primary

                    // Save to primary image URL for quick access
                    if ($isPrimary) {
                        $primaryImageUrl = $imageUrl;
                        $primaryImageThumbnailUrl = $imageUrl;
                    }

                    // Create product_image entry
                    $product->images()->create([
                        'image_url' => $imageUrl,
                        'image_thumbnail_url' => $imageUrl,
                        'sort_order' => $index,
                        'is_primary' => $isPrimary,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Admin: Failed to upload product image to Cloudinary: '.$e->getMessage(), [
                        'product_id' => $product->id,
                        'product_name' => $validated['name'],
                        'image_index' => $index,
                        'error' => $e->getMessage(),
                    ]);

                    // If any image fails, delete the product and all uploaded images
                    $product->images()->delete();
                    $product->delete();

                    return back()->withErrors([
                        'images' => 'Failed to upload one or more images. Please try again.',
                    ])->withInput();
                }
            }
        }

        // Update product with primary image URL for fast access (denormalization)
        $product->update([
            'image_url' => $primaryImageUrl,
            'image_thumbnail_url' => $primaryImageThumbnailUrl,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully with '.$product->images()->count().' images!');
    }

    /**
     * Update the specified product in storage.
     * PUT /admin/products/{product}
     * 
     * Supports adding new images (up to 10 total per product)
     * First new image becomes primary automatically
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,'.$product->id,
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'stock' => 'required|integer|min:0',
            'specifications' => 'nullable|array',
            'specifications.processor' => 'nullable|string|max:255',
            'specifications.gpu' => 'nullable|string|max:255',
            'specifications.ram' => 'nullable|string|max:100',
            'specifications.storage' => 'nullable|string|max:255',
            'specifications.display' => 'nullable|string|max:255',
            'specifications.keyboard' => 'nullable|string|max:255',
            'specifications.battery' => 'nullable|string|max:255',
            'specifications.warranty' => 'nullable|string|max:255',
            'specifications.condition' => 'nullable|string|in:new,excellent,good,fair,used-excellent,used-very-good,used-good',
            'specifications.extras' => 'nullable|string|max:500',
            'specifications.original_price' => 'nullable|numeric|min:0',
            'specifications.features' => 'nullable|string|max:1000',
            // Hardware (additional)
            'specifications.chipset' => 'nullable|string|max:255',
            'specifications.optical_drive' => 'nullable|string|max:255',
            'specifications.wireless_connectivity' => 'nullable|string|max:255',
            // Ports & Expansion
            'specifications.expansion_slots' => 'nullable|string|max:500',
            'specifications.external_ports' => 'nullable|string|max:500',
            // Physical Specs
            'specifications.dimensions_width' => 'nullable|string|max:100',
            'specifications.dimensions_depth' => 'nullable|string|max:100',
            'specifications.dimensions_height' => 'nullable|string|max:100',
            'specifications.weight' => 'nullable|string|max:100',
            // Power
            'specifications.power_supply_type' => 'nullable|string|max:255',
            // Multimedia
            'specifications.webcam' => 'nullable|string|max:255',
            'specifications.audio' => 'nullable|string|max:255',
            // Software
            'specifications.operating_system' => 'nullable|string|max:255',
            'specifications.software_included' => 'nullable|string|max:500',
            // Product Info
            'specifications.product_number' => 'nullable|string|max:255',
        ]);

        // Handle new image uploads if provided
        if ($request->hasFile('images')) {
            $currentImageCount = $product->images()->count();
            $newImagesCount = count($request->file('images'));

            // Check if total would exceed 10 images
            if ($currentImageCount + $newImagesCount > 10) {
                return back()->withErrors([
                    'images' => "Cannot exceed 10 images per product. You have {$currentImageCount} images and tried to add {$newImagesCount} more.",
                ])->withInput();
            }

            $images = $request->file('images');
            $nextSortOrder = $product->images()->max('sort_order') + 1;

            foreach ($images as $index => $image) {
                try {
                    $uploadedFile = Cloudinary::uploadApi()->upload($image->getRealPath(), [
                        'folder' => 'computer-store-products',
                        'transformation' => [
                            'width' => 1000,
                            'height' => 1000,
                            'crop' => 'limit',
                            'quality' => 'auto',
                            'fetch_format' => 'auto',
                        ],
                    ]);

                    $imageUrl = $uploadedFile['secure_url'];
                    $isPrimary = ($index === 0); // First new image becomes primary

                    // If setting as primary, unset current primary
                    if ($isPrimary) {
                        $product->images()->update(['is_primary' => false]);
                    }

                    // Create product_image entry
                    $product->images()->create([
                        'image_url' => $imageUrl,
                        'image_thumbnail_url' => $imageUrl,
                        'sort_order' => $nextSortOrder + $index,
                        'is_primary' => $isPrimary,
                    ]);

                    // Update products table with new primary image
                    if ($isPrimary) {
                        $validated['image_url'] = $imageUrl;
                        $validated['image_thumbnail_url'] = $imageUrl;
                    }
                } catch (\Exception $e) {
                    Log::error('Admin: Failed to upload product image to Cloudinary during update: '.$e->getMessage(), [
                        'product_id' => $product->id,
                        'product_name' => $validated['name'],
                        'image_index' => $index,
                        'error' => $e->getMessage(),
                    ]);

                    return back()->withErrors([
                        'images' => 'Failed to upload one or more images. Please try again.',
                    ])->withInput();
                }
            }
        }

        // Fallback: If no primary image exists, set first image as primary
        if (!isset($validated['image_url']) || empty($validated['image_url'])) {
            $firstImage = $product->images()->orderBy('sort_order')->first();
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
                $validated['image_url'] = $firstImage->image_url;
                $validated['image_thumbnail_url'] = $firstImage->image_thumbnail_url;
            }
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     * DELETE /admin/products/{product}
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Show import preview with validation
     * POST /admin/products/import/preview
     */
    public function importPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Max 10MB
            'allow_update' => 'nullable|boolean',
        ]);

        try {
            $file = $request->file('file');
            $allowUpdate = $request->boolean('allow_update');

            // Perform preview import (validation only, no database insert)
            $import = new ProductsImport(true, $allowUpdate); // Preview mode
            Excel::import($import, $file);

            $results = $import->getResults();

            return response()->json([
                'success' => true,
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            Log::error('Import preview failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process file: '.$e->getMessage(),
            ], 400);
        }
    }

    /**
     * Execute import in background
     * POST /admin/products/import/store
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Max 10MB
            'allow_update' => 'nullable|boolean',
        ]);

        try {
            $file = $request->file('file');
            $allowUpdate = $request->boolean('allow_update');

            // Generate unique job ID for tracking
            $jobId = Str::uuid()->toString();

            // Set status to processing
            Cache::put("import_job_{$jobId}_status", 'processing', now()->addHours(1));

            // Process import synchronously (Heroku doesn't share filesystem between dynos)
            // This avoids file not found errors when worker dyno tries to access the file
            $import = new ProductsImport(false, $allowUpdate);
            Excel::import($import, $file);

            // Get results
            $results = $import->getResults();

            // Store results in cache
            Cache::put("import_job_{$jobId}_results", $results, now()->addHours(1));
            Cache::put("import_job_{$jobId}_status", 'completed', now()->addHours(1));

            Log::info('Product import completed synchronously', [
                'job_id' => $jobId,
                'user_id' => Auth::id(),
                'summary' => $results['summary'],
            ]);

            return response()->json([
                'success' => true,
                'job_id' => $jobId,
                'message' => 'Import completed successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Store error in cache if job_id exists
            if (isset($jobId)) {
                Cache::put("import_job_{$jobId}_status", 'failed', now()->addHours(1));
                Cache::put("import_job_{$jobId}_error", $e->getMessage(), now()->addHours(1));
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to import: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check import job status
     * GET /admin/products/import/status/{jobId}
     */
    public function importStatus(string $jobId)
    {
        $status = Cache::get("import_job_{$jobId}_status", 'not_found');
        $results = Cache::get("import_job_{$jobId}_results");
        $error = Cache::get("import_job_{$jobId}_error");

        return response()->json([
            'status' => $status,
            'results' => $results,
            'error' => $error,
        ]);
    }

    /**
     * Download Excel template
     * GET /admin/products/import/template
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'name',
            'category',
            'brand',
            'description',
            'price',
            'sku',
            'stock',
            'processor',
            'gpu',
            'ram',
            'storage',
            'display',
            'keyboard',
            'battery',
            'warranty',
            'condition',
            'extras',
            'original_price',
            'features',
        ];

        $sheet->fromArray([$headers], null, 'A1');

        // Add example data
        $exampleData = [
            [
                'MacBook Pro M3 16" 2024',
                'Laptop',
                'Apple',
                'High performance laptop with M3 chip',
                35000000,
                'MBP-M3-16-001',
                5,
                'Apple M3 Max',
                'Integrated 40-core GPU',
                '48GB Unified Memory',
                '2TB SSD',
                '16.2" Liquid Retina XDR',
                'Magic Keyboard with Touch ID',
                'Up to 22 hours',
                '1 Year Apple Limited Warranty',
                'new',
                'USB-C charger, cleaning cloth',
                38000000,
                'ProMotion, HDR, P3 wide color',
            ],
            [
                'Dell XPS 15 9530',
                'Laptop',
                'Dell',
                'Premium ultrabook for professionals',
                28000000,
                'DELL-XPS15-001',
                3,
                'Intel Core i7-13700H',
                'NVIDIA RTX 4060 8GB',
                '32GB DDR5',
                '1TB NVMe SSD',
                '15.6" 3.5K OLED Touch',
                'Backlit keyboard',
                'Up to 13 hours',
                '1 Year Premium Support',
                'new',
                'Dell USB-C charger 130W',
                30000000,
                'Thunderbolt 4, Wi-Fi 6E, Killer AX1675',
            ],
        ];

        $sheet->fromArray($exampleData, null, 'A2');

        // Auto-size columns
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Style header row
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB'],
            ],
        ];
        $sheet->getStyle('A1:'.$sheet->getHighestColumn().'1')->applyFromArray($headerStyle);

        // Create writer and download
        $writer = new Xlsx($spreadsheet);
        $fileName = 'product_import_template_'.date('Y-m-d').'.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');

        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
