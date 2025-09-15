<?php

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestHelpers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('debug admin login flow', function () {
    // Create user with explicit password
    $admin = User::create([
        'name' => 'Test Admin Manual',
        'email' => 'admin.manual@test.local',
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'role' => 'admin',
        'locale' => 'en',
        'status' => 'active',
    ]);

    echo "Created manual admin: " . $admin->email . " with role: " . $admin->role . "\n";

    // Test authentication outside browser first
    if (auth()->attempt(['email' => $admin->email, 'password' => 'password'])) {
        echo "Manual auth() attempt: SUCCESS\n";
        echo "Authenticated user role: " . auth()->user()->role . "\n";
        auth()->logout();
    } else {
        echo "Manual auth() attempt: FAILED\n";
    }

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->visit('/admin/login')
            ->waitFor('#data\\.email')
            ->type('#data\\.email', $admin->email)
            ->type('#data\\.password', 'password')
            ->press('Sign in')
            ->pause(3000);

        // Handle any alerts
        try {
            $browser->acceptDialog();
            echo "Alert handled\n";
        } catch (\Exception $e) {
            echo "No alert present\n";
        }

        $browser->pause(2000);
        echo "After login URL: " . $browser->driver->getCurrentURL() . "\n";

        // Check if we're successfully logged in by looking for specific elements
        try {
            if ($browser->element('[data-cy="user-menu"]') || $browser->text('Dashboard')) {
                echo "Login appears successful - found dashboard or user menu\n";
            } else {
                echo "Login failed - no dashboard elements found\n";
            }
        } catch (\Exception $e) {
            echo "Could not check for dashboard elements\n";
        }
    });
});
