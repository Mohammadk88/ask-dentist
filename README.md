# Ask.Dentist MVP

A comprehensive dental practice management platform with separate patient and doctor mobile applications.

## 🏗️ Architecture

This is a mono-repository containing:
- **Backend**: Laravel 12 API with PHP 8.4
- **Patient App**: Flutter mobile application
- **Doctor App**: Flutter mobile application
- **Infrastructure**: Docker Compose setup

## 🚀 Quick Start

### Prerequisites
- Docker & Docker Compose
- Make (optional, for convenience commands)

### Setup Development Environment

1. **Clone and setup**:
```bash
git clone <repository-url>
cd ask-dentist-mvp
make setup
```

2. **Or manually**:
```bash
# Start services
cd infra && docker compose up -d

# Install dependencies
docker compose exec app composer install

# Generate app key
docker compose exec app php artisan key:generate

# Run migrations
docker compose exec app php artisan migrate --seed
```

3. **Access the application**:
- API: http://localhost:8080
- MailHog: http://localhost:8025
- Soketi Metrics: http://localhost:9601

## 🛠️ Development Commands

### Using Make (Recommended)

```bash
make up          # Start all services
make down        # Stop all services
make shell       # Access Laravel container
make migrate     # Run database migrations
make fresh       # Fresh database with seeders
make test        # Run Laravel tests
make logs        # View container logs
```

### Direct Docker Compose

```bash
cd infra
docker compose up -d
docker compose exec app bash
docker compose logs -f
```

## 📦 Services

| Service | Port | Description |
|---------|------|-------------|
| Laravel API | 8080 | Main backend API |
| PostgreSQL | 5432 | Database |
| Redis | 6379 | Cache & Sessions |
| Soketi | 6001 | WebSocket server |
| MailHog | 8025 | Email testing |

## 🏭 Backend Features

- **Laravel 12** with PHP 8.4
- **Authentication**: Laravel Passport OAuth2
- **Admin Panel**: Filament
- **Real-time**: Soketi WebSocket
- **Queue System**: Redis-backed jobs
- **Email**: SMTP with MailHog for development
- **Database**: PostgreSQL 17
- **Caching**: Redis

### Key Packages

- `laravel/passport` - OAuth2 authentication
- `filament/filament` - Admin panel
- `spatie/laravel-permission` - Role & permissions
- `spatie/laravel-activitylog` - Activity logging
- `predis/predis` - Redis client
- `guzzlehttp/guzzle` - HTTP client

## 📱 Mobile Apps

### Patient App (`apps/patient/`)

- Appointment booking
- Consultation history
- Payment processing
- Real-time chat

### Doctor App (`apps/doctor/`)

- Schedule management
- Patient consultations
- Medical records
- Practice analytics

Both apps use:

- **Flutter 3.x**
- **Riverpod** for state management
- **Go Router** for navigation
- **Dio** for API communication

## 🗃️ Database Schema

See `docs/DB_SCHEMA.md` for complete schema documentation.

Key entities:

- Users (patients, doctors, admins)
- Clinics & Doctor profiles
- Appointments & Consultations
- Payments & Billing
- Medical records

## 📚 API Documentation

- **Swagger/OpenAPI**: Available at `/api/documentation`
- **Postman Collection**: `docs/API.yaml`
- **Authentication**: OAuth2 Bearer tokens

## 🔧 Configuration

### Environment Variables

Key configuration in `backend/.env`:

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_DATABASE=askdentist

# Redis
REDIS_HOST=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Broadcasting
BROADCAST_DRIVER=pusher
PUSHER_HOST=soketi
PUSHER_APP_ID=ask
PUSHER_APP_KEY=askkey
PUSHER_APP_SECRET=asksecret

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

## 🧪 Testing

```bash
# Run all tests
make test

# Specific test suite
docker compose exec app php artisan test --testsuite=Feature
docker compose exec app php artisan test --testsuite=Unit

# With coverage
docker compose exec app php artisan test --coverage
```

## 📈 Production Deployment

1. **Environment**: Copy `.env.example` to `.env.production`
2. **Build**: `docker compose -f docker-compose.prod.yml build`
3. **Deploy**: Use provided infrastructure configurations
4. **SSL**: Configure reverse proxy with SSL termination

## 🤝 Contributing

1. Follow PSR-12 coding standards
2. Write tests for new features
3. Update documentation
4. Use conventional commits

## 📄 License

MIT License - see LICENSE file for details.

## 🏗️ Project Structure

```text
ask-dentist-mvp/
├── apps/
│   ├── doctor/          # Flutter doctor app
│   └── patient/         # Flutter patient app
├── backend/             # Laravel API
│   ├── app/
│   ├── config/
│   ├── database/
│   └── routes/
├── docs/                # Documentation
├── infra/               # Docker & deployment
└── Makefile            # Development commands
```


1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For questions and support, please contact the development team.
