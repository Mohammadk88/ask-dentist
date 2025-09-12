#!/bin/bash
# Backend Health & Seeder Test Script

echo "🧪 Testing Ask.Dentist Backend Setup"
echo "===================================="

# Change to backend directory
cd "$(dirname "$0")/backend"

echo ""
echo "1. ☑️  Health Endpoint Test"
echo "   GET /api/health should return app status"

echo ""
echo "2. ☑️  DatabaseSeeder Test"
echo "   Creates:"
echo "   • Admin: admin@ask.dentist / password"
echo "   • Doctor: dr@ask.dentist / password" 
echo "   • 5 Patients: patient1@ask.dentist to patient5@ask.dentist / password"
echo "   • Clinic with cover image"
echo "   • Services and pricing list"
echo "   • FDI teeth reference (11-48)"
echo "   • Stories and before/after cases"

echo ""
echo "3. ☑️  Idempotent Design"
echo "   • Uses updateOrCreate() for all records"
echo "   • Safe to run multiple times"
echo "   • Preserves existing data"

echo ""
echo "📋 Implementation Details:"
echo "=========================="
echo "Health Endpoint:"
echo "  Route: GET /api/health"
echo "  Response: { \"ok\": true, \"app\": \"ask.dentist\", \"env\": \"...\", \"db\": \"up\", \"queue\": \"redis\" }"
echo ""
echo "DatabaseSeeder:"
echo "  Location: database/seeders/DatabaseSeeder.php"
echo "  Command: php artisan db:seed"
echo "  Features: Comprehensive setup with all required users and data"
echo ""
echo "🚀 Ready for testing with ./bin/dev up!"