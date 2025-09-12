<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaService
{
    /**
     * Generate a signed URL for private media access
     * 
     * @param string $type Media type (before_after, cover, avatar, story)
     * @param string $modelId The model ID
     * @param array $params Additional parameters (e.g., type=after, index=0)
     * @param int $expiresInMinutes URL expiration time in minutes (default: 60)
     * @return string Signed URL
     */
    public function generateSignedUrl(
        string $type, 
        string $modelId, 
        array $params = [], 
        int $expiresInMinutes = 60
    ): string {
        $mediaId = "{$type}_{$modelId}";
        
        return URL::temporarySignedRoute(
            'api.media.signed',
            now()->addMinutes($expiresInMinutes),
            array_merge(['id' => $mediaId], $params)
        );
    }

    /**
     * Generate a public media URL
     * 
     * @param string $type Media type (avatars, covers, stories)
     * @param string $filename The filename
     * @return string Public URL
     */
    public function generatePublicUrl(string $type, string $filename): string
    {
        return route('api.media.public', [
            'type' => $type,
            'filename' => $filename
        ]);
    }

    /**
     * Convert a database storage path to a public or signed URL
     * 
     * @param string|null $path The database storage path
     * @param string $type Media type for signed URL generation
     * @param string $modelId Model ID for signed URL generation
     * @param bool $isPrivate Whether this is private media requiring signed URL
     * @param array $params Additional parameters for signed URLs
     * @return string|null The converted URL or null if no path
     */
    public function convertPathToUrl(
        ?string $path, 
        string $type, 
        string $modelId, 
        bool $isPrivate = false,
        array $params = []
    ): ?string {
        if (!$path) {
            return null;
        }

        if ($isPrivate) {
            return $this->generateSignedUrl($type, $modelId, $params);
        }

        // For public files, extract filename and generate public URL
        $filename = basename($path);
        $publicType = $this->getPublicMediaType($type);
        
        return $this->generatePublicUrl($publicType, $filename);
    }

    /**
     * Store a media file and return the storage path
     * 
     * @param \Illuminate\Http\UploadedFile $file The uploaded file
     * @param string $type Media type (before_after, covers, avatars, stories)
     * @param bool $isPrivate Whether to store in private storage
     * @param string|null $customName Custom filename (optional)
     * @return string The storage path
     */
    public function storeMediaFile(
        $file, 
        string $type, 
        bool $isPrivate = false, 
        ?string $customName = null
    ): string {
        $disk = $isPrivate ? 'private' : 'public';
        $directory = $isPrivate ? "media/{$type}" : "media/{$type}";
        
        $filename = $customName ?: $this->generateUniqueFilename($file);
        
        $path = $file->storeAs($directory, $filename, $disk);
        
        return $path;
    }

    /**
     * Delete a media file from storage
     * 
     * @param string $path The storage path
     * @param bool $isPrivate Whether the file is in private storage
     * @return bool Success status
     */
    public function deleteMediaFile(string $path, bool $isPrivate = false): bool
    {
        $disk = $isPrivate ? 'private' : 'public';
        
        return Storage::disk($disk)->delete($path);
    }

    /**
     * Check if a media file exists
     * 
     * @param string $path The storage path
     * @param bool $isPrivate Whether to check private storage
     * @return bool
     */
    public function mediaFileExists(string $path, bool $isPrivate = false): bool
    {
        $disk = $isPrivate ? 'private' : 'public';
        
        return Storage::disk($disk)->exists($path);
    }

    /**
     * Get file size of a media file
     * 
     * @param string $path The storage path
     * @param bool $isPrivate Whether the file is in private storage
     * @return int File size in bytes
     */
    public function getMediaFileSize(string $path, bool $isPrivate = false): int
    {
        $disk = $isPrivate ? 'private' : 'public';
        
        return Storage::disk($disk)->size($path);
    }

    /**
     * Generate a unique filename for uploaded media
     * 
     * @param \Illuminate\Http\UploadedFile $file The uploaded file
     * @return string Unique filename
     */
    private function generateUniqueFilename($file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);
        
        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Map internal media types to public directory names
     * 
     * @param string $type Internal media type
     * @return string Public directory name
     */
    private function getPublicMediaType(string $type): string
    {
        $mapping = [
            'cover' => 'covers',
            'avatar' => 'avatars',
            'story' => 'stories',
            'before_after' => 'before_after', // These should typically be private
        ];

        return $mapping[$type] ?? $type;
    }

    /**
     * Get the appropriate storage path for a media type
     * 
     * @param string $type Media type
     * @param bool $isPrivate Whether this is private storage
     * @return string Storage directory path
     */
    public function getStorageDirectory(string $type, bool $isPrivate = false): string
    {
        return "media/{$type}";
    }

    /**
     * Validate if a file type is allowed for media upload
     * 
     * @param \Illuminate\Http\UploadedFile $file The uploaded file
     * @param array $allowedTypes Allowed MIME types
     * @return bool
     */
    public function validateFileType($file, array $allowedTypes = []): bool
    {
        $defaultAllowedTypes = [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp',
            'video/mp4',
            'video/quicktime',
            'video/x-msvideo'
        ];

        $allowedTypes = !empty($allowedTypes) ? $allowedTypes : $defaultAllowedTypes;
        
        return in_array($file->getMimeType(), $allowedTypes);
    }

    /**
     * Validate file size
     * 
     * @param \Illuminate\Http\UploadedFile $file The uploaded file
     * @param int $maxSizeInMB Maximum file size in megabytes (default: 10MB)
     * @return bool
     */
    public function validateFileSize($file, int $maxSizeInMB = 10): bool
    {
        $maxSizeInBytes = $maxSizeInMB * 1024 * 1024;
        
        return $file->getSize() <= $maxSizeInBytes;
    }
}