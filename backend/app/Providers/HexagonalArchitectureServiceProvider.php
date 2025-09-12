<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Domain Repository Interfaces
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\DoctorRepositoryInterface;
use App\Domain\Repositories\PatientRepositoryInterface;
use App\Domain\Repositories\ConsultationRepositoryInterface;
use App\Domain\Repositories\MessageRepositoryInterface;
use App\Domain\Repositories\ClinicRepositoryInterface;
use App\Domain\Repositories\TreatmentRequestRepositoryInterface;
use App\Domain\Repositories\ReviewRepositoryInterface;

// Infrastructure Repository Implementations
use App\Infrastructure\Repositories\EloquentUserRepository;
use App\Infrastructure\Repositories\EloquentDoctorRepository;
use App\Infrastructure\Repositories\EloquentPatientRepository;
use App\Infrastructure\Repositories\EloquentConsultationRepository;
use App\Infrastructure\Repositories\EloquentMessageRepository;
use App\Infrastructure\Repositories\EloquentClinicRepository;
use App\Infrastructure\Repositories\EloquentTreatmentRequestRepository;
use App\Infrastructure\Repositories\EloquentReviewRepository;

// Application Use Cases
use App\Application\UseCases\RegisterPatientUseCase;
use App\Application\UseCases\RegisterDoctorUseCase;
use App\Application\UseCases\LoginUseCase;
use App\Application\UseCases\CreateConsultationUseCase;
use App\Application\UseCases\SendMessageUseCase;

class HexagonalArchitectureServiceProvider extends ServiceProvider
{
    /**
     * All repository interface bindings
     */
    private const REPOSITORY_BINDINGS = [
        UserRepositoryInterface::class => EloquentUserRepository::class,
        DoctorRepositoryInterface::class => EloquentDoctorRepository::class,
        PatientRepositoryInterface::class => EloquentPatientRepository::class,
        ConsultationRepositoryInterface::class => EloquentConsultationRepository::class,
        MessageRepositoryInterface::class => EloquentMessageRepository::class,
        ClinicRepositoryInterface::class => EloquentClinicRepository::class,
        TreatmentRequestRepositoryInterface::class => EloquentTreatmentRequestRepository::class,
        ReviewRepositoryInterface::class => EloquentReviewRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind all repository interfaces to their implementations
        $this->registerRepositories();

        // Register all use cases
        $this->registerUseCases();

        // Register additional domain services if needed
        $this->registerDomainServices();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Log successful hexagonal architecture initialization
        if ($this->app->environment('local')) {
            \Log::info('Hexagonal Architecture initialized with repository bindings', [
                'repositories' => array_keys(self::REPOSITORY_BINDINGS),
                'timestamp' => now()
            ]);
        }
    }

    /**
     * Register repository interface bindings
     */
    private function registerRepositories(): void
    {
        foreach (self::REPOSITORY_BINDINGS as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Register use cases as singletons for better performance
     */
    private function registerUseCases(): void
    {
        // Authentication Use Cases
        $this->app->singleton(RegisterPatientUseCase::class, function ($app) {
            return new RegisterPatientUseCase(
                $app->make(UserRepositoryInterface::class),
                $app->make(PatientRepositoryInterface::class)
            );
        });

        $this->app->singleton(RegisterDoctorUseCase::class, function ($app) {
            return new RegisterDoctorUseCase(
                $app->make(UserRepositoryInterface::class),
                $app->make(DoctorRepositoryInterface::class)
            );
        });

        $this->app->singleton(LoginUseCase::class, function ($app) {
            return new LoginUseCase(
                $app->make(UserRepositoryInterface::class)
            );
        });

        // Consultation Use Cases
        $this->app->singleton(CreateConsultationUseCase::class, function ($app) {
            return new CreateConsultationUseCase(
                $app->make(ConsultationRepositoryInterface::class),
                $app->make(PatientRepositoryInterface::class),
                $app->make(DoctorRepositoryInterface::class)
            );
        });

        // Messaging Use Cases
        $this->app->singleton(SendMessageUseCase::class, function ($app) {
            return new SendMessageUseCase(
                $app->make(MessageRepositoryInterface::class),
                $app->make(ConsultationRepositoryInterface::class)
            );
        });
    }

    /**
     * Register additional domain services
     */
    private function registerDomainServices(): void
    {
        // Domain services can be registered here as they are added
        // Example: Email notification service, SMS service, etc.

        // Future: Event bus for domain events
        // Future: Specification services for complex queries
        // Future: Domain event handlers
    }
}
