<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\MediaService;

class ProcessMediaImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;
    protected string $mediaType;
    protected array $resizeOptions;
    protected bool $isPrivate;

    /**
     * Create a new job instance.
     *
     * @param string $filePath The original file path
     * @param string $mediaType Media type (avatar, cover, before_after, story)
     * @param array $resizeOptions Resize configuration
     * @param bool $isPrivate Whether file is in private storage
     */
    public function __construct(
        string $filePath, 
        string $mediaType, 
        array $resizeOptions = [], 
        bool $isPrivate = false
    ) {
        $this->filePath = $filePath;
        $this->mediaType = $mediaType;
        $this->resizeOptions = $resizeOptions;
        $this->isPrivate = $isPrivate;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $disk = $this->isPrivate ? 'private' : 'public';
            
            if (!Storage::disk($disk)->exists($this->filePath)) {
                Log::warning("Media file not found for processing", [
                    'file_path' => $this->filePath,
                    'media_type' => $this->mediaType,
                    'disk' => $disk
                ]);
                return;
            }

            // Default resize configurations per media type
            $defaultSizes = $this->getDefaultSizesForMediaType($this->mediaType);
            $sizesToGenerate = array_merge($defaultSizes, $this->resizeOptions);

            foreach ($sizesToGenerate as $size => $dimensions) {
                $this->generateResizedVersion($size, $dimensions);
            }

            Log::info("Media image processed successfully", [
                'file_path' => $this->filePath,
                'media_type' => $this->mediaType,
                'sizes_generated' => array_keys($sizesToGenerate)
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to process media image", [
                'file_path' => $this->filePath,
                'media_type' => $this->mediaType,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Generate a resized version of the image
     * 
     * @param string $size Size identifier (e.g., 'thumbnail', 'medium', 'large')
     * @param array $dimensions Width and height configuration
     */
    private function generateResizedVersion(string $size, array $dimensions): void
    {
        // TODO: Implement actual image resizing logic
        // This could use libraries like Intervention Image or ImageMagick
        
        Log::info("Would generate resized image", [
            'original_path' => $this->filePath,
            'size' => $size,
            'dimensions' => $dimensions,
            'media_type' => $this->mediaType
        ]);

        /*
        Example implementation with Intervention Image:
        
        $disk = $this->isPrivate ? 'private' : 'public';
        $image = Image::make(Storage::disk($disk)->get($this->filePath));
        
        if (isset($dimensions['width']) && isset($dimensions['height'])) {
            $image->fit($dimensions['width'], $dimensions['height']);
        } elseif (isset($dimensions['width'])) {
            $image->widen($dimensions['width'], function ($constraint) {
                $constraint->upsize();
            });
        } elseif (isset($dimensions['height'])) {
            $image->heighten($dimensions['height'], function ($constraint) {
                $constraint->upsize();
            });
        }
        
        // Apply quality and format settings
        $quality = $dimensions['quality'] ?? 85;
        $format = $dimensions['format'] ?? 'jpg';
        
        $resizedPath = $this->getResizedPath($size);
        Storage::disk($disk)->put($resizedPath, $image->encode($format, $quality));
        */
    }

    /**
     * Get default resize configurations for different media types
     */
    private function getDefaultSizesForMediaType(string $mediaType): array
    {
        $configs = [
            'avatar' => [
                'thumbnail' => ['width' => 64, 'height' => 64, 'quality' => 90],
                'small' => ['width' => 128, 'height' => 128, 'quality' => 90],
                'medium' => ['width' => 256, 'height' => 256, 'quality' => 85],
            ],
            'cover' => [
                'small' => ['width' => 400, 'height' => 200, 'quality' => 85],
                'medium' => ['width' => 800, 'height' => 400, 'quality' => 85],
                'large' => ['width' => 1200, 'height' => 600, 'quality' => 80],
            ],
            'before_after' => [
                'thumbnail' => ['width' => 200, 'height' => 200, 'quality' => 90],
                'medium' => ['width' => 600, 'height' => 600, 'quality' => 85],
                'large' => ['width' => 1024, 'height' => 1024, 'quality' => 80],
            ],
            'story' => [
                'thumbnail' => ['width' => 150, 'height' => 200, 'quality' => 90],
                'medium' => ['width' => 400, 'height' => 600, 'quality' => 85],
            ],
        ];

        return $configs[$mediaType] ?? [];
    }

    /**
     * Generate the file path for a resized version
     */
    private function getResizedPath(string $size): string
    {
        $pathInfo = pathinfo($this->filePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'] ?? 'jpg';

        return "{$directory}/{$filename}_{$size}.{$extension}";
    }

    /**
     * Get the maximum number of attempts for this job
     */
    public function tries(): int
    {
        return 3;
    }

    /**
     * Calculate the number of seconds to wait before retrying the job
     */
    public function backoff(): array
    {
        return [30, 60, 120]; // Wait 30s, then 60s, then 120s between retries
    }

    /**
     * Handle a job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Media processing job failed permanently", [
            'file_path' => $this->filePath,
            'media_type' => $this->mediaType,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // TODO: Notify administrators or trigger fallback handling
    }
}

/*
Installation instructions for image processing:

1. Install Intervention Image package:
   composer require intervention/image

2. Publish config (optional):
   php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravelRecent"

3. Update .env with image driver preference:
   IMAGE_DRIVER=gd  # or 'imagick' if available

4. Example usage:
   ProcessMediaImageJob::dispatch('media/avatars/user123.jpg', 'avatar', [], false);

5. Configure queue worker in production:
   php artisan queue:work --queue=media,default --tries=3
*/