<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BeforeAfterCase;
use App\Models\Story;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    /**
     * Serve private media files with signature validation
     * 
     * GET /api/media/{id}?signature=...&expires=...
     */
    public function show(Request $request, string $id)
    {
        // Validate signature for private media access
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature');
        }

        // Parse the media ID to get type and actual ID
        // Format: {type}_{id} (e.g., "before_after_123", "cover_456")
        $parts = explode('_', $id, 2);
        if (count($parts) !== 2) {
            abort(404, 'Invalid media ID format');
        }

        [$type, $modelId] = $parts;

        try {
            $filePath = $this->getMediaPath($type, $modelId, $request->get('variant', 'original'));
            
            if (!Storage::disk('private')->exists($filePath)) {
                abort(404, 'Media file not found');
            }

            // Additional authorization checks based on media type
            $this->authorizeMediaAccess($type, $modelId);

            return $this->streamFile($filePath);

        } catch (\Exception $e) {
            abort(404, 'Media not found');
        }
    }

    /**
     * Get the file path for the requested media
     */
    private function getMediaPath(string $type, string $modelId, string $variant = 'original'): string
    {
        switch ($type) {
            case 'before_after':
                $case = BeforeAfterCase::findOrFail($modelId);
                $field = request()->get('type') === 'after' ? 'after_path' : 'before_path';
                return $case->{$field};

            case 'cover':
                // Could be doctor or clinic cover
                if ($doctor = Doctor::find($modelId)) {
                    return $doctor->cover_path;
                }
                if ($clinic = Clinic::find($modelId)) {
                    return $clinic->cover_path;
                }
                throw new \Exception('Cover not found');

            case 'avatar':
                $doctor = Doctor::findOrFail($modelId);
                return $doctor->avatar_path ?? null;

            case 'story':
                $story = Story::findOrFail($modelId);
                // Stories can have multiple media items in JSON
                $mediaIndex = (int) request()->get('index', 0);
                $media = is_array($story->media) ? $story->media : json_decode($story->media, true);
                
                if (!isset($media[$mediaIndex]['path'])) {
                    throw new \Exception('Story media not found');
                }
                
                return $media[$mediaIndex]['path'];

            default:
                throw new \Exception('Unknown media type');
        }
    }

    /**
     * Authorize access to the media based on type and user permissions
     */
    private function authorizeMediaAccess(string $type, string $modelId): void
    {
        $user = Auth::user();

        switch ($type) {
            case 'before_after':
                $case = BeforeAfterCase::findOrFail($modelId);
                
                // Only approved and published cases are accessible
                if (!$case->is_approved || $case->status !== 'published') {
                    // Only the owner doctor/clinic or admins can access unpublished
                    if (!$user || (
                        $user->id !== $case->doctor_id && 
                        $user->id !== $case->clinic_id && 
                        $user->role !== 'admin'
                    )) {
                        abort(403, 'Access denied to private media');
                    }
                }
                break;

            case 'cover':
            case 'avatar':
                // These are generally public but could have restrictions
                // Add authorization logic here if needed
                break;

            case 'story':
                $story = Story::findOrFail($modelId);
                
                // Check if story is active and accessible
                if (!$story->isActive() && (!$user || (
                    $user->id !== $story->owner_id && 
                    $user->role !== 'admin'
                ))) {
                    abort(403, 'Access denied to expired story');
                }
                break;
        }
    }

    /**
     * Stream the file to the browser
     */
    private function streamFile(string $filePath): StreamedResponse
    {
        $mimeType = $this->getMimeType($filePath);
        $fileSize = Storage::disk('private')->size($filePath);

        return response()->stream(function () use ($filePath) {
            $stream = Storage::disk('private')->readStream($filePath);
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Cache-Control' => 'private, max-age=3600',
            'Content-Disposition' => 'inline',
        ]);
    }

    /**
     * Get MIME type based on file extension
     */
    private function getMimeType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'mp4' => 'video/mp4',
            'mov' => 'video/quicktime',
            'avi' => 'video/x-msvideo',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Get public media URL (for non-sensitive content)
     * 
     * GET /api/media/public/{type}/{filename}
     */
    public function showPublic(Request $request, string $type, string $filename)
    {
        $allowedTypes = ['avatars', 'covers', 'stories'];
        
        if (!in_array($type, $allowedTypes)) {
            abort(404, 'Invalid media type');
        }

        $filePath = "media/{$type}/{$filename}";
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Media file not found');
        }

        $mimeType = $this->getMimeType($filePath);
        
        return response()->stream(function () use ($filePath) {
            $stream = Storage::disk('public')->readStream($filePath);
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=86400', // 24 hours for public content
            'Content-Disposition' => 'inline',
        ]);
    }
}