<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Clinic;
use App\Domain\Repositories\ClinicRepositoryInterface;
use App\Domain\ValueObjects\ClinicId;
use App\Domain\ValueObjects\Money;
use App\Models\Clinic as EloquentClinic;

class EloquentClinicRepository implements ClinicRepositoryInterface
{
    public function findById(ClinicId $id): ?Clinic
    {
        $eloquentClinic = EloquentClinic::find($id->value);

        if (!$eloquentClinic) {
            return null;
        }

        return $this->toDomainEntity($eloquentClinic);
    }

    public function findByName(string $name): ?Clinic
    {
        $eloquentClinic = EloquentClinic::where('name', $name)->first();

        if (!$eloquentClinic) {
            return null;
        }

        return $this->toDomainEntity($eloquentClinic);
    }

    public function findByCountryAndCity(string $country, string $city): array
    {
        $eloquentClinics = EloquentClinic::byCountry($country)
            ->byCity($city)
            ->get();

        return $eloquentClinics->map(fn($clinic) => $this->toDomainEntity($clinic))->toArray();
    }

    public function save(Clinic $clinic): Clinic
    {
        $eloquentClinic = $clinic->getId()->value > 0
            ? EloquentClinic::find($clinic->getId()->value)
            : new EloquentClinic();

        $eloquentClinic->fill([
            'name' => $clinic->getName(),
            'country' => $clinic->getCountry(),
            'city' => $clinic->getCity(),
            'address' => $clinic->getAddress(),
            'documents_json' => $clinic->getDocuments(),
            'commission_rate' => $clinic->getCommissionRate()->getAmount(),
        ]);

        $eloquentClinic->save();

        return $this->toDomainEntity($eloquentClinic);
    }

    public function delete(ClinicId $id): bool
    {
        $eloquentClinic = EloquentClinic::find($id->value);

        if (!$eloquentClinic) {
            return false;
        }

        return $eloquentClinic->delete();
    }

    public function getAllActive(): array
    {
        $eloquentClinics = EloquentClinic::all();

        return $eloquentClinics->map(fn($clinic) => $this->toDomainEntity($clinic))->toArray();
    }

    public function getWithHighCommissionRate(float $minRate): array
    {
        $eloquentClinics = EloquentClinic::withHighCommission($minRate)->get();

        return $eloquentClinics->map(fn($clinic) => $this->toDomainEntity($clinic))->toArray();
    }

    private function toDomainEntity(EloquentClinic $eloquentClinic): Clinic
    {
        return new Clinic(
            id: new ClinicId($eloquentClinic->id),
            name: $eloquentClinic->name,
            country: $eloquentClinic->country,
            city: $eloquentClinic->city,
            address: $eloquentClinic->address,
            documents: $eloquentClinic->documents_json,
            commissionRate: new Money($eloquentClinic->commission_rate, 'USD'),
            createdAt: $eloquentClinic->created_at?->toDateTime(),
            updatedAt: $eloquentClinic->updated_at?->toDateTime()
        );
    }
}
