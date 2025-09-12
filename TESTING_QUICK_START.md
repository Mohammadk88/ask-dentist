# 🧪 Testing & CI/CD Setup - Quick Start Guide

## ✅ What's Been Implemented

### 🎯 Complete Testing Framework
- **PestPHP 3.8** - Modern PHP testing framework
- **Contract Testing** - API validation against OpenAPI spec
- **Domain Testing** - Business logic validation
- **E2E Testing** - Laravel Dusk for web UI
- **Code Quality** - Laravel Pint + Larastan
- **Security Auditing** - Dependency vulnerability scanning

### 🚀 CI/CD Pipeline
- **GitHub Actions** - Comprehensive automation
- **Flutter Testing** - Both doctor & patient apps
- **APK Building** - Automated Android builds on releases
- **Quality Gates** - 80% coverage requirement
- **Release Management** - Automatic asset deployment

## 🏃‍♂️ Quick Start

### 1. Run Tests Locally
```bash
cd backend

# Run all tests
./run-tests.sh

# Run specific test types
./run-tests.sh unit       # Unit tests only
./run-tests.sh contract   # API contract validation
./run-tests.sh domain     # Business logic tests
./run-tests.sh e2e        # End-to-end browser tests
./run-tests.sh quality    # Code quality checks
```

### 2. Check Test Coverage
```bash
./vendor/bin/pest --coverage --min=80
```

### 3. Code Quality Checks
```bash
./vendor/bin/pint         # Fix code formatting
./vendor/bin/phpstan analyse  # Static analysis
```

### 4. Security Audit
```bash
composer audit            # Check PHP dependencies
npm audit                 # Check Node dependencies
```

## 📊 Test Suites Overview

### Contract Tests
- **File**: `tests/Feature/ContractTest.php`
- **Purpose**: Validate API responses match OpenAPI specification
- **Coverage**: Authentication, Doctors, Consultations, Treatment Plans
- **Dependencies**: `docs/API.yaml` specification file

### Domain Tests  
- **File**: `tests/Feature/DomainTest.php`
- **Purpose**: Test core business logic and workflows
- **Coverage**: Dentist selection, plan acceptance cascade
- **Focus**: Real-world scenarios and edge cases

### E2E Tests
- **File**: `tests/Browser/WebBuilderSmokeTest.php`
- **Purpose**: Test complete user journeys through web interface
- **Coverage**: Admin dashboard, CRUD operations, real-time chat
- **Technology**: Laravel Dusk with Chrome automation

## 🔄 CI/CD Workflow

### On Push/PR
1. **Backend Tests** run with PostgreSQL + Redis
2. **Code Quality** checks (Pint + Larastan)
3. **Contract Validation** against API spec
4. **E2E Tests** with browser automation
5. **Flutter Tests** for both mobile apps
6. **Security Audit** for vulnerabilities

### On Release Tags
1. **All CI tests** must pass
2. **Android APKs** built for both apps
3. **Release assets** automatically attached
4. **Release notes** updated with build info

## 📱 Mobile App Testing

### Flutter Commands
```bash
# Doctor app
cd apps/doctor
flutter test --coverage
flutter analyze

# Patient app  
cd apps/patient
flutter test --coverage
flutter analyze
```

### APK Building
```bash
# Debug builds
flutter build apk --debug

# Release builds (requires signing)
flutter build apk --release
```

## 🛠️ Configuration Files

### Key Files Created/Modified
- `.github/workflows/ci.yml` - Main CI pipeline
- `.github/workflows/build-apks.yml` - APK build automation
- `tests/Concerns/ContractTestingHelpers.php` - OpenAPI validation helpers
- `tests/Feature/ContractTest.php` - API contract tests
- `tests/Feature/DomainTest.php` - Business logic tests
- `tests/Browser/WebBuilderSmokeTest.php` - E2E tests
- `run-tests.sh` - Local test runner script

### Dependencies Added
- `pestphp/pest` - Testing framework
- `pestphp/pest-plugin-laravel` - Laravel integration
- `league/openapi-psr7-validator` - API validation
- `opis/json-schema` - JSON schema validation
- `laravel/dusk` - Browser testing

## 🎯 Quality Standards

### Coverage Requirements
- **Minimum**: 80% line coverage
- **Target**: 90%+ for critical paths
- **Exclusions**: Config files, migrations, generated code

### Code Quality
- **Laravel Pint**: PSR-12 compliant formatting
- **Larastan**: Level 6 static analysis
- **Security**: Zero known vulnerabilities
- **Performance**: Response time benchmarks

## 🚨 Troubleshooting

### Common Issues

#### Pest Tests Failing
```bash
# Clear cache and retry
php artisan config:clear
php artisan cache:clear
./vendor/bin/pest
```

#### Dusk Tests Failing
```bash
# Update ChromeDriver
php artisan dusk:chrome-driver --detect
# Check screenshots in tests/Browser/screenshots/
```

#### Contract Tests Failing
- Verify `docs/API.yaml` exists and is valid
- Check API responses match OpenAPI specification
- Update test data to match schema requirements

#### Coverage Below 80%
- Add tests for uncovered code paths
- Review coverage report: `./vendor/bin/pest --coverage-html coverage-html`
- Focus on critical business logic first

## 🎉 Success Metrics

### When Setup is Working
- ✅ All tests pass: `./run-tests.sh`
- ✅ CI pipeline green on GitHub
- ✅ Coverage above 80%
- ✅ Zero security vulnerabilities
- ✅ APKs build successfully on releases

### Next Steps
1. **Write Tests First**: For new features, write tests before implementation
2. **Monitor Coverage**: Keep coverage above 80% threshold
3. **Review Failures**: Address any CI failures immediately
4. **Update Docs**: Keep API specification current
5. **Security**: Regular dependency updates

## 📚 Resources

### Documentation
- [PestPHP Docs](https://pestphp.com/)
- [Laravel Dusk](https://laravel.com/docs/dusk)
- [OpenAPI Specification](https://swagger.io/specification/)
- [GitHub Actions](https://docs.github.com/en/actions)

### Best Practices
- Test-driven development (TDD)
- API-first design with contracts
- Continuous integration/deployment
- Security-first mindset
- Performance monitoring

Your Ask.Dentist MVP now has **enterprise-grade testing and CI/CD**! 🚀