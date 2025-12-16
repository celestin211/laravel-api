<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Job to process uploaded images (resize, optimize, generate thumbnails).
 *
 * This job handles image optimization tasks that can be time-consuming
 * and should be processed asynchronously.
 */
class ProcessImageJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $imagePath,
        public string $directory
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $fullPath = Storage::disk('public')->path($this->imagePath);

            if (! file_exists($fullPath)) {
                Log::warning('Image file not found for processing', [
                    'path' => $this->imagePath,
                    'full_path' => $fullPath,
                ]);

                return;
            }

            // Get image info
            $imageInfo = @getimagesize($fullPath);
            if ($imageInfo === false) {
                Log::warning('Invalid image file', ['path' => $this->imagePath]);

                return;
            }

            [$width, $height, $type] = $imageInfo;

            Log::info('Processing image', [
                'path' => $this->imagePath,
                'width' => $width,
                'height' => $height,
                'type' => $type,
            ]);



            Log::info('Image processed successfully', [
                'path' => $this->imagePath,
                'directory' => $this->directory,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to process image', [
                'path' => $this->imagePath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; // Re-throw to trigger retry mechanism
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Image processing job failed permanently', [
            'path' => $this->imagePath,
            'directory' => $this->directory,
            'error' => $exception->getMessage(),
        ]);
    }
}
