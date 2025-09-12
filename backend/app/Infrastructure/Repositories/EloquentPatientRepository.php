<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Patient;
use App\Domain\ValueObjects\PatientId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Phone;
use App\Domain\Repositories\PatientRepositoryInterface;
use App\Models\Patient as EloquentPatient;

class EloquentPatientRepository implements PatientRepositoryInterface
{
    public function findById(PatientId $id): ?Patient
    {
        $eloquentPatient = EloquentPatient::find($id->getValue());

        if (!$eloquentPatient) {
            return null;
        }

        return $this->toDomainEntity($eloquentPatient);
    }

    public function findByUserId(UserId $userId): ?Patient
    {
        $eloquentPatient = EloquentPatient::where('user_id', $userId->getValue())->first();

        if (!$eloquentPatient) {
            return null;
        }

        return $this->toDomainEntity($eloquentPatient);
    }

    public function save(Patient $patient): void
    {
        $eloquentPatient = EloquentPatient::find($patient->getId()->getValue()) ?? new EloquentPatient();

        $eloquentPatient->user_id = $patient->getUserId()->getValue();
        $eloquentPatient->date_of_birth = $patient->getDateOfBirth()->format('Y-m-d');
        $eloquentPatient->gender = $patient->getGender();
        $eloquentPatient->emergency_contact_name = $patient->getEmergencyContactName();
        $eloquentPatient->emergency_contact_phone = $patient->getEmergencyContactPhone()->getValue();
        $eloquentPatient->consent_treatment = $patient->hasGivenTreatmentConsent();
        $eloquentPatient->consent_data_sharing = $patient->hasGivenDataSharingConsent();
        $eloquentPatient->medical_history = $patient->getMedicalHistory();
        $eloquentPatient->allergies = $patient->getAllergies();
        $eloquentPatient->current_medications = $patient->getCurrentMedications();
        $eloquentPatient->dental_history = $patient->getDentalHistory();
        $eloquentPatient->insurance_provider = $patient->getInsuranceProvider();
        $eloquentPatient->insurance_number = $patient->getInsuranceNumber();

        $eloquentPatient->save();

        // Update the domain entity with the new ID if it was just created
        if ($patient->getId()->getValue() === 0) {
            $reflection = new \ReflectionClass($patient);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($patient, new PatientId($eloquentPatient->id));
        }
    }

    public function delete(Patient $patient): void
    {
        EloquentPatient::destroy($patient->getId()->getValue());
    }

    public function exists(PatientId $id): bool
    {
        return EloquentPatient::where('id', $id->getValue())->exists();
    }

    public function getPatientsWithConsent(): array
    {
        $eloquentPatients = EloquentPatient::where('consent_treatment', true)
            ->where('consent_data_sharing', true)
            ->get();

        return $eloquentPatients->map(fn($patient) => $this->toDomainEntity($patient))->toArray();
    }

    public function searchByName(string $name): array
    {
        $eloquentPatients = EloquentPatient::whereHas('user', function ($query) use ($name) {
            $query->where('first_name', 'like', "%{$name}%")
                  ->orWhere('last_name', 'like', "%{$name}%");
        })->get();

        return $eloquentPatients->map(fn($patient) => $this->toDomainEntity($patient))->toArray();
    }

    public function getPatientsRegisteredAfter(\DateTimeImmutable $date): array
    {
        $eloquentPatients = EloquentPatient::where('created_at', '>', $date->format('Y-m-d H:i:s'))->get();

        return $eloquentPatients->map(fn($patient) => $this->toDomainEntity($patient))->toArray();
    }

    private function toDomainEntity(EloquentPatient $eloquentPatient): Patient
    {
        return new Patient(
            new PatientId($eloquentPatient->id),
            new UserId($eloquentPatient->user_id),
            new \DateTimeImmutable($eloquentPatient->date_of_birth),
            $eloquentPatient->gender,
            $eloquentPatient->emergency_contact_name,
            new Phone($eloquentPatient->emergency_contact_phone),
            $eloquentPatient->consent_treatment,
            $eloquentPatient->consent_data_sharing,
            $eloquentPatient->medical_history,
            $eloquentPatient->allergies,
            $eloquentPatient->current_medications,
            $eloquentPatient->dental_history,
            $eloquentPatient->insurance_provider,
            $eloquentPatient->insurance_number
        );
    }
}
