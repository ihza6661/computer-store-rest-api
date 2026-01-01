<?php

namespace App\Jobs;

use App\Imports\ProductsImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportProductsJob implements ShouldQueue
{
    use Queueable;

    protected $filePath;

    protected $userId;

    protected $jobId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, int $userId, string $jobId)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
        $this->jobId = $jobId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting product import job', [
                'job_id' => $this->jobId,
                'user_id' => $this->userId,
                'file_path' => $this->filePath,
            ]);

            // Update status to processing
            Cache::put("import_job_{$this->jobId}_status", 'processing', now()->addHours(1));

            // Perform the import
            $import = new ProductsImport(false); // Not preview mode
            Excel::import($import, $this->filePath);

            // Get results
            $results = $import->getResults();

            // Store results in cache for retrieval
            Cache::put("import_job_{$this->jobId}_results", $results, now()->addHours(1));
            Cache::put("import_job_{$this->jobId}_status", 'completed', now()->addHours(1));

            Log::info('Product import job completed', [
                'job_id' => $this->jobId,
                'summary' => $results['summary'],
            ]);

            // Clean up uploaded file
            if (Storage::exists($this->filePath)) {
                Storage::delete($this->filePath);
            }
        } catch (\Exception $e) {
            Log::error('Product import job failed', [
                'job_id' => $this->jobId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            Cache::put("import_job_{$this->jobId}_status", 'failed', now()->addHours(1));
            Cache::put("import_job_{$this->jobId}_error", $e->getMessage(), now()->addHours(1));

            throw $e;
        }
    }
}
