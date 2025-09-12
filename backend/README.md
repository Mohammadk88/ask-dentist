# Ask.Dentist Backend

Laravel 12 API and Web Dashboard for the Ask.Dentist telemedicine platform.

## Features

- **RESTful API**: Complete API for mobile applications
- **Admin Dashboard**: Filament-based admin interface
- **Doctor Dashboard**: Web interface for doctors
- **Real-time Communication**: WebSocket support via Soketi
- **Authentication**: Laravel Passport OAuth2
- **Authorization**: Role-based permissions
- **Activity Logging**: Track user actions
- **API Documentation**: Auto-generated with Scribe

## Architecture

The backend follows a clean architecture pattern with:

- **Domain Layer**: Core business logic and entities
- **Application Layer**: Use cases and DTOs
- **Infrastructure Layer**: Database, external services
- **Presentation Layer**: Controllers, middleware

## Setup

### Prerequisites

- PHP 8.2+
- Composer
- PostgreSQL 17
- Redis
- Node.js & NPM

### Installation

1. Install dependencies:

```bash
composer install
npm install
```

2. Environment setup:

```bash
cp .env.example .env
php artisan key:generate
```

3. Database setup:

```bash
php artisan migrate
php artisan db:seed
```

4. OAuth setup:

```bash
php artisan passport:install
```

5. Development server:

```bash
php artisan serve
npm run dev
```

## API Endpoints

### Authentication

- `POST /api/v1/auth/register` - User registration
- `POST /api/v1/auth/login` - User login
- `POST /api/v1/auth/logout` - User logout
- `POST /api/v1/auth/refresh` - Refresh token

### Patients

- `GET /api/v1/patients` - List patients
- `POST /api/v1/patients` - Create patient
- `GET /api/v1/patients/{id}` - Get patient
- `PUT /api/v1/patients/{id}` - Update patient

### Doctors

- `GET /api/v1/doctors` - List doctors
- `GET /api/v1/doctors/{id}` - Get doctor
- `PUT /api/v1/doctors/{id}` - Update doctor profile

### Appointments

- `GET /api/v1/appointments` - List appointments
- `POST /api/v1/appointments` - Book appointment
- `GET /api/v1/appointments/{id}` - Get appointment
- `PUT /api/v1/appointments/{id}` - Update appointment
- `DELETE /api/v1/appointments/{id}` - Cancel appointment

### Consultations

- `GET /api/v1/consultations` - List consultations
- `POST /api/v1/consultations` - Start consultation
- `GET /api/v1/consultations/{id}` - Get consultation
- `PUT /api/v1/consultations/{id}` - Update consultation

## Dashboards

### Admin Dashboard

Access at `/admin`

- User management
- Doctor verification
- System configuration
- Reports and analytics

### Doctor Dashboard

Access at `/doctor`

- Schedule management
- Patient consultations
- Medical records
- Earnings overview

## Development

### Code Style

```bash
./vendor/bin/pint --test
./vendor/bin/pint
```

### Static Analysis

```bash
./vendor/bin/phpstan analyse
```

### Testing

```bash
php artisan test
php artisan test --coverage
```

### API Documentation

Generate documentation:

```bash
php artisan scribe:generate
```

View at `/docs`

## Deployment

### Docker

The application is containerized and can be deployed using Docker Compose.

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database
- [ ] Set up Redis for caching
- [ ] Configure mail settings
- [ ] Set up SSL certificates
- [ ] Configure backup strategy
- [ ] Set up monitoring

## Contributing

1. Follow PSR-12 coding standards
2. Write tests for new features
3. Update API documentation
4. Follow conventional commits

## License

MIT License
