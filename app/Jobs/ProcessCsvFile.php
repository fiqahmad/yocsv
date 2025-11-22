<?php

namespace App\Jobs;

use App\Models\CsvUpload;
use App\Services\CsvParserService;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessCsvFile implements ShouldQueue
{
    use Queueable;

    public $timeout = 300;
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public CsvUpload $csvUpload
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CsvParserService $parser): void
    {
        try {
            $this->csvUpload->update(['status' => 'processing']);

            $filePath = storage_path('app/public/csv_uploads/' . $this->csvUpload->file_name);

            if (!file_exists($filePath)) {
                throw new \Exception("File not found: {$filePath}");
            }

            $stats = $parser->parseAndImport($filePath);

            $status = $stats['errors'] > 0 ? 'completed_with_errors' : 'completed';

            $this->csvUpload->update([
                'status' => $status,
                'total_rows' => $stats['total_rows'],
                'inserted_rows' => $stats['inserted'],
                'updated_rows' => $stats['updated'],
                'error_rows' => $stats['errors'],
                'error_messages' => !empty($stats['error_messages']) ? json_encode($stats['error_messages']) : null,
            ]);

            // Send notifications if user exists
            if ($this->csvUpload->user) {
                $notificationService = new NotificationService();
                $notificationService->csvProcessed(
                    $this->csvUpload->user,
                    $this->csvUpload->id,
                    $this->csvUpload->file_name,
                    [
                        'total_rows' => $stats['total_rows'],
                        'inserted_rows' => $stats['inserted'],
                        'updated_rows' => $stats['updated'],
                        'error_rows' => $stats['errors'],
                    ]
                );
            }

            Log::info("CSV processing completed", [
                'file' => $this->csvUpload->file_name,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            $this->csvUpload->update([
                'status' => 'failed',
                'error_messages' => json_encode([$e->getMessage()])
            ]);

            // Send failure notifications if user exists
            if ($this->csvUpload->user) {
                $notificationService = new NotificationService();
                $notificationService->csvFailed(
                    $this->csvUpload->user,
                    $this->csvUpload->id,
                    $this->csvUpload->file_name,
                    $e->getMessage()
                );
            }

            Log::error("CSV processing failed", [
                'file' => $this->csvUpload->file_name,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $this->csvUpload->update([
            'status' => 'failed',
            'error_messages' => json_encode([$exception->getMessage()])
        ]);
    }
}
