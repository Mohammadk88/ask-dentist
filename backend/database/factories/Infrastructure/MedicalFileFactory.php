<?php

namespace Database\Factories\Infrastructure;

use App\Infrastructure\Models\MedicalFile;
use App\Infrastructure\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\MedicalFile>
 */
class MedicalFileFactory extends Factory
{
    protected $model = MedicalFile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            MedicalFile::CATEGORY_XRAY,
            MedicalFile::CATEGORY_PHOTO,
            MedicalFile::CATEGORY_DOCUMENT,
            MedicalFile::CATEGORY_REPORT,
            MedicalFile::CATEGORY_TREATMENT_PLAN,
            MedicalFile::CATEGORY_PRESCRIPTION,
        ];

        $accessLevels = [
            MedicalFile::ACCESS_PRIVATE,
            MedicalFile::ACCESS_CLINIC,
            MedicalFile::ACCESS_DOCTOR,
            MedicalFile::ACCESS_PATIENT,
        ];

        $fileExtensions = [
            'pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'
        ];

        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        $extension = $this->faker->randomElement($fileExtensions);
        $originalName = $this->faker->slug(3) . '.' . $extension;
        $secureFilename = MedicalFile::generateSecureFilename($originalName);

        return [
            'original_name' => $originalName,
            'filename' => basename($secureFilename),
            'file_path' => $secureFilename,
            'file_size' => $this->faker->numberBetween(1024, 10485760), // 1KB to 10MB
            'mime_type' => $mimeTypes[$extension],
            'file_hash' => hash('sha256', $this->faker->text(1000)),
            'uploaded_by' => User::factory(),
            'related_to_type' => null,
            'related_to_id' => null,
            'file_category' => $this->faker->randomElement($categories),
            'access_level' => $this->faker->randomElement($accessLevels),
            'virus_scan_status' => MedicalFile::SCAN_CLEAN,
            'virus_scan_result' => null,
            'expiry_date' => null,
            'metadata' => [
                'upload_ip' => $this->faker->ipv4,
                'user_agent' => $this->faker->userAgent,
                'original_size' => $this->faker->numberBetween(1024, 10485760),
            ],
        ];
    }

    /**
     * Indicate that the file is an X-ray.
     */
    public function xray(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_category' => MedicalFile::CATEGORY_XRAY,
            'original_name' => 'xray_' . $this->faker->slug(2) . '.jpg',
            'mime_type' => 'image/jpeg',
        ]);
    }

    /**
     * Indicate that the file is a document.
     */
    public function document(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_category' => MedicalFile::CATEGORY_DOCUMENT,
            'original_name' => 'document_' . $this->faker->slug(2) . '.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }

    /**
     * Indicate that the file is a treatment plan.
     */
    public function treatmentPlan(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_category' => MedicalFile::CATEGORY_TREATMENT_PLAN,
            'original_name' => 'treatment_plan_' . $this->faker->slug(2) . '.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }

    /**
     * Indicate that the file has private access.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'access_level' => MedicalFile::ACCESS_PRIVATE,
        ]);
    }

    /**
     * Indicate that the file has clinic access.
     */
    public function clinic(): static
    {
        return $this->state(fn (array $attributes) => [
            'access_level' => MedicalFile::ACCESS_CLINIC,
        ]);
    }

    /**
     * Indicate that the file has doctor access.
     */
    public function doctor(): static
    {
        return $this->state(fn (array $attributes) => [
            'access_level' => MedicalFile::ACCESS_DOCTOR,
        ]);
    }

    /**
     * Indicate that the file has patient access.
     */
    public function patient(): static
    {
        return $this->state(fn (array $attributes) => [
            'access_level' => MedicalFile::ACCESS_PATIENT,
        ]);
    }

    /**
     * Indicate that the file is virus scanned and clean.
     */
    public function clean(): static
    {
        return $this->state(fn (array $attributes) => [
            'virus_scan_status' => MedicalFile::SCAN_CLEAN,
            'virus_scan_result' => null,
        ]);
    }

    /**
     * Indicate that the file is infected.
     */
    public function infected(): static
    {
        return $this->state(fn (array $attributes) => [
            'virus_scan_status' => MedicalFile::SCAN_INFECTED,
            'virus_scan_result' => 'Malware detected: ' . $this->faker->word,
        ]);
    }

    /**
     * Indicate that the virus scan is pending.
     */
    public function scanPending(): static
    {
        return $this->state(fn (array $attributes) => [
            'virus_scan_status' => MedicalFile::SCAN_PENDING,
            'virus_scan_result' => null,
        ]);
    }

    /**
     * Indicate that the file is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => $this->faker->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }

    /**
     * Indicate that the file has an expiry date in the future.
     */
    public function withExpiry(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => $this->faker->dateTimeBetween('+1 day', '+1 year'),
        ]);
    }

    /**
     * Configure the factory to use a specific uploader.
     */
    public function uploadedBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'uploaded_by' => $user->id,
        ]);
    }

    /**
     * Configure the factory to relate to a specific model.
     */
    public function relatedTo(string $modelType, string $modelId): static
    {
        return $this->state(fn (array $attributes) => [
            'related_to_type' => $modelType,
            'related_to_id' => $modelId,
        ]);
    }
}