<?php

namespace Tests\Unit;

use App\Imports\ProductsImport;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductsImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test categories
        Category::create(['name' => 'Laptop', 'slug' => 'laptop']);
        Category::create(['name' => 'Desktop', 'slug' => 'desktop']);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $import = new ProductsImport(true); // Preview mode

        $rows = new Collection([
            new Collection([
                'name' => '', // Missing required field
                'category' => 'Laptop',
                'price' => 1000000,
                'sku' => 'TEST-001',
                'stock' => 5,
            ]),
        ]);

        $import->collection($rows);
        $results = $import->getResults();

        $this->assertEquals(1, $results['summary']['error']);
        $this->assertStringContainsString('Validation failed', $results['details'][0]['message']);
    }

    /** @test */
    public function it_validates_category_exists()
    {
        $import = new ProductsImport(true); // Preview mode

        $rows = new Collection([
            new Collection([
                'name' => 'Test Product',
                'category' => 'NonExistentCategory',
                'price' => 1000000,
                'sku' => 'TEST-001',
                'stock' => 5,
            ]),
        ]);

        $import->collection($rows);
        $results = $import->getResults();

        $this->assertEquals(1, $results['summary']['error']);
        $this->assertStringContainsString('Category \'NonExistentCategory\' not found', $results['details'][0]['message']);
    }

    /** @test */
    public function it_validates_category_case_insensitive()
    {
        $import = new ProductsImport(true); // Preview mode

        $rows = new Collection([
            new Collection([
                'name' => 'Test Product',
                'category' => 'laptop', // lowercase should match 'Laptop'
                'price' => 1000000,
                'sku' => 'TEST-001',
                'stock' => 5,
            ]),
        ]);

        $import->collection($rows);
        $results = $import->getResults();

        $this->assertEquals(1, $results['summary']['valid']);
        $this->assertEquals('Ready to import', $results['details'][0]['message']);
    }

    /** @test */
    public function it_validates_duplicate_sku()
    {
        // Create existing product
        Product::create([
            'name' => 'Existing Product',
            'category_id' => Category::where('name', 'Laptop')->first()->id,
            'price' => 1000000,
            'sku' => 'DUPLICATE-SKU',
            'stock' => 5,
            'image_url' => '',
        ]);

        $import = new ProductsImport(true); // Preview mode

        $rows = new Collection([
            new Collection([
                'name' => 'New Product',
                'category' => 'Laptop',
                'price' => 2000000,
                'sku' => 'DUPLICATE-SKU', // Duplicate SKU
                'stock' => 10,
            ]),
        ]);

        $import->collection($rows);
        $results = $import->getResults();

        $this->assertEquals(1, $results['summary']['error']);
        $this->assertStringContainsString('SKU \'DUPLICATE-SKU\' already exists', $results['details'][0]['message']);
    }

    /** @test */
    public function it_validates_numeric_fields()
    {
        $import = new ProductsImport(true); // Preview mode

        $rows = new Collection([
            new Collection([
                'name' => 'Test Product',
                'category' => 'Laptop',
                'price' => 'not-a-number', // Invalid price
                'sku' => 'TEST-001',
                'stock' => 5,
            ]),
        ]);

        $import->collection($rows);
        $results = $import->getResults();

        $this->assertEquals(1, $results['summary']['error']);
        $this->assertStringContainsString('Validation failed', $results['details'][0]['message']);
    }

    /** @test */
    public function it_validates_condition_enum()
    {
        $import = new ProductsImport(true); // Preview mode

        $rows = new Collection([
            new Collection([
                'name' => 'Test Product',
                'category' => 'Laptop',
                'price' => 1000000,
                'sku' => 'TEST-001',
                'stock' => 5,
                'condition' => 'invalid-condition', // Invalid condition
            ]),
        ]);

        $import->collection($rows);
        $results = $import->getResults();

        $this->assertEquals(1, $results['summary']['error']);
        $this->assertStringContainsString('Validation failed', $results['details'][0]['message']);
    }

    /** @test */
    public function it_accepts_valid_conditions()
    {
        $validConditions = ['new', 'excellent', 'good', 'fair', 'used-excellent', 'used-very-good', 'used-good'];

        foreach ($validConditions as $index => $condition) {
            $import = new ProductsImport(true); // Preview mode

            $rows = new Collection([
                new Collection([
                    'name' => 'Test Product',
                    'category' => 'Laptop',
                    'price' => 1000000,
                    'sku' => "TEST-{$index}",
                    'stock' => 5,
                    'condition' => $condition,
                ]),
            ]);

            $import->collection($rows);
            $results = $import->getResults();

            $this->assertEquals(1, $results['summary']['valid'], "Condition '{$condition}' should be valid");
        }
    }

    /** @test */
    public function it_processes_valid_row_in_preview_mode()
    {
        $import = new ProductsImport(true); // Preview mode

        $rows = new Collection([
            new Collection([
                'name' => 'Valid Product',
                'category' => 'Laptop',
                'brand' => 'Apple',
                'description' => 'Test description',
                'price' => 1500000,
                'sku' => 'VALID-001',
                'stock' => 10,
                'processor' => 'Intel i7',
                'ram' => '16GB',
                'storage' => '512GB SSD',
            ]),
        ]);

        $import->collection($rows);
        $results = $import->getResults();

        $this->assertEquals(1, $results['summary']['total']);
        $this->assertEquals(1, $results['summary']['valid']);
        $this->assertEquals(0, $results['summary']['error']);
        $this->assertEquals('Ready to import', $results['details'][0]['message']);
    }

    /** @test */
    public function it_creates_product_when_not_in_preview_mode()
    {
        $import = new ProductsImport(false); // Import mode (not preview)

        $rows = new Collection([
            new Collection([
                'name' => 'Product to Create',
                'category' => 'Laptop',
                'brand' => 'Dell',
                'description' => 'Test product',
                'price' => 2000000,
                'sku' => 'CREATE-001',
                'stock' => 15,
                'processor' => 'AMD Ryzen 7',
                'ram' => '32GB',
            ]),
        ]);

        $this->assertEquals(0, Product::count());

        $import->collection($rows);
        $results = $import->getResults();

        $this->assertEquals(1, Product::count());
        $this->assertEquals(1, $results['summary']['success']);

        $product = Product::first();
        $this->assertEquals('Product to Create', $product->name);
        $this->assertEquals('CREATE-001', $product->sku);
        $this->assertEquals(2000000, $product->price);
        $this->assertArrayHasKey('processor', $product->specifications);
        $this->assertEquals('AMD Ryzen 7', $product->specifications['processor']);
    }

    /** @test */
    public function it_does_not_create_product_in_preview_mode()
    {
        $import = new ProductsImport(true); // Preview mode

        $rows = new Collection([
            new Collection([
                'name' => 'Product Should Not Be Created',
                'category' => 'Laptop',
                'price' => 1000000,
                'sku' => 'NO-CREATE-001',
                'stock' => 5,
            ]),
        ]);

        $import->collection($rows);

        $this->assertEquals(0, Product::count());
    }

    /** @test */
    public function it_handles_optional_fields_correctly()
    {
        $import = new ProductsImport(false); // Import mode

        $rows = new Collection([
            new Collection([
                'name' => 'Minimal Product',
                'category' => 'Laptop',
                'price' => 1000000,
                'sku' => 'MINIMAL-001',
                'stock' => 5,
                // No optional fields
            ]),
        ]);

        $import->collection($rows);

        $product = Product::first();
        $this->assertNotNull($product);
        $this->assertEquals('Minimal Product', $product->name);
        $this->assertNull($product->brand);
        $this->assertNull($product->description);
        $this->assertNull($product->specifications);
    }

    /** @test */
    public function it_processes_multiple_rows_correctly()
    {
        $import = new ProductsImport(true); // Preview mode

        $rows = new Collection([
            new Collection([
                'name' => 'Product 1',
                'category' => 'Laptop',
                'price' => 1000000,
                'sku' => 'MULTI-001',
                'stock' => 5,
            ]),
            new Collection([
                'name' => 'Product 2',
                'category' => 'Desktop',
                'price' => 2000000,
                'sku' => 'MULTI-002',
                'stock' => 3,
            ]),
            new Collection([
                'name' => '', // Invalid
                'category' => 'Laptop',
                'price' => 3000000,
                'sku' => 'MULTI-003',
                'stock' => 1,
            ]),
        ]);

        $import->collection($rows);
        $results = $import->getResults();

        $this->assertEquals(3, $results['summary']['total']);
        $this->assertEquals(2, $results['summary']['valid']);
        $this->assertEquals(1, $results['summary']['error']);
    }

    /** @test */
    public function it_reports_correct_row_numbers()
    {
        $import = new ProductsImport(true); // Preview mode

        $rows = new Collection([
            new Collection(['name' => 'P1', 'category' => 'Laptop', 'price' => 1000000, 'sku' => 'R1', 'stock' => 5]),
            new Collection(['name' => '', 'category' => 'Laptop', 'price' => 1000000, 'sku' => 'R2', 'stock' => 5]), // Row 3 (header is row 1)
            new Collection(['name' => 'P3', 'category' => 'Laptop', 'price' => 1000000, 'sku' => 'R3', 'stock' => 5]),
        ]);

        $import->collection($rows);
        $results = $import->getResults();

        $this->assertEquals(2, $results['details'][0]['row']); // First data row
        $this->assertEquals(3, $results['details'][1]['row']); // Second data row (error)
        $this->assertEquals(4, $results['details'][2]['row']); // Third data row
    }
}
