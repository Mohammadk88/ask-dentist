<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\User;
use App\Domain\ValueObjects\UserId;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Phone;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Models\User as EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(UserId $id): ?User
    {
        $eloquentUser = EloquentUser::find($id->getValue());

        if (!$eloquentUser) {
            return null;
        }

        return $this->toDomainEntity($eloquentUser);
    }

    public function findByEmail(Email $email): ?User
    {
        $eloquentUser = EloquentUser::where('email', $email->getValue())->first();

        if (!$eloquentUser) {
            return null;
        }

        return $this->toDomainEntity($eloquentUser);
    }

    public function save(User $user): void
    {
        $eloquentUser = EloquentUser::find($user->getId()->getValue()) ?? new EloquentUser();

        $eloquentUser->first_name = $user->getFirstName();
        $eloquentUser->last_name = $user->getLastName();
        $eloquentUser->email = $user->getEmail()->getValue();
        $eloquentUser->phone = $user->getPhone()->getValue();
        $eloquentUser->password = $user->getPasswordHash();
        $eloquentUser->role = $user->getRole();
        $eloquentUser->is_active = $user->isActive();
        $eloquentUser->last_login_at = $user->getLastLoginAt();
        $eloquentUser->email_verified_at = $user->getEmailVerifiedAt();

        $eloquentUser->save();

        // Update the domain entity with the new ID if it was just created
        if ($user->getId()->getValue() === 0) {
            $reflection = new \ReflectionClass($user);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($user, new UserId($eloquentUser->id));
        }
    }

    public function delete(User $user): void
    {
        EloquentUser::destroy($user->getId()->getValue());
    }

    public function exists(UserId $id): bool
    {
        return EloquentUser::where('id', $id->getValue())->exists();
    }

    public function emailExists(Email $email): bool
    {
        return EloquentUser::where('email', $email->getValue())->exists();
    }

    public function getActiveUsers(): array
    {
        $eloquentUsers = EloquentUser::where('is_active', true)->get();

        return $eloquentUsers->map(fn($user) => $this->toDomainEntity($user))->toArray();
    }

    public function getUsersRegisteredAfter(\DateTimeImmutable $date): array
    {
        $eloquentUsers = EloquentUser::where('created_at', '>', $date->format('Y-m-d H:i:s'))->get();

        return $eloquentUsers->map(fn($user) => $this->toDomainEntity($user))->toArray();
    }

    public function searchByName(string $name): array
    {
        $eloquentUsers = EloquentUser::where('first_name', 'like', "%{$name}%")
            ->orWhere('last_name', 'like', "%{$name}%")
            ->get();

        return $eloquentUsers->map(fn($user) => $this->toDomainEntity($user))->toArray();
    }

    private function toDomainEntity(EloquentUser $eloquentUser): User
    {
        return new User(
            new UserId($eloquentUser->id),
            $eloquentUser->first_name,
            $eloquentUser->last_name,
            new Email($eloquentUser->email),
            new Phone($eloquentUser->phone),
            $eloquentUser->password,
            $eloquentUser->role,
            $eloquentUser->is_active,
            $eloquentUser->last_login_at ? new \DateTimeImmutable($eloquentUser->last_login_at) : null,
            $eloquentUser->email_verified_at ? new \DateTimeImmutable($eloquentUser->email_verified_at) : null,
            $eloquentUser->profile_picture
        );
    }
}
