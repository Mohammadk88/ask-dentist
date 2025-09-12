#!/bin/bash

# Ask.Dentist MVP - Comprehensive Test Runner
# Usage: ./run-tests.sh [test-type]
# Test types: all, unit, feature, domain, contract, e2e, quality, security

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Helper functions
print_header() {
    echo -e "${BLUE}=================================================================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}=================================================================================${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Check if running in correct directory
if [ ! -f "composer.json" ]; then
    print_error "Please run this script from the backend directory"
    exit 1
fi

# Set up environment
setup_environment() {
    print_header "Setting up test environment"
    
    if [ ! -f ".env" ]; then
        print_info "Copying .env.example to .env"
        cp .env.example .env
    fi
    
    print_info "Clearing application cache"
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    
    print_info "Running migrations"
    php artisan migrate:fresh --seed --force
    
    print_success "Environment setup complete"
}

# Run unit tests
run_unit_tests() {
    print_header "Running Unit Tests"
    
    if ./vendor/bin/pest tests/Unit --parallel; then
        print_success "Unit tests passed"
        return 0
    else
        print_error "Unit tests failed"
        return 1
    fi
}

# Run feature tests
run_feature_tests() {
    print_header "Running Feature Tests"
    
    if ./vendor/bin/pest tests/Feature --parallel; then
        print_success "Feature tests passed"
        return 0
    else
        print_error "Feature tests failed"
        return 1
    fi
}

# Run domain tests
run_domain_tests() {
    print_header "Running Domain Tests"
    
    if ./vendor/bin/pest --filter=DomainTest; then
        print_success "Domain tests passed"
        return 0
    else
        print_error "Domain tests failed"
        return 1
    fi
}

# Run contract tests
run_contract_tests() {
    print_header "Running Contract Tests (OpenAPI Validation)"
    
    if [ ! -f "../docs/API.yaml" ]; then
        print_warning "API.yaml not found, skipping contract tests"
        return 0
    fi
    
    if ./vendor/bin/pest --filter=ContractTest; then
        print_success "Contract tests passed"
        return 0
    else
        print_error "Contract tests failed"
        return 1
    fi
}

# Run E2E tests
run_e2e_tests() {
    print_header "Running End-to-End Tests (Laravel Dusk)"
    
    print_info "Starting application server"
    php artisan serve --host=127.0.0.1 --port=8000 &
    SERVER_PID=$!
    
    # Wait for server to start
    sleep 5
    
    if php artisan dusk; then
        print_success "E2E tests passed"
        RESULT=0
    else
        print_error "E2E tests failed"
        RESULT=1
    fi
    
    # Stop server
    kill $SERVER_PID 2>/dev/null || true
    
    return $RESULT
}

# Run code quality checks
run_quality_checks() {
    print_header "Running Code Quality Checks"
    
    local quality_failed=0
    
    # Laravel Pint (Code Formatting)
    print_info "Running Laravel Pint (code formatting)"
    if ./vendor/bin/pint --test; then
        print_success "Code formatting check passed"
    else
        print_error "Code formatting check failed"
        quality_failed=1
    fi
    
    # Larastan (Static Analysis)
    print_info "Running Larastan (static analysis)"
    if ./vendor/bin/phpstan analyse; then
        print_success "Static analysis passed"
    else
        print_error "Static analysis failed"
        quality_failed=1
    fi
    
    return $quality_failed
}

# Run security audit
run_security_audit() {
    print_header "Running Security Audit"
    
    local security_failed=0
    
    # Composer security audit
    print_info "Running Composer security audit"
    if composer audit; then
        print_success "Composer security audit passed"
    else
        print_error "Composer security audit failed"
        security_failed=1
    fi
    
    # NPM security audit (if package.json exists)
    if [ -f "package.json" ]; then
        print_info "Running NPM security audit"
        if npm audit --audit-level=moderate; then
            print_success "NPM security audit passed"
        else
            print_error "NPM security audit failed"
            security_failed=1
        fi
    fi
    
    return $security_failed
}

# Run coverage report
run_coverage() {
    print_header "Generating Code Coverage Report"
    
    print_info "Running tests with coverage"
    if ./vendor/bin/pest --coverage --min=80; then
        print_success "Coverage requirements met (80%+)"
        return 0
    else
        print_error "Coverage requirements not met (below 80%)"
        return 1
    fi
}

# Run all tests
run_all_tests() {
    print_header "Running Complete Test Suite"
    
    local total_failed=0
    
    setup_environment
    
    # Run each test type
    run_unit_tests || total_failed=$((total_failed + 1))
    run_feature_tests || total_failed=$((total_failed + 1))
    run_domain_tests || total_failed=$((total_failed + 1))
    run_contract_tests || total_failed=$((total_failed + 1))
    run_quality_checks || total_failed=$((total_failed + 1))
    run_security_audit || total_failed=$((total_failed + 1))
    run_coverage || total_failed=$((total_failed + 1))
    
    # E2E tests last (as they take longest)
    run_e2e_tests || total_failed=$((total_failed + 1))
    
    if [ $total_failed -eq 0 ]; then
        print_header "🎉 ALL TESTS PASSED! 🎉"
        print_success "Your Ask.Dentist MVP is ready for deployment!"
    else
        print_header "❌ SOME TESTS FAILED"
        print_error "$total_failed test suite(s) failed"
        exit 1
    fi
}

# Main script logic
case "${1:-all}" in
    "unit")
        setup_environment
        run_unit_tests
        ;;
    "feature")
        setup_environment
        run_feature_tests
        ;;
    "domain")
        setup_environment
        run_domain_tests
        ;;
    "contract")
        setup_environment
        run_contract_tests
        ;;
    "e2e")
        setup_environment
        run_e2e_tests
        ;;
    "quality")
        run_quality_checks
        ;;
    "security")
        run_security_audit
        ;;
    "coverage")
        setup_environment
        run_coverage
        ;;
    "all")
        run_all_tests
        ;;
    *)
        echo "Usage: $0 [test-type]"
        echo "Test types: all, unit, feature, domain, contract, e2e, quality, security, coverage"
        exit 1
        ;;
esac