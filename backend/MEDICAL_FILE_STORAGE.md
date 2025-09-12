# Medical File Storage System

## Overview
This system provides secure medical file storage with role-based access control, signed temporary URLs, and virus scanning capabilities.

## Features

### 🔐 Security Features
- **Signed Temporary URLs**: 10-minute TTL for secure file downloads
- **Role-Based Access Control**: Granular permissions based on user roles
- **Virus Scanning**: Automated scanning with quarantine for infected files
- **Audit Logging**: Comprehensive logging of file operations
- **File Validation**: MIME type validation and security checks

### 📁 Storage Configuration
- **Medical Files**: `storage/app/medical/` with private access
- **Quarantine**: `storage/app/medical/quarantine/` for infected files
- **Temporary**: `storage/app/temp/` for processing
- **Randomized Names**: Secure filename generation with date-based structure

### 🎯 File Categories
- `xray` - X-Ray images and DICOM files
- `photo` - Clinical photos and images
- `document` - Medical documents and reports
- `report` - Test results and clinical reports
- `treatment_plan` - Treatment planning documents
- `prescription` - Prescription files

### 🔑 Access Levels
- `private` - Owner only
- `patient` - Patient and authorized medical staff
- `doctor` - Doctor and clinic manager access
- `clinic` - Full clinic team access

## API Endpoints

### File Upload
```http
POST /api/files/upload
Content-Type: multipart/form-data
Authorization: Bearer {token}

Body:
- file: (file) Medical file to upload
- category: (string) File category (xray, photo, document, etc.)
- access_level: (string) Access level (private, clinic, doctor, patient)
- related_to_type: (string, optional) Related model type
- related_to_id: (uuid, optional) Related model ID
- expiry_days: (integer, optional) Days until expiration
```

### File Download (Signed URL)
```http
GET /api/files/{id}/download?signature={signature}&expires={timestamp}
Authorization: Bearer {token}
```

### Get Signed URL
```http
GET /api/files/{id}/signed-url
Authorization: Bearer {token}
```

### List Files
```http
GET /api/files?category=xray&page=1&per_page=20
Authorization: Bearer {token}
```

## Role-Based Permissions

### Admin
- Can upload any file category
- Can set any access level
- Can access all files
- Can manage quarantined files

### Clinic Manager
- Can upload: xray, photo, document, report, treatment_plan, prescription
- Can set access levels: private, patient, doctor, clinic
- Can access clinic files and files within their clinic
- Can manage clinic file policies

### Doctor
- Can upload: xray, photo, document, report, treatment_plan, prescription
- Can set access levels: private, patient, doctor
- Can access clinic and doctor level files within their clinic
- Can access patient files for their cases

### Patient
- Can upload: photo, document
- Can set access levels: private, patient
- Can access their own files and files shared with them
- Cannot access other patients' files

## Security Implementation

### File Validation
- MIME type validation based on category
- File size limits (50MB for medical files)
- Executable file detection and blocking
- Extension validation against MIME type

### Virus Scanning
```php
// Automatic virus scanning after upload
\App\Jobs\VirusScanJob::dispatch($medicalFile);

// Scan statuses: pending, scanning, clean, infected, failed
$file->virus_scan_status === MedicalFile::SCAN_CLEAN;
```

### Audit Logging
All file operations are automatically logged:
- File uploads with metadata
- File downloads with user information
- Signed URL generation
- File access attempts
- Security violations

### Signed URLs
```php
// Generate 10-minute signed URL
$downloadUrl = URL::temporarySignedRoute(
    'api.files.download',
    Carbon::now()->addMinutes(10),
    ['id' => $medicalFile->id]
);
```

## Storage Structure
```
storage/app/
├── medical/
│   ├── 2025/01/01/
│   │   ├── abc123def456.pdf
│   │   └── xyz789ghi012.jpg
│   └── quarantine/
│       └── infected_file.exe
├── temp/
│   └── processing/
└── private/
    └── other_files/
```

## Database Schema

### medical_files table
- `id` (UUID) - Primary key
- `original_name` - Original filename
- `filename` - Secure filename
- `file_path` - Storage path
- `file_size` - File size in bytes
- `mime_type` - MIME type
- `file_hash` - SHA-256 hash for deduplication
- `uploaded_by` - User who uploaded
- `related_to_type/id` - Polymorphic relationship
- `file_category` - File category enum
- `access_level` - Access level enum
- `virus_scan_status` - Scan status enum
- `virus_scan_result` - Scan result details
- `expiry_date` - File expiration
- `metadata` - JSON metadata
- `created_at/updated_at` - Timestamps
- `deleted_at` - Soft delete

## Configuration

### Environment Variables
```env
# Storage configuration
FILESYSTEM_DISK=local

# Queue configuration for virus scanning
QUEUE_CONNECTION=redis
```

### File Validation Rules
```php
// Max file sizes by category
'xray' => '50MB',
'photo' => '10MB',
'document' => '25MB',
'report' => '25MB',
'treatment_plan' => '25MB',
'prescription' => '10MB'
```

## Future Enhancements

### Virus Scanning Integration
- ClamAV local scanning
- VirusTotal API integration
- Amazon S3 Malware Detection
- Custom ML-based scanning

### Advanced Features
- File versioning system
- Automatic file archiving
- Advanced search capabilities
- Bulk operations
- File sharing with external parties
- Integration with DICOM viewers

### Compliance Features
- HIPAA compliance logging
- Data retention policies
- Automated data purging
- Encryption at rest
- Geographic data residency

## Testing

### Unit Tests
```bash
php artisan test --filter=MedicalFileTest
php artisan test --filter=FileControllerTest
php artisan test --filter=VirusScanJobTest
```

### Security Tests
```bash
# Test file upload with various file types
# Test access control violations
# Test signed URL expiration
# Test virus scanning workflow
```

## Monitoring

### Key Metrics
- File upload success rate
- Download latency
- Virus scan completion time
- Storage usage by category
- Access control violations

### Alerts
- Failed virus scans
- Infected file uploads
- Unusual access patterns
- Storage quota warnings
- Security violations

This medical file storage system provides enterprise-grade security and compliance for healthcare applications while maintaining ease of use for medical professionals.
