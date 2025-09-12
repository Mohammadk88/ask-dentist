<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\TreatmentRequest;
use App\Domain\ValueObjects\TreatmentRequestId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\CaseType;

interface TreatmentRequestRepositoryInterface
{
    public function findById(TreatmentRequestId $id): ?TreatmentRequest;

    public function findByPatientId(UserId $patientId): array;

    public function findByCaseType(CaseType $caseType): array;

    public function findByStatus(string $status): array;

    public function findPendingRequests(): array;

    public function save(TreatmentRequest $request): TreatmentRequest;

    public function delete(TreatmentRequestId $id): bool;

    public function getRecentRequests(int $limit = 10): array;

    public function getPatientRequestHistory(UserId $patientId, int $limit = 20): array;
}
