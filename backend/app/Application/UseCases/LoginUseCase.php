<?php

namespace App\Application\UseCases;

use App\Application\DTOs\LoginDTO;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;
use App\Domain\Repositories\UserRepositoryInterface;

class LoginUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(LoginDTO $dto): User
    {
        $email = new Email($dto->email);
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new \DomainException('Invalid credentials');
        }

        if (!$user->isActive()) {
            throw new \DomainException('Account is not active');
        }

        if (!password_verify($dto->password, $user->getPasswordHash())) {
            throw new \DomainException('Invalid credentials');
        }

        // Update last login
        $user->updateLastLogin();
        $this->userRepository->save($user);

        return $user;
    }
}
