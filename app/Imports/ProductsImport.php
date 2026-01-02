<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToCollection, WithHeadingRow
{
    protected $results = [];

    protected $preview = false;

    protected $allowUpdate = false;

    public function __construct(bool $preview = false, bool $allowUpdate = false)
    {
        $this->preview = $preview;
        $this->allowUpdate = $allowUpdate;
        
        // Debug logging to verify boolean conversion
        Log::debug('ProductsImport initialized', [
            'preview' => $preview,
            'allowUpdate' => $allowUpdate,
            'preview_type' => gettype($preview),
            'allowUpdate_type' => gettype($allowUpdate),
        ]);
    }

    /**
     * Process each row in the Excel file
     */
    public function collection(Collection $rows)
    {
        // Phase 2: Check for duplicate SKUs within the uploaded file
        $duplicates = $this->findDuplicateSKUs($rows);
        
        if (!empty($duplicates)) {
            // Reject entire import if duplicates found within file
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;
                $sku = $row['sku'] ?? null;
                
                if ($sku && isset($duplicates[$sku])) {
                    $this->results[] = [
                        'row' => $rowNumber,
                        'status' => 'error',
                        'message' => "Duplicate SKU '{$sku}' found in file at rows: " . implode(', ', $duplicates[$sku]),
                        'data' => $row->toArray(),
                    ];
                } else {
                    $this->results[] = [
                        'row' => $rowNumber,
                        'status' => 'error',
                        'message' => 'Import rejected due to duplicate SKUs in file',
                        'data' => $row->toArray(),
                    ];
                }
            }
            
            return; // Stop processing
        }

        // Phase 2: Bulk check for existing SKUs in database
        $allSKUs = $rows->pluck('sku')->filter()->map(fn($sku) => trim($sku))->toArray();
        $existingProducts = Product::whereIn('sku', $allSKUs)->get()->keyBy('sku');

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because: 1 for heading, 1 for 0-based index

            try {
                // Normalize SKU early to prevent whitespace issues
                $normalizedSku = trim($row['sku'] ?? '');
                
                // Validate row data
                $validator = Validator::make($row->toArray(), [
                    'name' => 'required|string|max:255',
                    'category' => 'required|string',
                    'brand' => 'nullable|string|max:50',
                    'description' => 'nullable|string',
                    'price' => 'required|numeric|min:0',
                    'sku' => 'required|string|max:255',
                    'stock' => 'required|integer|min:0',
                    // Specifications (optional) - Existing fields
                    'processor' => 'nullable|string|max:255',
                    'gpu' => 'nullable|string|max:255',
                    'ram' => 'nullable|string|max:100',
                    'storage' => 'nullable|string|max:255',
                    'display' => 'nullable|string|max:255',
                    'keyboard' => 'nullable|string|max:255',
                    'battery' => 'nullable|string|max:255',
                    'warranty' => 'nullable|string|max:255',
                    'condition' => 'nullable|string|in:new,excellent,good,fair,used-excellent,used-very-good,used-good',
                    'extras' => 'nullable|string|max:500',
                    'original_price' => 'nullable|numeric|min:0',
                    'features' => 'nullable|string|max:1000',
                    // New specification fields (13 fields)
                    'product_number' => 'nullable|string|max:100',
                    'chipset' => 'nullable|string|max:255',
                    'optical_drive' => 'nullable|string|max:100',
                    'wireless_connectivity' => 'nullable|string|max:255',
                    'expansion_slots' => 'nullable|string|max:255',
                    'external_ports' => 'nullable|string|max:500',
                    'dimensions_width' => 'nullable|string|max:50',
                    'dimensions_depth' => 'nullable|string|max:50',
                    'dimensions_height' => 'nullable|string|max:50',
                    'weight' => 'nullable|string|max:50',
                    'power_supply_type' => 'nullable|string|max:255',
                    'webcam' => 'nullable|string|max:255',
                    'audio' => 'nullable|string|max:255',
                    'operating_system' => 'nullable|string|max:100',
                    'software_included' => 'nullable|string|max:500',
                ]);

                if ($validator->fails()) {
                    $this->results[] = [
                        'row' => $rowNumber,
                        'status' => 'error',
                        'message' => 'Validation failed: '.$validator->errors()->first(),
                        'data' => $row->toArray(),
                    ];

                    continue;
                }

                // Find category by name (case-insensitive)
                $category = Category::whereRaw('LOWER(name) = ?', [strtolower(trim($row['category']))])->first();

                if (! $category) {
                    $this->results[] = [
                        'row' => $rowNumber,
                        'status' => 'error',
                        'message' => "Category '{$row['category']}' not found. Please ensure category exists.",
                        'data' => $row->toArray(),
                    ];

                    continue;
                }

                // Check if SKU already exists in database (bulk checked earlier)
                // Use normalized SKU to match the bulk fetch logic that also trims
                if (isset($existingProducts[$normalizedSku])) {
                    $existingProduct = $existingProducts[$normalizedSku];
                    
                    if ($this->allowUpdate) {
                        // Mark for update in preview mode
                        if ($this->preview) {
                            $this->results[] = [
                                'row' => $rowNumber,
                                'status' => 'valid',
                                'message' => 'Will update existing product',
                                'action' => 'update',
                                'product_id' => $existingProduct->id,
                                'data' => $row->toArray(),
                            ];

                            continue;
                        }
                        
                        // Perform update (safe fields only)
                        $existingProduct->update([
                            'price' => $row['price'],
                            'stock' => $row['stock'],
                            'description' => $row['description'] ?? $existingProduct->description,
                        ]);

                        $this->results[] = [
                            'row' => $rowNumber,
                            'status' => 'success',
                            'message' => 'Product updated successfully',
                            'action' => 'update',
                            'product_id' => $existingProduct->id,
                            'data' => $row->toArray(),
                        ];

                        Log::info('Product updated successfully', [
                            'row' => $rowNumber,
                            'sku' => $normalizedSku,
                            'product_id' => $existingProduct->id,
                        ]);

                        continue;
                    } else {
                        // Skip existing products when update not allowed
                        $this->results[] = [
                            'row' => $rowNumber,
                            'status' => 'error',
                            'message' => "SKU '{$normalizedSku}' already exists in database",
                            'product_id' => $existingProduct->id,
                            'data' => $row->toArray(),
                        ];

                        continue;
                    }
                }

                // If preview mode, just mark as valid
                if ($this->preview) {
                    $this->results[] = [
                        'row' => $rowNumber,
                        'status' => 'valid',
                        'message' => 'Ready to import',
                        'action' => 'create',
                        'data' => $row->toArray(),
                    ];

                    continue;
                }

                // Build specifications array
                $specifications = [];
                $specFields = [
                    // Existing fields
                    'processor', 'gpu', 'ram', 'storage', 'display', 'keyboard', 'battery', 
                    'warranty', 'condition', 'extras', 'original_price', 'features',
                    // New fields (13 new specification fields)
                    'product_number', 'chipset', 'optical_drive', 'wireless_connectivity',
                    'expansion_slots', 'external_ports', 'dimensions_width', 'dimensions_depth',
                    'dimensions_height', 'weight', 'power_supply_type', 'webcam', 'audio',
                    'operating_system', 'software_included',
                ];

                foreach ($specFields as $field) {
                    if (isset($row[$field]) && ! empty($row[$field])) {
                        $specifications[$field] = $row[$field];
                    }
                }

                // Create product
                Product::create([
                    'name' => $row['name'],
                    'category_id' => $category->id,
                    'brand' => $row['brand'] ?? null,
                    'description' => $row['description'] ?? null,
                    'price' => $row['price'],
                    'sku' => $normalizedSku,
                    'stock' => $row['stock'],
                    'specifications' => ! empty($specifications) ? $specifications : null,
                    'image_url' => '', // No image support
                    'image_thumbnail_url' => null,
                ]);

                $this->results[] = [
                    'row' => $rowNumber,
                    'status' => 'success',
                    'message' => 'Product imported successfully',
                    'action' => 'create',
                    'data' => $row->toArray(),
                ];

                Log::info('Product imported successfully', [
                    'row' => $rowNumber,
                    'sku' => $normalizedSku,
                    'name' => $row['name'],
                ]);
            } catch (QueryException $e) {
                // Handle database constraint violations with user-friendly messages
                $errorMessage = $this->normalizeDatabaseError($e, $normalizedSku ?? 'unknown');
                
                $this->results[] = [
                    'row' => $rowNumber,
                    'status' => 'error',
                    'message' => $errorMessage,
                    'technical_details' => $e->getMessage(),
                    'data' => $row->toArray(),
                ];

                Log::error('Product import failed (database error)', [
                    'row' => $rowNumber,
                    'error' => $errorMessage,
                    'technical' => $e->getMessage(),
                    'data' => $row->toArray(),
                ]);
            } catch (\Exception $e) {
                $this->results[] = [
                    'row' => $rowNumber,
                    'status' => 'error',
                    'message' => 'Import failed: '.$e->getMessage(),
                    'data' => $row->toArray(),
                ];

                Log::error('Product import failed', [
                    'row' => $rowNumber,
                    'error' => $e->getMessage(),
                    'data' => $row->toArray(),
                ]);
            }
        }
    }

    /**
     * Get import results
     */
    public function getResults(): array
    {
        $summary = [
            'total' => count($this->results),
            'success' => count(array_filter($this->results, fn ($r) => $r['status'] === 'success')),
            'valid' => count(array_filter($this->results, fn ($r) => $r['status'] === 'valid')),
            'error' => count(array_filter($this->results, fn ($r) => $r['status'] === 'error')),
        ];

        return [
            'summary' => $summary,
            'details' => $this->results,
        ];
    }

    /**
     * Normalize database errors into user-friendly messages
     */
    protected function normalizeDatabaseError(QueryException $e, string $sku): string
    {
        $errorCode = $e->errorInfo[1] ?? null;
        $message = $e->getMessage();

        // Duplicate entry error (MySQL error code 1062)
        if ($errorCode === 1062) {
            // Try to extract the duplicate value from error message
            if (preg_match("/Duplicate entry '(.+?)' for key '(.+?)'/", $message, $matches)) {
                $duplicateValue = $matches[1];
                $keyName = $matches[2];

                if (str_contains($keyName, 'sku')) {
                    return "SKU '{$duplicateValue}' already exists in database";
                }

                return "Duplicate value '{$duplicateValue}' for {$keyName}";
            }

            return "SKU '{$sku}' already exists in database";
        }

        // Foreign key constraint error (MySQL error code 1452)
        if ($errorCode === 1452) {
            return 'Invalid reference to related data (foreign key constraint)';
        }

        // Data too long error (MySQL error code 1406)
        if ($errorCode === 1406) {
            return 'One or more fields exceed maximum length';
        }

        // Default fallback - return simplified message
        return 'Database error: '.substr($message, 0, 100);
    }

    /**
     * Find duplicate SKUs within the uploaded file
     * Returns array of [sku => [row_numbers]]
     */
    protected function findDuplicateSKUs(Collection $rows): array
    {
        $skuTracker = [];
        $duplicates = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because: 1 for heading, 1 for 0-based index
            $sku = $row['sku'] ?? null;

            if (!$sku) {
                continue; // Skip rows without SKU (will be caught by validation)
            }

            $sku = trim($sku);

            if (isset($skuTracker[$sku])) {
                // Duplicate found
                if (!isset($duplicates[$sku])) {
                    $duplicates[$sku] = [$skuTracker[$sku]]; // Add first occurrence
                }
                $duplicates[$sku][] = $rowNumber; // Add current occurrence
            } else {
                $skuTracker[$sku] = $rowNumber;
            }
        }

        return $duplicates;
    }
}
