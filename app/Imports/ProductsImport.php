<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
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

    public function __construct(bool $preview = false)
    {
        $this->preview = $preview;
    }

    /**
     * Process each row in the Excel file
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because: 1 for heading, 1 for 0-based index

            try {
                // Validate row data
                $validator = Validator::make($row->toArray(), [
                    'name' => 'required|string|max:255',
                    'category' => 'required|string',
                    'brand' => 'nullable|string|max:50',
                    'description' => 'nullable|string',
                    'price' => 'required|numeric|min:0',
                    'sku' => 'required|string|max:255',
                    'stock' => 'required|integer|min:0',
                    // Specifications (optional)
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

                // Check if SKU already exists
                $existingProduct = Product::where('sku', $row['sku'])->first();
                if ($existingProduct) {
                    $this->results[] = [
                        'row' => $rowNumber,
                        'status' => 'error',
                        'message' => "SKU '{$row['sku']}' already exists in database.",
                        'data' => $row->toArray(),
                    ];

                    continue;
                }

                // If preview mode, just mark as valid
                if ($this->preview) {
                    $this->results[] = [
                        'row' => $rowNumber,
                        'status' => 'valid',
                        'message' => 'Ready to import',
                        'data' => $row->toArray(),
                    ];

                    continue;
                }

                // Build specifications array
                $specifications = [];
                $specFields = ['processor', 'gpu', 'ram', 'storage', 'display', 'keyboard', 'battery', 'warranty', 'condition', 'extras', 'original_price', 'features'];

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
                    'sku' => $row['sku'],
                    'stock' => $row['stock'],
                    'specifications' => ! empty($specifications) ? $specifications : null,
                    'image_url' => '', // No image support
                    'image_thumbnail_url' => null,
                ]);

                $this->results[] = [
                    'row' => $rowNumber,
                    'status' => 'success',
                    'message' => 'Product imported successfully',
                    'data' => $row->toArray(),
                ];

                Log::info('Product imported successfully', [
                    'row' => $rowNumber,
                    'sku' => $row['sku'],
                    'name' => $row['name'],
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
}
