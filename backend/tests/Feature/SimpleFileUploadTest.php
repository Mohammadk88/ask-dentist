<?php

namespace Tests\Feature;

use App\Infrastructure\Models\User;
use App\Infrastructure\Models\MedicalFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SimpleFileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('medical');
    }

    /** @test */
    public function it_can_upload_a_medical_file_successfully()
    {
        // Seed roles first
        $this->seed(\Database\Seeders\RoleSeeder::class);
        
        $doctor = User::factory()->doctor()->create();
        $doctor->assignRole('Doctor');
        
        $file = UploadedFile::fake()->image('xray.jpg', 1000, 1000);

        $response = $this->actingAs($doctor, 'api')
            ->postJson('/api/files/upload', [
                'file' => $file,
                'category' => 'xray',
                'access_level' => 'doctor', // Doctors can set doctor access level
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'file' => [
                'id',
                'original_name', 
                'file_category',
                'access_level',
                'file_size',
                'formatted_size',
                'virus_scan_status',
                'download_url',
                'uploaded_at',
            ]
        ]);

        // Verify file was stored
        $medicalFile = MedicalFile::first();
        $this->assertNotNull($medicalFile);
        $this->assertEquals('xray.jpg', $medicalFile->original_name);
        $this->assertEquals($doctor->id, $medicalFile->uploaded_by);
        $this->assertEquals('doctor', $medicalFile->access_level);
        
        // Verify file exists in storage
        Storage::disk('medical')->assertExists($medicalFile->file_path);
    }

    /** @test */
    public function it_prevents_unauthorized_users_from_uploading_files()
    {
        $file = UploadedFile::fake()->image('xray.jpg', 1000, 1000);

        $response = $this->postJson('/api/files/upload', [
            'file' => $file,
            'category' => 'xray',
            'access_level' => 'clinic',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        // Seed roles first
        $this->seed(\Database\Seeders\RoleSeeder::class);
        
        $doctor = User::factory()->doctor()->create();
        $doctor->assignRole('Doctor');

        $response = $this->actingAs($doctor, 'api')
            ->postJson('/api/files/upload', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['file', 'category', 'access_level']);
    }

    /** @test */
    public function it_can_generate_signed_download_url()
    {
        // Seed roles first
        $this->seed(\Database\Seeders\RoleSeeder::class);
        
        $doctor = User::factory()->doctor()->create();
        $doctor->assignRole('Doctor');
        
        $medicalFile = MedicalFile::factory()->create([
            'uploaded_by' => $doctor->id,
            'file_category' => 'xray',
            'access_level' => 'doctor',
        ]);

        $response = $this->actingAs($doctor, 'api')
            ->getJson("/api/files/{$medicalFile->id}/signed-url");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'download_url',
            'expires_at'
        ]);

        $responseData = $response->json();
        $this->assertStringContainsString('signature', $responseData['download_url']);
    }
}