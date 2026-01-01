<?php

namespace Tests\Feature;

use App\Jobs\ImportProductsJob;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create(['role' => 'admin']);

        // Create test categories
        Category::create(['name' => 'Laptop', 'slug' => 'laptop']);
        Category::create(['name' => 'Desktop', 'slug' => 'desktop']);

        Storage::fake('local');
    }

    /** @test */
    public function it_shows_import_page_for_authenticated_admin()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.import'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_redirects_unauthenticated_users_from_import_page()
    {
        $response = $this->get(route('admin.products.import'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function it_downloads_template_file()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.import.template'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertDownload('product_import_template_'.date('Y-m-d').'.xlsx');
    }

    /** @test */
    public function it_validates_file_is_required_for_preview()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.import.preview'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function it_validates_file_type_for_preview()
    {
        $invalidFile = UploadedFile::fake()->create('test.pdf', 100);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.import.preview'), [
                'file' => $invalidFile,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function it_validates_file_size_for_preview()
    {
        $largeFile = UploadedFile::fake()->create('large.xlsx', 15000); // 15MB > 10MB limit

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.import.preview'), [
                'file' => $largeFile,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function it_processes_preview_request_successfully()
    {
        // Create a simple Excel file with valid data
        $file = $this->createTestExcelFile([
            ['name', 'category', 'brand', 'description', 'price', 'sku', 'stock'],
            ['Test Product', 'Laptop', 'Apple', 'Test description', 1000000, 'TEST-001', 5],
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.import.preview'), [
                'file' => $file,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'results' => [
                'summary' => ['total', 'valid', 'error'],
                'details',
            ],
        ]);
    }

    /** @test */
    public function it_validates_file_is_required_for_import()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.import.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function it_dispatches_import_job_successfully()
    {
        Queue::fake();

        $file = $this->createTestExcelFile([
            ['name', 'category', 'brand', 'description', 'price', 'sku', 'stock'],
            ['Test Product', 'Laptop', 'Apple', 'Test description', 1000000, 'TEST-001', 5],
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.import.store'), [
                'file' => $file,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'job_id',
            'message',
        ]);

        Queue::assertPushed(ImportProductsJob::class);
    }

    /** @test */
    public function it_stores_uploaded_file_temporarily()
    {
        Queue::fake();

        $file = $this->createTestExcelFile([
            ['name', 'category', 'brand', 'description', 'price', 'sku', 'stock'],
            ['Test Product', 'Laptop', 'Apple', 'Test description', 1000000, 'TEST-001', 5],
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.products.import.store'), [
                'file' => $file,
            ]);

        $response->assertStatus(200);

        // Check file was stored
        $files = Storage::disk('local')->files('imports');
        $this->assertNotEmpty($files, 'No files were stored in imports directory');
    }

    /** @test */
    public function it_returns_import_status_for_pending_job()
    {
        $jobId = 'test-job-id-123';
        Cache::put("import_job_{$jobId}_status", 'processing', 60);

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.products.import.status', ['jobId' => $jobId]));

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'processing',
        ]);
    }

    /** @test */
    public function it_returns_import_status_for_completed_job()
    {
        $jobId = 'test-job-id-456';
        $results = [
            'summary' => [
                'total' => 5,
                'success' => 5,
                'error' => 0,
            ],
            'details' => [],
        ];

        Cache::put("import_job_{$jobId}_status", 'completed', 60);
        Cache::put("import_job_{$jobId}_results", $results, 60);

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.products.import.status', ['jobId' => $jobId]));

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'completed',
            'results' => $results,
        ]);
    }

    /** @test */
    public function it_returns_import_status_for_failed_job()
    {
        $jobId = 'test-job-id-789';
        Cache::put("import_job_{$jobId}_status", 'failed', 60);
        Cache::put("import_job_{$jobId}_error", 'Test error message', 60);

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.products.import.status', ['jobId' => $jobId]));

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'failed',
            'error' => 'Test error message',
        ]);
    }

    /** @test */
    public function it_returns_not_found_status_for_unknown_job()
    {
        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.products.import.status', ['jobId' => 'non-existent-job']));

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'not_found',
        ]);
    }

    /**
     * Helper method to create test Excel file
     */
    protected function createTestExcelFile(array $data): UploadedFile
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, null, 'A1');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'test_excel_');
        $writer->save($tempFile);

        return new UploadedFile($tempFile, 'test.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
    }
}
