<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RequiresAuthentication;
use App\Infrastructure\Models\MedicalFile;
use App\Infrastructure\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

/**
 * @group File Management
 *
 * Secure medical file upload, download, and management with signed URLs
 */
class FileController extends Controller
{
    use RequiresAuthentication;
    /**
     * Upload medical file
     *
     * Upload a medical file with proper security checks and role-based access
     *
     * @authenticated
     *
     * @bodyParam file file required The file to upload. Max size: 50MB.
     * @bodyParam category string required File category (xray, photo, document, report, treatment_plan, prescription). Example: xray
     * @bodyParam access_level string required Access level (private, clinic, doctor, patient). Example: clinic
     * @bodyParam related_to_type string optional Related model type. Example: App\Infrastructure\Models\Patient
     * @bodyParam related_to_id string optional Related model ID. Example: 123e4567-e89b-12d3-a456-426614174000
     * @bodyParam expiry_days integer optional Days until file expires. Example: 365
     *
     * @response 201 {
     *   "message": "Medical file uploaded successfully",
     *   "file": {
     *     "id": "123e4567-e89b-12d3-a456-426614174000",
     *     "original_name": "xray_report.pdf",
     *     "file_category": "xray",
     *     "access_level": "clinic",
     *     "file_size": 1024000,
     *     "formatted_size": "1.00 MB",
     *     "virus_scan_status": "pending",
     *     "download_url": "http://localhost:8000/api/files/123e4567-e89b-12d3-a456-426614174000/download?signature=abc123&expires=1640995200",
     *     "uploaded_at": "2023-01-01T12:00:00.000000Z"
     *   }
     * }
     */
    public function upload(Request $request): JsonResponse
    {
        $this->requireAuth('upload_medical_file');
        
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:51200', // 50MB max for medical files
            'category' => 'required|string|in:xray,photo,document,report,treatment_plan,prescription',
            'access_level' => 'required|string|in:private,clinic,doctor,patient',
            'related_to_type' => 'nullable|string',
            'related_to_id' => 'nullable|uuid',
            'expiry_days' => 'nullable|integer|min:1|max:3650', // Max 10 years
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $uploadedFile = $request->file('file');
        $category = $request->input('category');
        $accessLevel = $request->input('access_level');

        // Check upload permissions using policy
        $this->authorizeAction('uploadCategory', [MedicalFile::class, $category], 'upload_file_category');

        // Check access level permissions
        $this->authorizeAction('setAccessLevel', [MedicalFile::class, $accessLevel], 'set_access_level');

        // Validate file security
        $validationResult = $this->validateFileFormat($uploadedFile, $category);
        if (!$validationResult['valid']) {
            return response()->json([
                'message' => 'Invalid file format',
                'error' => $validationResult['error']
            ], 422);
        }

        try {
            // Generate secure filename and path
            $secureFilename = MedicalFile::generateSecureFilename($uploadedFile->getClientOriginalName());
            $fileHash = hash_file('sha256', $uploadedFile->getRealPath());

            // Check for duplicate files
            $existingFile = MedicalFile::where('file_hash', $fileHash)
                ->where('uploaded_by', $user->id)
                ->first();

            if ($existingFile) {
                return response()->json([
                    'message' => 'File already exists',
                    'file' => [
                        'id' => $existingFile->id,
                        'original_name' => $existingFile->original_name,
                        'download_url' => $this->generateSignedDownloadUrl($existingFile),
                    ]
                ], 409);
            }

            // Store file in medical disk
            $filePath = Storage::disk('medical')->putFileAs(
                '',
                $uploadedFile,
                $secureFilename
            );

            // Calculate expiry date
            $expiryDate = $request->input('expiry_days')
                ? Carbon::now()->addDays($request->input('expiry_days'))
                : null;

            // Create medical file record
            $medicalFile = MedicalFile::create([
                'original_name' => $uploadedFile->getClientOriginalName(),
                'filename' => basename($secureFilename),
                'file_path' => $secureFilename,
                'file_size' => $uploadedFile->getSize(),
                'mime_type' => $uploadedFile->getMimeType(),
                'file_hash' => $fileHash,
                'uploaded_by' => $user->id,
                'related_to_type' => $request->input('related_to_type'),
                'related_to_id' => $request->input('related_to_id'),
                'file_category' => $category,
                'access_level' => $accessLevel,
                'expiry_date' => $expiryDate,
                'metadata' => [
                    'upload_ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'original_size' => $uploadedFile->getSize(),
                ],
            ]);

            // Dispatch virus scan job (placeholder for now)
            \App\Jobs\VirusScanJob::dispatch($medicalFile);

            // Generate signed download URL
            $downloadUrl = $this->generateSignedDownloadUrl($medicalFile);

            return response()->json([
                'message' => 'Medical file uploaded successfully',
                'file' => [
                    'id' => $medicalFile->id,
                    'original_name' => $medicalFile->original_name,
                    'file_category' => $medicalFile->file_category,
                    'access_level' => $medicalFile->access_level,
                    'file_size' => $medicalFile->file_size,
                    'formatted_size' => $medicalFile->formatted_size,
                    'virus_scan_status' => $medicalFile->virus_scan_status,
                    'download_url' => $downloadUrl,
                    'uploaded_at' => $medicalFile->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'File upload failed',
                'error' => 'An error occurred while uploading the file'
            ], 500);
        }
    }

    /**
     * Download medical file
     *
     * Download a medical file using signed URL for security (10-minute TTL)
     *
     * @urlParam id string required The file UUID. Example: 123e4567-e89b-12d3-a456-426614174000
     * @queryParam signature string required Signed URL signature. Example: abc123
     * @queryParam expires integer required URL expiration timestamp. Example: 1640995200
     *
     * @response 200 Binary file content
     * @response 403 {
     *   "message": "Invalid or expired download link"
     * }
     * @response 404 {
     *   "message": "File not found"
     * }
     */
    public function download(Request $request, string $fileId): StreamedResponse|JsonResponse
    {
        // Verify signed URL
        if (!$request->hasValidSignature()) {
            return response()->json([
                'message' => 'Invalid or expired download link'
            ], 403);
        }

        $medicalFile = MedicalFile::with('uploader')->find($fileId);

        if (!$medicalFile) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        $user = $request->user();

        // Check download permissions using policy
        if (!$user->can('download', $medicalFile)) {
            return response()->json([
                'message' => 'You do not have permission to access this file'
            ], 403);
        }

        // Check virus scan status
        if ($medicalFile->virus_scan_status === MedicalFile::SCAN_INFECTED) {
            return response()->json([
                'message' => 'File is infected and cannot be downloaded'
            ], 403);
        }

        // Check if file exists on storage
        if (!Storage::disk('medical')->exists($medicalFile->file_path)) {
            return response()->json([
                'message' => 'File not found on storage'
            ], 404);
        }

        // Check expiry
        if ($medicalFile->expiry_date && $medicalFile->expiry_date->isPast()) {
            return response()->json([
                'message' => 'File has expired'
            ], 410);
        }

        // Stream file download with proper headers
        $response = Storage::disk('medical')->response(
            $medicalFile->file_path,
            $medicalFile->original_name,
            [
                'Content-Type' => $medicalFile->mime_type,
                'Content-Disposition' => 'attachment; filename="' . $medicalFile->original_name . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]
        );

        return $response;
    }

    /**
     * Get signed download URL
     *
     * Generate a temporary signed URL for file download (10-minute expiry)
     *
     * @authenticated
     *
     * @urlParam id string required The file UUID. Example: 123e4567-e89b-12d3-a456-426614174000
     *
     * @response 200 {
     *   "download_url": "http://localhost:8000/api/files/123e4567-e89b-12d3-a456-426614174000/download?signature=abc123&expires=1640995200",
     *   "expires_at": "2023-01-01T12:10:00.000000Z"
     * }
     */
    public function getSignedUrl(Request $request, string $fileId): JsonResponse
    {
        $medicalFile = MedicalFile::find($fileId);

        if (!$medicalFile) {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }

        $user = $request->user();

        // Check access permissions using policy
        if (!$user->can('generateSignedUrl', $medicalFile)) {
            return response()->json([
                'message' => 'You do not have permission to access this file'
            ], 403);
        }

        $downloadUrl = $this->generateSignedDownloadUrl($medicalFile);
        $expiresAt = Carbon::now()->addMinutes(10); // 10-minute TTL

        return response()->json([
            'download_url' => $downloadUrl,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * List user's accessible files
     *
     * @authenticated
     *
     * @queryParam category string optional Filter by category. Example: xray
     * @queryParam page integer optional Page number. Example: 1
     * @queryParam per_page integer optional Items per page. Example: 20
     */
    public function list(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = MedicalFile::accessibleBy($user)
            ->with('uploader')
            ->notExpired()
            ->orderBy('created_at', 'desc');

        if ($request->has('category')) {
            $query->byCategory($request->input('category'));
        }

        $files = $query->paginate($request->input('per_page', 20));

        return response()->json([
            'files' => $files->items(),
            'pagination' => [
                'current_page' => $files->currentPage(),
                'per_page' => $files->perPage(),
                'total' => $files->total(),
                'last_page' => $files->lastPage(),
            ]
        ]);
    }

    /**
     * Check if user can upload specific file category with access level
     */
    private function canUploadFileCategory(User $user, string $category, string $accessLevel): bool
    {
        // Admin can upload anything
        if ($user->hasRole('admin')) {
            return true;
        }

        // Category-specific permissions
        $categoryPermissions = [
            'xray' => ['doctor', 'clinic_manager'],
            'photo' => ['doctor', 'clinic_manager', 'patient'],
            'document' => ['doctor', 'clinic_manager', 'patient'],
            'report' => ['doctor', 'clinic_manager'],
            'treatment_plan' => ['doctor', 'clinic_manager'],
            'prescription' => ['doctor', 'clinic_manager'],
        ];

        $allowedRoles = $categoryPermissions[$category] ?? [];

        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                // Check access level permissions
                return $this->canSetAccessLevel($user, $accessLevel);
            }
        }

        return false;
    }

    /**
     * Check if user can set specific access level
     */
    private function canSetAccessLevel(User $user, string $accessLevel): bool
    {
        return match ($accessLevel) {
            'private' => true, // Anyone can set private
            'patient' => $user->hasRole(['doctor', 'clinic_manager', 'admin']),
            'doctor' => $user->hasRole(['doctor', 'clinic_manager', 'admin']),
            'clinic' => $user->hasRole(['clinic_manager', 'admin']),
            default => false,
        };
    }

    /**
     * Validate file format based on category
     */
    private function validateFileFormat($file, string $category): array
    {
        $allowedMimes = [
            'xray' => ['image/jpeg', 'image/png', 'application/pdf', 'application/dicom'],
            'photo' => ['image/jpeg', 'image/png', 'image/gif'],
            'document' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'report' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'treatment_plan' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'prescription' => ['application/pdf', 'image/jpeg', 'image/png'],
        ];

        $allowed = $allowedMimes[$category] ?? [];

        if (!in_array($file->getMimeType(), $allowed)) {
            return [
                'valid' => false,
                'error' => "File type {$file->getMimeType()} not allowed for category {$category}"
            ];
        }

        return ['valid' => true];
    }

    /**
     * Generate signed download URL with 10-minute TTL
     */
    private function generateSignedDownloadUrl(MedicalFile $medicalFile): string
    {
        return URL::temporarySignedRoute(
            'api.files.download',
            Carbon::now()->addMinutes(10), // 10-minute TTL as requested
            ['id' => $medicalFile->id]
        );
    }
}
