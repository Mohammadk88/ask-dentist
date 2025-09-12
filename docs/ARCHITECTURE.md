# Ask.Dentist MVP - Hexagonal Architecture

## Overview

This document describes the hexagonal architecture implementation for the Ask.Dentist MVP platform. The architecture follows Domain-Driven Design (DDD) principles and ensures clean separation of concerns through well-defined layers.

## Architecture Layers

### 1. Domain Layer (`app/Domain/`)

The core business logic layer that contains pure business rules without any external dependencies.

#### Entities (`app/Domain/Entities/`)

Core business objects that encapsulate business rules and maintain their own state.

```php
// app/Domain/Entities/User.php
class User
{
    private UserId $id;
    private Email $email;
    private UserType $type;
    private UserStatus $status;
    private DateTimeImmutable $createdAt;

    public function __construct(
        UserId $id,
        Email $email,
        UserType $type
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->type = $type;
        $this->status = UserStatus::PENDING;
        $this->createdAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        if ($this->status !== UserStatus::PENDING) {
            throw new DomainException('User can only be activated from pending status');
        }
        $this->status = UserStatus::ACTIVE;
    }
}

// app/Domain/Entities/TreatmentRequest.php
class TreatmentRequest
{
    private TreatmentRequestId $id;
    private UserId $patientId;
    private TreatmentType $type;
    private Priority $priority;
    private RequestStatus $status;
    private ?UserId $assignedDoctorId;

    public function assignToDoctor(UserId $doctorId): void
    {
        if ($this->status !== RequestStatus::PENDING) {
            throw new DomainException('Only pending requests can be assigned');
        }
        $this->assignedDoctorId = $doctorId;
        $this->status = RequestStatus::ASSIGNED;
    }
}
```

#### Value Objects (`app/Domain/ValueObjects/`)

Immutable objects that represent concepts in the domain with no identity.

```php
// app/Domain/ValueObjects/Email.php
final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
        $this->value = strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }
}

// app/Domain/ValueObjects/Money.php
final class Money
{
    private int $amount; // Store in cents
    private Currency $currency;

    public function __construct(int $amount, Currency $currency)
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Amount cannot be negative');
        }
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function add(Money $other): Money
    {
        if (!$this->currency->equals($other->currency)) {
            throw new DomainException('Cannot add different currencies');
        }
        return new Money($this->amount + $other->amount, $this->currency);
    }
}
```

#### Domain Services (`app/Domain/Services/`)

Business logic that doesn't naturally fit within a single entity.

```php
// app/Domain/Services/DentistMatchingService.php
class DentistMatchingService
{
    public function findBestMatchForTreatment(
        TreatmentRequest $request,
        array $availableDentists
    ): ?Doctor {
        // Complex business logic for matching dentists
        // Based on specialization, availability, location, ratings
        return $this->calculateBestMatch($request, $availableDentists);
    }

    private function calculateBestMatch(
        TreatmentRequest $request,
        array $dentists
    ): ?Doctor {
        // Scoring algorithm implementation
    }
}

// app/Domain/Services/PricingService.php
class PricingService
{
    public function calculateTreatmentCost(
        TreatmentPlan $plan,
        PriceList $priceList
    ): Money {
        $total = new Money(0, $priceList->getCurrency());
        
        foreach ($plan->getItems() as $item) {
            $itemPrice = $priceList->getPriceForService($item->getServiceId());
            $total = $total->add($itemPrice->multiply($item->getQuantity()));
        }
        
        return $total;
    }
}
```

#### Repositories (Interfaces) (`app/Domain/Repositories/`)

Define contracts for data persistence without implementation details.

```php
// app/Domain/Repositories/UserRepositoryInterface.php
interface UserRepositoryInterface
{
    public function findById(UserId $id): ?User;
    public function findByEmail(Email $email): ?User;
    public function save(User $user): void;
    public function delete(UserId $id): void;
}

// app/Domain/Repositories/TreatmentRequestRepositoryInterface.php
interface TreatmentRequestRepositoryInterface
{
    public function findById(TreatmentRequestId $id): ?TreatmentRequest;
    public function findPendingRequests(): array;
    public function findByPatient(UserId $patientId): array;
    public function save(TreatmentRequest $request): void;
}
```

### 2. Application Layer (`app/Application/`)

Orchestrates domain objects to perform application-specific business logic.

#### Use Cases (`app/Application/UseCases/`)

Application services that implement specific business scenarios.

```php
// app/Application/UseCases/CreateTreatmentRequest/CreateTreatmentRequestUseCase.php
class CreateTreatmentRequestUseCase
{
    public function __construct(
        private TreatmentRequestRepositoryInterface $treatmentRequestRepository,
        private UserRepositoryInterface $userRepository,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(CreateTreatmentRequestCommand $command): CreateTreatmentRequestResponse
    {
        // Validate patient exists
        $patient = $this->userRepository->findById($command->patientId);
        if (!$patient || !$patient->isPatient()) {
            throw new PatientNotFoundException();
        }

        // Create treatment request
        $request = TreatmentRequest::create(
            TreatmentRequestId::generate(),
            $command->patientId,
            $command->treatmentType,
            $command->priority,
            $command->description,
            $command->files
        );

        // Persist
        $this->treatmentRequestRepository->save($request);

        // Dispatch domain event
        $this->eventDispatcher->dispatch(
            new TreatmentRequestCreated($request->getId())
        );

        return new CreateTreatmentRequestResponse($request->getId());
    }
}

// app/Application/UseCases/AssignDentistToRequest/AssignDentistToRequestUseCase.php
class AssignDentistToRequestUseCase
{
    public function __construct(
        private TreatmentRequestRepositoryInterface $treatmentRequestRepository,
        private UserRepositoryInterface $userRepository,
        private DentistMatchingService $matchingService,
        private NotificationServiceInterface $notificationService
    ) {}

    public function execute(AssignDentistCommand $command): void
    {
        $request = $this->treatmentRequestRepository->findById($command->requestId);
        if (!$request) {
            throw new TreatmentRequestNotFoundException();
        }

        if ($command->dentistId) {
            // Manual assignment
            $dentist = $this->userRepository->findById($command->dentistId);
            if (!$dentist || !$dentist->isDoctor()) {
                throw new DentistNotFoundException();
            }
        } else {
            // Auto-assignment using domain service
            $availableDentists = $this->userRepository->findAvailableDentists();
            $dentist = $this->matchingService->findBestMatchForTreatment(
                $request, 
                $availableDentists
            );
        }

        if (!$dentist) {
            throw new NoDentistAvailableException();
        }

        $request->assignToDoctor($dentist->getId());
        $this->treatmentRequestRepository->save($request);

        // Send notification
        $this->notificationService->notifyDentistAssignment($dentist, $request);
    }
}
```

#### DTOs (`app/Application/DTOs/`)

Data Transfer Objects for crossing application boundaries.

```php
// app/Application/DTOs/UserDTO.php
class UserDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $type,
        public readonly string $status,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $phone,
        public readonly array $roles,
        public readonly string $createdAt
    ) {}

    public static function fromDomain(User $user): self
    {
        return new self(
            id: $user->getId()->getValue(),
            email: $user->getEmail()->getValue(),
            type: $user->getType()->value,
            status: $user->getStatus()->value,
            firstName: $user->getProfile()?->getFirstName(),
            lastName: $user->getProfile()?->getLastName(),
            phone: $user->getProfile()?->getPhone()?->getValue(),
            roles: array_map(fn($role) => $role->getName(), $user->getRoles()),
            createdAt: $user->getCreatedAt()->format('c')
        );
    }
}

// app/Application/DTOs/TreatmentRequestDTO.php
class TreatmentRequestDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $patientId,
        public readonly string $treatmentType,
        public readonly string $priority,
        public readonly string $status,
        public readonly ?string $assignedDoctorId,
        public readonly string $description,
        public readonly array $files,
        public readonly ?array $treatmentPlans,
        public readonly string $createdAt
    ) {}
}
```

#### Commands & Responses (`app/Application/DTOs/Commands/`, `app/Application/DTOs/Responses/`)

```php
// app/Application/DTOs/Commands/CreateTreatmentRequestCommand.php
class CreateTreatmentRequestCommand
{
    public function __construct(
        public readonly UserId $patientId,
        public readonly TreatmentType $treatmentType,
        public readonly Priority $priority,
        public readonly string $description,
        public readonly array $files = []
    ) {}
}

// app/Application/DTOs/Responses/CreateTreatmentRequestResponse.php
class CreateTreatmentRequestResponse
{
    public function __construct(
        public readonly TreatmentRequestId $id
    ) {}
}
```

#### Ports (Interfaces) (`app/Application/Ports/`)

Interfaces for external dependencies (secondary adapters).

```php
// app/Application/Ports/NotificationServiceInterface.php
interface NotificationServiceInterface
{
    public function notifyDentistAssignment(Doctor $dentist, TreatmentRequest $request): void;
    public function notifyPatientPlanReceived(Patient $patient, TreatmentPlan $plan): void;
    public function sendEmail(Email $to, EmailTemplate $template, array $data): void;
    public function sendPushNotification(UserId $userId, string $title, string $body): void;
}

// app/Application/Ports/FileStorageInterface.php
interface FileStorageInterface
{
    public function store(UploadedFile $file, string $path): string;
    public function delete(string $path): void;
    public function generateSignedUrl(string $path, int $expiresInMinutes = 60): string;
}

// app/Application/Ports/PaymentGatewayInterface.php
interface PaymentGatewayInterface
{
    public function createPaymentIntent(Money $amount, string $currency): PaymentIntent;
    public function capturePayment(string $paymentId): PaymentResult;
    public function refundPayment(string $paymentId, Money $amount): RefundResult;
}
```

### 3. Infrastructure Layer (`app/Infrastructure/`)

Implements the ports defined in the application layer and handles external concerns.

#### Repositories (`app/Infrastructure/Repositories/`)

Eloquent-based implementations of domain repository interfaces.

```php
// app/Infrastructure/Repositories/EloquentUserRepository.php
class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(UserId $id): ?User
    {
        $model = UserModel::find($id->getValue());
        return $model ? $this->toDomain($model) : null;
    }

    public function findByEmail(Email $email): ?User
    {
        $model = UserModel::where('email', $email->getValue())->first();
        return $model ? $this->toDomain($model) : null;
    }

    public function save(User $user): void
    {
        $model = UserModel::find($user->getId()->getValue()) ?? new UserModel();
        
        $model->id = $user->getId()->getValue();
        $model->email = $user->getEmail()->getValue();
        $model->type = $user->getType()->value;
        $model->status = $user->getStatus()->value;
        $model->email_verified_at = $user->isEmailVerified() ? now() : null;
        
        $model->save();
    }

    private function toDomain(UserModel $model): User
    {
        $user = new User(
            new UserId($model->id),
            new Email($model->email),
            UserType::from($model->type)
        );

        if ($model->status === 'active') {
            $user->activate();
        }

        return $user;
    }
}
```

#### Adapters (`app/Infrastructure/Adapters/`)

Implementations of application ports.

```php
// app/Infrastructure/Adapters/LaravelNotificationService.php
class LaravelNotificationService implements NotificationServiceInterface
{
    public function notifyDentistAssignment(Doctor $dentist, TreatmentRequest $request): void
    {
        // Convert domain objects to DTOs for Laravel notification
        $dentistDto = DoctorDTO::fromDomain($dentist);
        $requestDto = TreatmentRequestDTO::fromDomain($request);
        
        Notification::send(
            UserModel::find($dentist->getId()->getValue()),
            new DentistAssignmentNotification($requestDto)
        );
    }

    public function sendEmail(Email $to, EmailTemplate $template, array $data): void
    {
        Mail::to($to->getValue())->send(
            new GenericEmail($template, $data)
        );
    }
}

// app/Infrastructure/Adapters/S3FileStorage.php
class S3FileStorage implements FileStorageInterface
{
    public function store(UploadedFile $file, string $path): string
    {
        return Storage::disk('s3')->putFile($path, $file);
    }

    public function generateSignedUrl(string $path, int $expiresInMinutes = 60): string
    {
        return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes($expiresInMinutes));
    }
}

// app/Infrastructure/Adapters/StripePaymentGateway.php
class StripePaymentGateway implements PaymentGatewayInterface
{
    public function createPaymentIntent(Money $amount, string $currency): PaymentIntent
    {
        $stripeIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount->getAmount(),
            'currency' => strtolower($currency),
        ]);

        return new PaymentIntent($stripeIntent->id, $stripeIntent->client_secret);
    }
}
```

#### HTTP Layer (`app/Http/`)

Laravel controllers that serve as primary adapters.

```php
// app/Http/Controllers/Api/TreatmentRequestController.php
class TreatmentRequestController extends Controller
{
    public function __construct(
        private CreateTreatmentRequestUseCase $createUseCase,
        private AssignDentistToRequestUseCase $assignUseCase
    ) {}

    public function store(CreateTreatmentRequestRequest $request): JsonResponse
    {
        $command = new CreateTreatmentRequestCommand(
            patientId: new UserId($request->user()->id),
            treatmentType: TreatmentType::from($request->validated('treatment_type')),
            priority: Priority::from($request->validated('priority')),
            description: $request->validated('description'),
            files: $request->validated('files', [])
        );

        $response = $this->createUseCase->execute($command);

        return response()->json([
            'data' => ['id' => $response->id->getValue()],
            'message' => 'Treatment request created successfully'
        ], 201);
    }

    public function dispatch(string $id, AssignDentistRequest $request): JsonResponse
    {
        $command = new AssignDentistCommand(
            requestId: new TreatmentRequestId($id),
            dentistId: $request->validated('dentist_id') 
                ? new UserId($request->validated('dentist_id'))
                : null
        );

        $this->assignUseCase->execute($command);

        return response()->json([
            'message' => 'Dentist assigned successfully'
        ]);
    }
}
```

#### WebSocket Layer (`app/Infrastructure/WebSockets/`)

Real-time communication adapters.

```php
// app/Infrastructure/WebSockets/SoketiWebSocketAdapter.php
class SoketiWebSocketAdapter implements WebSocketInterface
{
    public function broadcastToUser(UserId $userId, string $event, array $data): void
    {
        broadcast(new UserEvent($userId->getValue(), $event, $data));
    }

    public function broadcastToChannel(string $channel, string $event, array $data): void
    {
        broadcast(new ChannelEvent($channel, $event, $data));
    }
}
```

### 4. Providers (`app/Providers/`)

Dependency injection configuration.

```php
// app/Providers/DomainServiceProvider.php
class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repository bindings
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );
        
        $this->app->bind(
            TreatmentRequestRepositoryInterface::class,
            EloquentTreatmentRequestRepository::class
        );

        // Service bindings
        $this->app->bind(
            NotificationServiceInterface::class,
            LaravelNotificationService::class
        );
        
        $this->app->bind(
            FileStorageInterface::class,
            S3FileStorage::class
        );
        
        $this->app->bind(
            PaymentGatewayInterface::class,
            StripePaymentGateway::class
        );

        // Domain services
        $this->app->singleton(DentistMatchingService::class);
        $this->app->singleton(PricingService::class);
    }
}
```

## Key Principles

### 1. Dependency Rule
- Dependencies point inward toward the domain
- Domain layer has no dependencies on external frameworks
- Infrastructure depends on Application and Domain
- Application depends only on Domain

### 2. No Eloquent Leakage
- Eloquent models stay in Infrastructure layer
- Domain entities are pure PHP objects
- Repository implementations handle mapping between Eloquent and Domain

### 3. Use Case Driven
- Each business scenario is represented by a Use Case
- Use Cases orchestrate domain objects
- Controllers are thin and delegate to Use Cases

### 4. Event Driven
- Domain events represent important business moments
- Events are dispatched from Use Cases
- Event handlers are in Infrastructure layer

## Benefits

1. **Testability**: Domain logic can be tested without framework dependencies
2. **Flexibility**: Easy to swap infrastructure components
3. **Maintainability**: Clear separation of concerns
4. **Framework Independence**: Domain logic is not tied to Laravel
5. **Scalability**: Architecture supports growth and complexity

## Folder Structure

```
app/
├── Domain/
│   ├── Entities/
│   ├── ValueObjects/
│   ├── Services/
│   ├── Repositories/
│   ├── Events/
│   └── Exceptions/
├── Application/
│   ├── UseCases/
│   ├── DTOs/
│   │   ├── Commands/
│   │   └── Responses/
│   ├── Ports/
│   └── Events/
├── Infrastructure/
│   ├── Repositories/
│   ├── Adapters/
│   ├── Models/
│   ├── WebSockets/
│   └── Events/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
└── Providers/
```

This architecture ensures a clean, maintainable, and testable codebase that can evolve with the business requirements while maintaining clear boundaries between different concerns.
          └──────────┬───────────┘
                     │
          ┌──────────▼───────────┐
          │    Backend API       │
          │    (Laravel 12)      │
          └──────────┬───────────┘
                     │
     ┌───────────────┼───────────────┐
     │               │               │
┌────▼────┐    ┌────▼────┐    ┌────▼────┐
│PostgreSQL│    │  Redis  │    │ Soketi  │
│Database │    │ Cache   │    │WebSocket│
└─────────┘    └─────────┘    └─────────┘
```

### Technology Stack

#### Frontend
- **Patient App**: Flutter (Dart)
- **Doctor App**: Flutter (Dart)
- **Admin Dashboard**: Laravel Filament (PHP)
- **Doctor Web Dashboard**: Laravel Livewire (PHP)

#### Backend
- **API**: Laravel 12 (PHP 8.2+)
- **Authentication**: Laravel Passport (OAuth2)
- **Real-time Communication**: Soketi (WebSocket)
- **Queue Processing**: Redis

#### Database & Storage
- **Primary Database**: PostgreSQL 17
- **Cache**: Redis 7
- **File Storage**: Local (Development) / AWS S3 (Production)
- **Search**: PostgreSQL Full-Text Search

#### Infrastructure
- **Web Server**: Nginx
- **Application Server**: PHP-FPM
- **Containerization**: Docker & Docker Compose
- **Process Manager**: Supervisor (Production)

## Architectural Patterns

### Backend Architecture

The backend follows **Clean Architecture** principles with **Domain-Driven Design** (DDD):

```text
┌─────────────────────────────────────────────┐
│               Presentation Layer            │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────┐│
│  │ Controllers │ │ Middleware  │ │ Requests││
│  └─────────────┘ └─────────────┘ └─────────┘│
└─────────────────┬───────────────────────────┘
                  │
┌─────────────────▼───────────────────────────┐
│              Application Layer              │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────┐│
│  │ Use Cases   │ │    DTOs     │ │Services ││
│  └─────────────┘ └─────────────┘ └─────────┘│
└─────────────────┬───────────────────────────┘
                  │
┌─────────────────▼───────────────────────────┐
│                Domain Layer                 │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────┐│
│  │  Entities   │ │ Value Objs  │ │  Rules  ││
│  └─────────────┘ └─────────────┘ └─────────┘│
└─────────────────┬───────────────────────────┘
                  │
┌─────────────────▼───────────────────────────┐
│             Infrastructure Layer            │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────┐│
│  │Repositories │ │   Models    │ │Adapters ││
│  └─────────────┘ └─────────────┘ └─────────┘│
└─────────────────────────────────────────────┘
```

### Mobile Architecture

Both Flutter apps use **Clean Architecture** with **Riverpod** for state management:

```text
┌─────────────────────────────────────────────┐
│               Presentation                  │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────┐│
│  │   Screens   │ │   Widgets   │ │ Providers││
│  └─────────────┘ └─────────────┘ └─────────┘│
└─────────────────┬───────────────────────────┘
                  │
┌─────────────────▼───────────────────────────┐
│                Domain                       │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────┐│
│  │  Entities   │ │ Use Cases   │ │  Repos  ││
│  └─────────────┘ └─────────────┘ └─────────┘│
└─────────────────┬───────────────────────────┘
                  │
┌─────────────────▼───────────────────────────┐
│                 Data                        │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────┐│
│  │Repositories │ │ Data Sources│ │ Models  ││
│  └─────────────┘ └─────────────┘ └─────────┘│
└─────────────────────────────────────────────┘
```

## Core Components

### 1. Authentication & Authorization

#### JWT-based Authentication
- **Access Tokens**: Short-lived (1 hour)
- **Refresh Tokens**: Long-lived (30 days)
- **Token Refresh**: Automatic background refresh

#### Role-Based Access Control (RBAC)
- **Patient**: Book appointments, join consultations
- **Doctor**: Manage schedule, conduct consultations
- **Admin**: System administration, user management

### 2. Real-time Communication

#### WebSocket Integration
- **Soketi Server**: Self-hosted Pusher replacement
- **Channels**: Subscription-based messaging
- **Events**: Real-time notifications and updates

#### Video Consultations
- **Agora SDK**: Video calling infrastructure
- **Recording**: Optional consultation recording
- **Screen Sharing**: Educational content sharing

### 3. Data Management

#### Database Design
- **PostgreSQL**: ACID-compliant transactions
- **Migrations**: Version-controlled schema changes
- **Indexes**: Optimized for query performance
- **Foreign Keys**: Referential integrity

#### Caching Strategy
- **Redis**: Session storage and caching
- **Query Caching**: Frequently accessed data
- **Rate Limiting**: API throttling

### 4. File Management

#### Media Storage
- **Images**: Patient photos, dental records
- **Documents**: Prescriptions, reports
- **Videos**: Consultation recordings (optional)

#### Storage Providers
- **Local**: Development environment
- **AWS S3**: Production environment
- **CDN**: Content delivery optimization

## Security Architecture

### Data Protection

#### Encryption
- **At Rest**: Database encryption (PostgreSQL TDE)
- **In Transit**: TLS 1.3 for all communications
- **Application**: Sensitive data encryption

#### Privacy Compliance
- **HIPAA**: Healthcare data protection
- **GDPR**: European data protection
- **Data Minimization**: Collect only necessary data

### API Security

#### Authentication
- **OAuth 2.0**: Industry standard
- **JWT Tokens**: Stateless authentication
- **Refresh Mechanism**: Secure token renewal

#### Rate Limiting
- **Per-User Limits**: Prevent abuse
- **API Throttling**: System protection
- **DDoS Protection**: Infrastructure level

### Infrastructure Security

#### Network Security
- **Firewall Rules**: Restricted access
- **VPN Access**: Secure administration
- **SSL Certificates**: End-to-end encryption

#### Container Security
- **Image Scanning**: Vulnerability detection
- **Minimal Images**: Reduced attack surface
- **Secrets Management**: Secure credential storage

## Scalability Considerations

### Horizontal Scaling

#### Application Scaling
- **Load Balancing**: Multiple app instances
- **Session Clustering**: Redis-backed sessions
- **Stateless Design**: No server affinity

#### Database Scaling
- **Read Replicas**: Query distribution
- **Connection Pooling**: Efficient connections
- **Query Optimization**: Performance tuning

### Performance Optimization

#### Caching Layers
- **Application Cache**: Redis
- **Database Cache**: Query caching
- **CDN**: Static asset delivery

#### Background Processing
- **Queue Workers**: Async task processing
- **Job Scheduling**: Automated tasks
- **Email Processing**: Queued notifications

## Monitoring & Observability

### Application Monitoring

#### Logging
- **Structured Logs**: JSON format
- **Log Aggregation**: Centralized collection
- **Error Tracking**: Exception monitoring

#### Metrics
- **Application Metrics**: Performance KPIs
- **Infrastructure Metrics**: System health
- **Business Metrics**: User behavior

### Health Checks

#### Service Health
- **Database**: Connection and query tests
- **Redis**: Cache availability
- **External APIs**: Third-party services

#### Monitoring Tools
- **Laravel Telescope**: Development debugging
- **Laravel Pulse**: Production monitoring
- **Custom Dashboards**: Business metrics

## Deployment Architecture

### Development Environment

```text
┌─────────────────┐
│ Docker Compose  │
├─────────────────┤
│ ┌─────────────┐ │
│ │   Nginx     │ │
│ └─────────────┘ │
│ ┌─────────────┐ │
│ │  PHP-FPM    │ │
│ └─────────────┘ │
│ ┌─────────────┐ │
│ │ PostgreSQL  │ │
│ └─────────────┘ │
│ ┌─────────────┐ │
│ │    Redis    │ │
│ └─────────────┘ │
│ ┌─────────────┐ │
│ │   Soketi    │ │
│ └─────────────┘ │
└─────────────────┘
```

### Production Environment

```text
┌─────────────────┐    ┌─────────────────┐
│  Load Balancer  │    │     CDN         │
└─────────┬───────┘    └─────────────────┘
          │
┌─────────▼───────┐    ┌─────────────────┐
│  App Servers    │    │    Database     │
│  (Multi-AZ)     │◄───┤   (Primary +    │
└─────────────────┘    │   Read Replicas)│
                       └─────────────────┘
┌─────────────────┐    ┌─────────────────┐
│     Redis       │    │   File Storage  │
│   (Cluster)     │    │     (S3)        │
└─────────────────┘    └─────────────────┘
```

## API Design Principles

### RESTful Design
- **Resource-Based URLs**: `/api/v1/appointments`
- **HTTP Methods**: GET, POST, PUT, DELETE
- **Status Codes**: Meaningful HTTP responses
- **JSON Format**: Consistent data exchange

### Versioning Strategy
- **URL Versioning**: `/api/v1/`, `/api/v2/`
- **Backward Compatibility**: Support previous versions
- **Deprecation Policy**: Gradual phase-out

### Error Handling
- **Consistent Format**: Standardized error responses
- **Error Codes**: Application-specific codes
- **Validation**: Detailed field-level errors

## Future Considerations

### Microservices Migration
- **Service Boundaries**: Domain-driven services
- **API Gateway**: Central entry point
- **Service Discovery**: Dynamic service location

### Advanced Features
- **AI Integration**: Symptom analysis
- **IoT Integration**: Dental device connectivity
- **Blockchain**: Secure medical records

### Scalability Enhancements
- **Event Sourcing**: Audit trail and replay
- **CQRS**: Command Query Responsibility Segregation
- **Message Queues**: Reliable async communication
