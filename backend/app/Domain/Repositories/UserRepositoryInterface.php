<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Email;

interface UserRepositoryInterface
{
    public function findById(UserId $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function save(User $user): void;

    public function delete(User $user): void;

    public function exists(UserId $id): bool;

    public function emailExists(Email $email): bool;

    public function getActiveUsers(): array;

    public function getUsersRegisteredAfter(\DateTimeImmutable $date): array;

    public function searchByName(string $name): array;
}
