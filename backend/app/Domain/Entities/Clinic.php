<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ClinicId;
use App\Domain\ValueObjects\Money;

class Clinic
{
    public function __construct(
        private ClinicId $id,
        private string $name,
        private string $country,
        private string $city,
        private string $address,
        private ?array $documents = null,
        private Money $commissionRate = new Money(0.1000, 'USD'),
        private ?\DateTime $createdAt = null,
        private ?\DateTime $updatedAt = null
    ) {
        $this->validateCountry();
        $this->validateName();
    }

    public static function create(
        string $name,
        string $country,
        string $city,
        string $address,
        ?array $documents = null,
        ?Money $commissionRate = null
    ): self {
        return new self(
            id: new ClinicId(0), // Will be set by repository
            name: $name,
            country: $country,
            city: $city,
            address: $address,
            documents: $documents,
            commissionRate: $commissionRate ?? new Money(0.1000, 'USD'),
            createdAt: new \DateTime(),
            updatedAt: new \DateTime()
        );
    }

    // Getters
    public function getId(): ClinicId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getDocuments(): ?array
    {
        return $this->documents;
    }

    public function getCommissionRate(): Money
    {
        return $this->commissionRate;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    // Business methods
    public function updateDetails(string $name, string $city, string $address): void
    {
        $this->name = $name;
        $this->city = $city;
        $this->address = $address;
        $this->updatedAt = new \DateTime();
    }

    public function updateCommissionRate(Money $commissionRate): void
    {
        $this->commissionRate = $commissionRate;
        $this->updatedAt = new \DateTime();
    }

    public function addDocument(array $document): void
    {
        $this->documents = $this->documents ?? [];
        $this->documents[] = $document;
        $this->updatedAt = new \DateTime();
    }

    // Validation
    private function validateCountry(): void
    {
        if (strlen($this->country) !== 2) {
            throw new \InvalidArgumentException('Country must be a 2-letter ISO code');
        }
    }

    private function validateName(): void
    {
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Clinic name cannot be empty');
        }
    }
}
