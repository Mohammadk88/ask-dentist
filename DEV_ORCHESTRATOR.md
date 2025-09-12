# Ask.Dentist MVP Developer Orchestrator

A powerful command-line interface for managing the Ask.Dentist MVP development environment with Laravel backend and Flutter frontend apps.

## Quick Start

```bash
# Start the complete development environment
./bin/dev up

# Run smoke tests to verify everything works
./bin/dev smoke

# Open admin portal with credentials
./bin/dev admin

# Start patient Flutter app
./bin/dev run-patient
```

## Available Commands

### 🚀 Environment Management

- **`./bin/dev up`** - Start all services with complete Laravel setup
  - Starts Docker containers
  - Waits for PostgreSQL to be ready
  - Runs Laravel migrations and seeders
  - Sets up Passport for API authentication
  - Links storage and optimizes caches

- **`./bin/dev down`** - Stop all services and clean volumes
- **`./bin/dev reset`** - Reset environment with fresh database

### 🔍 Monitoring & Debugging

- **`./bin/dev logs`** - Follow filtered error logs (highlights ERROR, EXCEPTION, etc.)
- **`./bin/dev smoke`** - Run comprehensive API health checks
- **`./bin/dev doctor-logs`** - Follow Laravel application logs specifically
- **`./bin/dev fix`** - Run diagnostics and display common fixes

### 🌐 Quick Access

- **`./bin/dev admin`** - Show admin credentials and open admin portal
  - Credentials: `admin@ask.dentist` / `password`
  - Opens: `http://localhost:8080/admin`

- **`./bin/dev doctor`** - Show doctor credentials and open doctor portal  
  - Credentials: `dr@ask.dentist` / `password`
  - Opens: `http://localhost:8080/doctor`

- **`./bin/dev shell`** - Open PHP container shell for debugging

### 📱 Flutter Apps

- **`./bin/dev run-patient`** - Start patient Flutter app
  - Installs dependencies (`flutter pub get`)
  - Runs tests (`flutter test`)
  - Launches app (prefers Chrome, falls back to macOS/default)

- **`./bin/dev run-doctor`** - Start doctor Flutter app
  - Same workflow as patient app
  - Runs on different port (3001) when using Chrome

### 🧪 Testing

- **`./bin/dev test`** - Run Laravel backend tests

## Makefile Shortcuts

All main commands can also be run via `make`:

```bash
make up           # Same as ./bin/dev up
make down         # Same as ./bin/dev down  
make logs         # Same as ./bin/dev logs
make smoke        # Same as ./bin/dev smoke
make admin        # Same as ./bin/dev admin
make doctor       # Same as ./bin/dev doctor
make shell        # Same as ./bin/dev shell
make run-patient  # Same as ./bin/dev run-patient
make run-doctor   # Same as ./bin/dev run-doctor
make test         # Same as ./bin/dev test
```

## Architecture

### Backend (Laravel)
- **API**: `http://localhost:8080/api/*`
- **Admin Portal**: `http://localhost:8080/admin`
- **Doctor Portal**: `http://localhost:8080/doctor`
- **Database**: PostgreSQL on port 5432
- **Container**: `php-fpm` (PHP 8.2+ with Laravel)

### Frontend Apps
- **Patient App**: Flutter app in `apps/patient/`
- **Doctor App**: Flutter app in `apps/doctor/`
- **Web Preview**: Chrome on ports 3000/3001 when available

## Common Workflows

### First Time Setup
```bash
# Clone the repository and navigate to it
cd ask-dentist-mvp

# Start everything for the first time
./bin/dev up

# Verify everything works
./bin/dev smoke

# Open admin portal
./bin/dev admin
```

### Daily Development
```bash
# Start services (quick restart)
./bin/dev up

# Work on patient app
./bin/dev run-patient

# Check backend logs while developing
./bin/dev doctor-logs

# Run tests
./bin/dev test
```

### Debugging Issues
```bash
# Check system status and get fix suggestions
./bin/dev fix

# View error logs
./bin/dev logs

# Access backend shell for debugging
./bin/dev shell

# Reset environment if needed
./bin/dev reset
```

## Service URLs

When running locally:

- **Main API**: http://localhost:8080
- **Admin Portal**: http://localhost:8080/admin
- **Doctor Portal**: http://localhost:8080/doctor
- **Patient App**: http://localhost:3000 (when running in Chrome)
- **Doctor App**: http://localhost:3001 (when running in Chrome)
- **MailHog** (if configured): http://localhost:8025
- **Database**: PostgreSQL on localhost:5432

## Troubleshooting

### Common Issues and Fixes

#### Docker not running
```bash
# Error: Cannot connect to Docker daemon
# Fix: Start Docker Desktop application
```

#### Database connection failed
```bash
# Error: SQLSTATE[08006] connection failed
# Fix: Wait for PostgreSQL and check configuration
./scripts/wait-for.sh 127.0.0.1 5432 60
./bin/dev shell
php artisan migrate
```

#### 500 error on admin/doctor portals
```bash
# Error: Internal Server Error
# Fix: Check storage permissions
./bin/dev shell
chown -R www-data:www-data storage bootstrap/cache
```

#### 419 CSRF Token Mismatch
```bash
# Error: 419 PAGE EXPIRED
# Fix: Clear browser cookies or check APP_URL
# Ensure APP_URL=http://localhost:8080 in .env
```

#### Flutter app won't start
```bash
# Error: Flutter command not found
# Fix: Install Flutter and check doctor
flutter doctor

# Error: Dependencies issues
cd apps/patient  # or apps/doctor
flutter clean
flutter pub get
```

### Getting Help

1. **Run diagnostics**: `./bin/dev fix`
2. **Check logs**: `./bin/dev logs`
3. **Verify health**: `./bin/dev smoke`
4. **Access shell**: `./bin/dev shell`

## Advanced Usage

### Custom Database Operations
```bash
./bin/dev shell
php artisan migrate:status        # Check migration status
php artisan migrate:fresh --seed  # Fresh database with test data
php artisan db:seed --class=DoctorSeeder  # Run specific seeder
```

### Laravel Debugging
```bash
./bin/dev shell
php artisan tinker               # Laravel REPL
php artisan route:list           # View all routes
php artisan config:cache         # Cache configuration
php artisan queue:work           # Process background jobs
```

### Flutter Development
```bash
# Patient app with specific device
cd apps/patient
flutter devices                  # List available devices
flutter run -d chrome           # Force Chrome
flutter run -d macos           # Force macOS

# Hot reload development
flutter run --hot               # Enable hot reload
```

## Files Structure

```
bin/
  dev                 # Main orchestrator script
scripts/
  wait-for.sh        # TCP connection waiter
Makefile             # Make shortcuts
docker-compose.yml   # Docker services definition
apps/
  patient/           # Flutter patient app
  doctor/            # Flutter doctor app
backend/             # Laravel API backend
```

---

**Need help?** Run `./bin/dev help` or `make help` for quick reference!