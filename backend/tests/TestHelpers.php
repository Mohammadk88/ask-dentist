<?php

namespace Tests;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\TreatmentRequest;

class TestHelpers
{
    /**
     * Create a clinic with a clinic manager
     *
     * @return array [Clinic, User]
     */
    public static function seedClinicWithManager(): array
    {
        $unique = uniqid();

        $clinic = Clinic::factory()->create([
            'name' => 'Test Clinic ' . $unique,
            'email' => 'clinic.' . $unique . '@test.local',
            'verified_at' => now(),
        ]);

        $manager = User::factory()->clinicManager()->create([
            'name' => 'Test Clinic Manager ' . $unique,
            'email' => 'manager.' . $unique . '@test.local',
        ]);

        return [$clinic, $manager];
    }

    /**
     * Create a doctor with user account and link to a clinic
     *
     * @return Doctor
     */
    public static function seedDoctor(): Doctor
    {
        $unique = uniqid();

        $user = User::factory()->doctor()->create([
            'name' => 'Dr. Test Doctor ' . $unique,
            'email' => 'doctor.' . $unique . '@test.local',
        ]);

        $clinic = Clinic::factory()->create([
            'name' => 'Test Doctor Clinic ' . $unique,
            'verified_at' => now(),
        ]);

        $doctor = Doctor::factory()->create([
            'user_id' => $user->id,
            'license_number' => 'TEST' . $unique,
            'specialty' => 'general',
            'verified_at' => now(),
        ]);

        // Link doctor to clinic
        $doctor->clinics()->attach($clinic->id, [
            'id' => \Illuminate\Support\Str::uuid(),
            'role' => 'associate',
            'started_at' => now(),
        ]);

        return $doctor;
    }

    /**
     * Create a patient user
     *
     * @return User
     */
    public static function seedPatient(): User
    {
        $unique = uniqid();

        return User::factory()->patient()->create([
            'name' => 'Test Patient ' . $unique,
            'email' => 'patient.' . $unique . '@test.local',
        ]);
    }

    /**
     * Create an admin user
     *
     * @return User
     */
    public static function admin(): User
    {
        $unique = uniqid();

        return User::factory()->admin()->create([
            'name' => 'Test Admin ' . $unique,
            'email' => 'admin.' . $unique . '@test.local',
        ]);
    }

    /**
     * Create a doctor with a treatment request case for testing
     *
     * @return object Context with doctor user and treatment request
     */
    public static function seedDoctorWithRequestCase(): object
    {
        $doctor = self::seedDoctor();
        $patient = Patient::factory()->create([
            'user_id' => self::seedPatient()->id,
        ]);

        $request = TreatmentRequest::factory()->accepted()->create([
            'patient_id' => $patient->user_id,
            'title' => 'Dental Pain Treatment',
            'description' => 'Test treatment request for browser testing',
            'status' => 'accepted',
        ]);

        return (object) [
            'doctor_user' => $doctor->user,
            'doctor' => $doctor,
            'patient' => $patient,
            'request' => $request,
        ];
    }
}
