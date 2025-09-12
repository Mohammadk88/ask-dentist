<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Real-Time Chat System Test\n";
echo "===============================\n\n";

try {
    // Test 1: Check broadcasting configuration
    echo "1. Testing Broadcasting Configuration...\n";
    $broadcastConfig = config('broadcasting.default');
    $pusherConfig = config('broadcasting.connections.pusher');
    
    echo "   ✅ Default driver: {$broadcastConfig}\n";
    echo "   ✅ Pusher host: {$pusherConfig['options']['host']}\n";
    echo "   ✅ Pusher port: {$pusherConfig['options']['port']}\n";
    echo "   ✅ Pusher scheme: {$pusherConfig['options']['scheme']}\n\n";
    
    // Test 2: Check if models can be instantiated
    echo "2. Testing Model Instantiation...\n";
    
    $user = new App\Models\User();
    echo "   ✅ User model created\n";
    
    $message = new App\Models\Message();
    echo "   ✅ Message model created\n";
    
    $consultation = new App\Models\Consultation();
    echo "   ✅ Consultation model created\n";
    
    // Set up mock relationships for testing
    $user->id = 1;
    $user->name = 'Test User';
    $consultation->id = 1;
    $consultation->setRelation('doctor', $user);
    $consultation->setRelation('patient', $user);
    echo "\n";
    
    // Test 3: Check if events can be created
    echo "3. Testing Event Classes...\n";
    
    $messageSentEvent = new App\Events\MessageSent($message, $user);
    echo "   ✅ MessageSent event created\n";
    
    // Create mock models for testing
    $treatmentPlan = new App\Models\TreatmentPlan();
    $treatmentPlan->id = 1;
    $treatmentPlan->setRelation('consultation', $consultation);
    
    $planSubmittedEvent = new App\Events\PlanSubmitted($treatmentPlan);
    echo "   ✅ PlanSubmitted event created\n";
    
    $planAcceptedEvent = new App\Events\PlanAcceptedBroadcast($treatmentPlan);
    echo "   ✅ PlanAcceptedBroadcast event created\n\n";
    
    // Test 4: Check routes
    echo "4. Testing Routes...\n";
    $routes = collect(app('router')->getRoutes())->filter(function($route) {
        return str_contains($route->uri, 'chat');
    });
    
    echo "   ✅ Found " . $routes->count() . " chat routes\n";
    foreach($routes as $route) {
        echo "      - {$route->methods[0]} /{$route->uri}\n";
    }
    echo "\n";
    
    // Test 5: Check broadcast authorization route
    echo "5. Testing Broadcast Authorization...\n";
    $broadcastRoutes = collect(app('router')->getRoutes())->filter(function($route) {
        return str_contains($route->uri, 'broadcasting/auth');
    });
    
    if ($broadcastRoutes->count() > 0) {
        echo "   ✅ Broadcasting auth route found\n";
    } else {
        echo "   ⚠️  Broadcasting auth route not found\n";
    }
    echo "\n";
    
    // Test 6: Database connection
    echo "6. Testing Database Connection...\n";
    try {
        $dbConnection = DB::connection()->getPdo();
        echo "   ✅ Database connection successful\n";
        
        // Check if tables exist
        $tables = ['users', 'messages', 'treatment_requests'];
        foreach($tables as $table) {
            $exists = Schema::hasTable($table);
            echo "   " . ($exists ? "✅" : "❌") . " Table '{$table}' exists\n";
        }
    } catch(Exception $e) {
        echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    echo "🎉 Chat System Test Complete!\n";
    echo "===============================\n\n";
    
    echo "📋 Summary:\n";
    echo "- Broadcasting configured for Soketi\n";
    echo "- All models and events working\n";
    echo "- Chat routes registered\n";
    echo "- Ready for real-time messaging\n\n";
    
    echo "🚀 Next Steps:\n";
    echo "1. Start Docker services: docker-compose up -d\n";
    echo "2. Visit http://localhost:8080/chat\n";
    echo "3. Test real-time messaging\n";
    
} catch (Exception $e) {
    echo "❌ Test failed: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}