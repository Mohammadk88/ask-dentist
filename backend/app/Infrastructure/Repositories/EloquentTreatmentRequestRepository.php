<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\TreatmentRequest;
use App\Domain\Repositories\TreatmentRequestRepositoryInterface;
use App\Domain\ValueObjects\TreatmentRequestId;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\CaseType;
use App\Models\TreatmentRequest as EloquentTreatmentRequest;

class EloquentTreatmentRequestRepository implements TreatmentRequestRepositoryInterface
{
    public function findById(TreatmentRequestId $id): ?TreatmentRequest
    {
        $eloquentRequest = EloquentTreatmentRequest::find($id->value);

        if (!$eloquentRequest) {
            return null;
        }

        return $this->toDomainEntity($eloquentRequest);
    }

    public function findByPatientId(UserId $patientId): array
    {
        $eloquentRequests = EloquentTreatmentRequest::where('patient_id', $patientId->value)->get();

        return $eloquentRequests->map(fn($request) => $this->toDomainEntity($request))->toArray();
    }

    public function findByCaseType(CaseType $caseType): array
    {
        $eloquentRequests = EloquentTreatmentRequest::byCaseType($caseType->value)->get();

        return $eloquentRequests->map(fn($request) => $this->toDomainEntity($request))->toArray();
    }

    public function findByStatus(string $status): array
    {
        $eloquentRequests = EloquentTreatmentRequest::byStatus($status)->get();

        return $eloquentRequests->map(fn($request) => $this->toDomainEntity($request))->toArray();
    }

    public function findPendingRequests(): array
    {
        $eloquentRequests = EloquentTreatmentRequest::pending()->get();

        return $eloquentRequests->map(fn($request) => $this->toDomainEntity($request))->toArray();
    }

    public function save(TreatmentRequest $request): TreatmentRequest
    {
        $eloquentRequest = $request->getId()->value > 0
            ? EloquentTreatmentRequest::find($request->getId()->value)
            : new EloquentTreatmentRequest();

        $eloquentRequest->fill([
            'patient_id' => $request->getPatientId()->value,
            'case_type' => $request->getCaseType()->value,
            'message' => $request->getMessage(),
            'images_json' => $request->getImages(),
            'status' => $request->getStatus(),
        ]);

        $eloquentRequest->save();

        return $this->toDomainEntity($eloquentRequest);
    }

    public function delete(TreatmentRequestId $id): bool
    {
        $eloquentRequest = EloquentTreatmentRequest::find($id->value);

        if (!$eloquentRequest) {
            return false;
        }

        return $eloquentRequest->delete();
    }

    public function getRecentRequests(int $limit = 10): array
    {
        $eloquentRequests = EloquentTreatmentRequest::recent()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $eloquentRequests->map(fn($request) => $this->toDomainEntity($request))->toArray();
    }

    public function getPatientRequestHistory(UserId $patientId, int $limit = 20): array
    {
        $eloquentRequests = EloquentTreatmentRequest::where('patient_id', $patientId->value)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $eloquentRequests->map(fn($request) => $this->toDomainEntity($request))->toArray();
    }

    private function toDomainEntity(EloquentTreatmentRequest $eloquentRequest): TreatmentRequest
    {
        return new TreatmentRequest(
            id: new TreatmentRequestId($eloquentRequest->id),
            patientId: new UserId($eloquentRequest->patient_id),
            caseType: CaseType::from($eloquentRequest->case_type),
            message: $eloquentRequest->message,
            images: $eloquentRequest->images_json,
            status: $eloquentRequest->status,
            createdAt: $eloquentRequest->created_at?->toDateTime(),
            updatedAt: $eloquentRequest->updated_at?->toDateTime()
        );
    }
}
