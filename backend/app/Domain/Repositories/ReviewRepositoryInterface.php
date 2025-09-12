<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Review;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\ClinicId;
use App\Domain\ValueObjects\Rating;

interface ReviewRepositoryInterface
{
    public function findById(int $id): ?Review;

    public function findByPatientId(UserId $patientId): array;

    public function findByDoctorId(UserId $doctorId): array;

    public function findByClinicId(ClinicId $clinicId): array;

    public function findByPatientAndDoctor(UserId $patientId, UserId $doctorId): ?Review;

    public function save(Review $review): Review;

    public function delete(int $id): bool;

    public function getPublishedReviews(): array;

    public function getDoctorAverageRating(UserId $doctorId): ?Rating;

    public function getClinicAverageRating(ClinicId $clinicId): ?Rating;

    public function getRecentReviews(int $limit = 10): array;
}
