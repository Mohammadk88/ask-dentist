# Ask.Dentist MVP - Development Commands

.PHONY: help up down build restart logs shell migrate fresh seed test
.PHONY: smoke admin doctor run-patient run-doctor doctor-logs fix reset
.PHONY: admin-ar doctor-ar monitor-ar test-ar ip health logs-patient fix-network

# Arabic Command Interface
admin-ar:
	@echo "🔓 فتح لوحة تحكم المسؤول..."
	@echo "📧 admin@ask.dentist / password"
	@./bin/dev admin

doctor-ar:
	@echo "🩺 فتح بوابة الطبيب..."
	@echo "📧 dr@ask.dentist / password"
	@./bin/dev doctor

monitor-ar:
	@echo "📊 مراقبة حيّة للنظام مع تمييز الأخطاء..."
	@./bin/dev logs

test-ar:
	@echo "🔍 فحص صحة النظام..."
	@./bin/dev smoke

# Arabic aliases (for terminal input)
افتح-لوحة-الأدمن: admin-ar
افتح-بوابة-الطبيب: doctor-ar
مراقبة-حية: monitor-ar
اختبار-النظام: test-ar

# New networking and debugging commands
ip:           ; ./bin/dev ip
health:       ; ./bin/dev health
run-patient:  ; ./bin/dev run-patient
logs-patient: ; ./bin/dev logs-patient
fix-network:  ; ./bin/dev fix-network

# Arabic networking commands
ip-ar:
	@echo "🌐 معلومات الشبكة للهواتف المحمولة..."
	@./bin/dev ip

health-ar:
	@echo "🏥 فحص صحة واجهة برمجة التطبيقات..."
	@./bin/dev health

# Add Arabic aliases for new commands
معلومات-الشبكة: ip-ar
فحص-الاتصال: health-ar

# Updated help text
help:
	@echo "Ask.Dentist MVP Development Commands"
	@echo "=================================="
	@echo ""
	@echo "🇸🇦 Arabic Commands (الأوامر العربية):"
	@echo "admin-ar / افتح-لوحة-الأدمن   - Open admin panel (فتح لوحة التحكم)"
	@echo "doctor-ar / افتح-بوابة-الطبيب - Open doctor portal (فتح بوابة الطبيب)"
	@echo "monitor-ar / مراقبة-حية       - Live monitoring (مراقبة حيّة مع تمييز الأخطاء)"
	@echo "test-ar / اختبار-النظام      - System health check (فحص صحة النظام)"
	@echo "ip-ar / معلومات-الشبكة       - Network info for mobile (معلومات الشبكة للموبايل)"
	@echo "health-ar / فحص-الاتصال      - API health check (فحص صحة واجهة التطبيقات)"
	@echo ""
	@echo "🚀 Main Commands (via ./bin/dev):"
	@echo "up          - Start all services with full setup"
	@echo "down        - Stop all services and clean volumes"
	@echo "logs        - View filtered error logs"
	@echo "smoke       - Run API health checks"
	@echo "admin       - Show admin credentials and open portal"
	@echo "doctor      - Show doctor credentials and open portal"
	@echo "shell       - Access PHP container shell"
	@echo "run-patient - Start patient Flutter app"
	@echo "run-doctor  - Start doctor Flutter app"
	@echo "doctor-logs - Follow Laravel application logs"
	@echo "fix         - Run diagnostics and show fixes"
	@echo "reset       - Reset environment with fresh database"
	@echo ""
	@echo "🌐 Network & Mobile Development:"
	@echo "ip          - Show LAN IP and platform-specific URLs"
	@echo "health      - Test API health endpoint"
	@echo "fix-network - Diagnose network connectivity issues"
	@echo ""
	@echo "🔧 Legacy Commands (via infra/ structure):"
	@echo "build       - Build all containers"
	@echo "restart     - Restart all services"
	@echo "migrate     - Run database migrations"
	@echo "fresh       - Fresh database with seeders"
	@echo "seed        - Run database seeders"
	@echo "test        - Run Laravel tests"
	@echo "install     - Install composer dependencies"
	@echo "key         - Generate Laravel app key"
	@echo "clear       - Clear Laravel caches"
	@echo "queue       - Start queue worker"
	@echo "tinker      - Laravel tinker REPL"

# New orchestrator commands (via ./bin/dev)
up:           ; ./bin/dev up
down:         ; ./bin/dev down
logs:         ; ./bin/dev logs
smoke:        ; ./bin/dev smoke
admin:        ; ./bin/dev admin
doctor:       ; ./bin/dev doctor
shell:        ; ./bin/dev shell
run-patient:  ; ./bin/dev run-patient
run-doctor:   ; ./bin/dev run-doctor
doctor-logs:  ; ./bin/dev doctor-logs
fix:          ; ./bin/dev fix
reset:        ; ./bin/dev reset
test:         ; ./bin/dev test

# Legacy commands (preserved for compatibility)
legacy-up:
	@echo "🚀 Starting Ask.Dentist MVP services..."
	cd infra && docker compose up -d
	@echo "✅ Services started! Visit http://localhost:8080"

# Stop all services
legacy-down:
	@echo "🛑 Stopping Ask.Dentist MVP services..."
	cd infra && docker compose down
	@echo "✅ Services stopped!"

# Build all containers
build:
	@echo "🔨 Building containers..."
	docker compose build --no-cache
	@echo "✅ Build complete!"

# Restart all services
restart: legacy-down legacy-up

# View logs (legacy version)
legacy-logs:
	docker compose logs -f

# Access Laravel container shell (legacy)
legacy-shell:
	docker compose exec php-fpm bash

# Run database migrations
migrate:
	@echo "📊 Running database migrations..."
	docker compose exec php-fpm php artisan migrate
	@echo "✅ Migrations complete!"

# Fresh database with seeders
fresh:
	@echo "🗃️  Creating fresh database..."
	docker compose exec php-fpm php artisan migrate:fresh --seed
	@echo "✅ Fresh database created!"

# Run database seeders
seed:
	@echo "🌱 Running database seeders..."
	docker compose exec php-fpm php artisan db:seed
	@echo "✅ Seeders complete!"

# Install composer dependencies
install:
	@echo "📦 Installing composer dependencies..."
	docker compose exec php-fpm composer install
	@echo "✅ Dependencies installed!"

# Generate Laravel application key
key:
	@echo "🔑 Generating Laravel application key..."
	docker compose exec php-fpm php artisan key:generate
	@echo "✅ Application key generated!"

# Clear Laravel caches
clear:
	@echo "🧹 Clearing Laravel caches..."
	docker compose exec php-fpm php artisan optimize:clear
	@echo "✅ Caches cleared!"

# Start queue worker
queue:
	@echo "⚡ Starting queue worker..."
	docker compose exec php-fpm php artisan queue:work --verbose --tries=3 --timeout=90

# Laravel tinker REPL
tinker:
	docker compose exec php-fpm php artisan tinker

# Network and connectivity commands
ip:
	@./bin/dev ip

health:
	@./bin/dev health

run-patient:
	@./bin/dev run-patient

logs-patient:
	@./bin/dev logs-patient

fix-network:
	@./bin/dev fix-network

# Setup development environment (first time setup)
setup: build legacy-up install key migrate seed
	@echo "🎉 Ask.Dentist MVP development environment is ready!"
	@echo "📍 API: http://localhost:8080"
	@echo "📧 MailHog: http://localhost:8025"
	@echo "📊 Soketi Metrics: http://localhost:9601"
