# Hexagonal Architecture Implementation

This document explains the hexagonal architecture implementation in the Ask Dentist MVP project, demonstrating clean separation of concerns and dependency injection.

## Architecture Overview

The hexagonal architecture (also known as Ports and Adapters pattern) separates the application into three main layers:

### 1. Domain Layer (`app/Domain/`)
- **Entities**: Core business objects with business logic
- **Value Objects**: Immutable objects representing concepts like IDs, Email, Phone, Money
- **Repository Interfaces**: Contracts defining data access operations

### 2. Application Layer (`app/Application/`)
- **Use Cases**: Business workflows and application services
- **DTOs**: Data Transfer Objects for input/output

### 3. Infrastructure Layer (`app/Infrastructure/`)
- **Repository Implementations**: Concrete implementations using Eloquent
- **Adapters**: External service integrations

## Directory Structure

```
app/
├── Domain/
│   ├── Entities/
│   │   ├── User.php
│   │   ├── Doctor.php
│   │   ├── Patient.php
│   │   ├── Consultation.php
│   │   └── Message.php
│   ├── ValueObjects/
│   │   ├── UserId.php
│   │   ├── DoctorId.php
│   │   ├── PatientId.php
│   │   ├── ConsultationId.php
│   │   ├── MessageId.php
│   │   ├── SpecializationId.php
│   │   ├── Email.php
│   │   ├── Phone.php
│   │   └── Money.php
│   └── Repositories/
│       ├── UserRepositoryInterface.php
│       ├── DoctorRepositoryInterface.php
│       ├── PatientRepositoryInterface.php
│       ├── ConsultationRepositoryInterface.php
│       └── MessageRepositoryInterface.php
├── Application/
│   ├── UseCases/
│   │   ├── RegisterPatientUseCase.php
│   │   ├── RegisterDoctorUseCase.php
│   │   ├── LoginUseCase.php
│   │   ├── CreateConsultationUseCase.php
│   │   └── SendMessageUseCase.php
│   └── DTOs/
│       ├── RegisterPatientDTO.php
│       ├── RegisterDoctorDTO.php
│       ├── LoginDTO.php
│       ├── CreateConsultationDTO.php
│       └── SendMessageDTO.php
└── Infrastructure/
    └── Repositories/
        ├── EloquentUserRepository.php
        ├── EloquentDoctorRepository.php
        ├── EloquentPatientRepository.php
        ├── EloquentConsultationRepository.php
        └── EloquentMessageRepository.php
```

## Key Benefits

### 1. **Clean Separation of Concerns**
- Domain logic is isolated from infrastructure concerns
- Business rules are centralized in entities
- Data access is abstracted through interfaces

### 2. **Testability**
- Easy to mock repository interfaces for unit testing
- Business logic can be tested without database dependencies
- Use cases can be tested in isolation

### 3. **Flexibility**
- Easy to swap implementations (e.g., from Eloquent to MongoDB)
- Database-agnostic domain layer
- Infrastructure changes don't affect business logic

### 4. **Maintainability**
- Clear boundaries between layers
- Single responsibility principle enforced
- Easy to locate and modify specific functionality

## Domain Entities

### User Entity
```php
// Core user business logic
$user = new User($id, $firstName, $lastName, $email, $phone, $passwordHash, $role);
$user->activate();
$user->updateLastLogin();
$user->verifyEmail();
```

### Doctor Entity
```php
// Doctor-specific business logic
$doctor = new Doctor($id, $userId, $specializationId, $licenseNumber, $experience, $fee);
$doctor->verify();
$doctor->setAvailability(true);
$doctor->updateConsultationFee(new Money(200, 'USD'));
```

### Patient Entity
```php
// Patient-specific business logic
$patient = new Patient($id, $userId, $dateOfBirth, $gender, ...);
$patient->updateConsent(true, true);
$patient->canCreateConsultations(); // Business rule check
```

## Value Objects

Value objects ensure type safety and encapsulate validation:

### Email Value Object
```php
$email = new Email('user@example.com'); // Validates email format
$domain = $email->getDomain(); // Returns 'example.com'
```

### Money Value Object
```php
$fee = new Money(150.00, 'USD');
$total = $fee->add(new Money(50.00, 'USD')); // Type-safe operations
$formatted = $fee->format(); // Returns '150.00 USD'
```

### Phone Value Object
```php
$phone = new Phone('+1234567890');
$formatted = $phone->format(); // Returns '+1 (234) 567-8900'
```

## Repository Pattern

Repositories abstract data access and provide a clean interface:

```php
interface UserRepositoryInterface
{
    public function findById(UserId $id): ?User;
    public function findByEmail(Email $email): ?User;
    public function save(User $user): void;
    public function emailExists(Email $email): bool;
}
```

## Use Cases

Use cases orchestrate business workflows:

```php
class RegisterPatientUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PatientRepositoryInterface $patientRepository
    ) {}

    public function execute(RegisterPatientDTO $dto): array
    {
        // Business logic implementation
        // 1. Validate email doesn't exist
        // 2. Create User entity
        // 3. Create Patient entity
        // 4. Save both entities
    }
}
```

## Dependency Injection

The `HexagonalArchitectureServiceProvider` binds interfaces to implementations:

```php
// Repository bindings
$this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

// Use case registrations
$this->app->singleton(RegisterPatientUseCase::class, function ($app) {
    return new RegisterPatientUseCase(
        $app->make(UserRepositoryInterface::class),
        $app->make(PatientRepositoryInterface::class)
    );
});
```

## Controller Implementation

Controllers are thin and delegate to use cases:

```php
class HexagonalAuthController extends Controller
{
    public function registerPatient(Request $request, RegisterPatientUseCase $useCase): JsonResponse
    {
        // 1. Validate input
        // 2. Create DTO
        // 3. Execute use case
        // 4. Return response
        
        $dto = new RegisterPatientDTO(...$request->validated());
        $result = $useCase->execute($dto);
        
        return response()->json([
            'user' => $this->mapUserToArray($result['user']),
            'patient' => $this->mapPatientToArray($result['patient'])
        ]);
    }
}
```

## Migration from Eloquent Models

To migrate existing controllers from direct Eloquent usage:

1. **Replace model calls with use cases**:
   ```php
   // Old way
   $user = User::create($request->all());
   
   // New way
   $dto = new RegisterPatientDTO(...);
   $result = $this->registerPatientUseCase->execute($dto);
   ```

2. **Use dependency injection**:
   ```php
   public function __construct(
       private RegisterPatientUseCase $registerPatientUseCase
   ) {}
   ```

3. **Work with domain entities**:
   ```php
   // Access data through entity methods
   $userId = $user->getId()->getValue();
   $email = $user->getEmail()->getValue();
   $isActive = $user->isActive();
   ```

## Testing

The hexagonal architecture makes testing easier:

### Unit Testing Use Cases
```php
class RegisterPatientUseCaseTest extends TestCase
{
    public function test_registers_patient_successfully()
    {
        // Mock repositories
        $userRepo = Mockery::mock(UserRepositoryInterface::class);
        $patientRepo = Mockery::mock(PatientRepositoryInterface::class);
        
        // Set up expectations
        $userRepo->shouldReceive('emailExists')->andReturn(false);
        $userRepo->shouldReceive('save')->once();
        
        // Test use case
        $useCase = new RegisterPatientUseCase($userRepo, $patientRepo);
        $result = $useCase->execute($dto);
        
        // Assert results
        $this->assertInstanceOf(User::class, $result['user']);
    }
}
```

## Best Practices

1. **Keep entities focused on business logic**
2. **Use value objects for type safety**
3. **Keep use cases simple and focused**
4. **Mock repositories in tests**
5. **Validate input at the controller level**
6. **Use DTOs for data transfer**
7. **Keep infrastructure concerns in the Infrastructure layer**

## Future Enhancements

- Add domain events for cross-cutting concerns
- Implement CQRS for read/write separation
- Add specification pattern for complex queries
- Implement domain services for cross-entity operations
