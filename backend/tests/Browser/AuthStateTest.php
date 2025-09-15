<?php

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestHelpers;

test('verify auth state after loginAs', function () {
    $admin = TestHelpers::admin();
    echo "Created admin: " . $admin->email . " with role: " . $admin->role . "\n";

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin, 'web');

        // Create a simple test route to check auth state
        $browser->visit('/api/user'); // This should return user info if authenticated
        $pageSource = $browser->driver->getPageSource();
        echo "API user response: " . substr($pageSource, 0, 500) . "\n";

        // Let's check if the issue is the session configuration by visiting a simple auth-protected route
        $browser->visit('/profile'); // Assuming this exists and requires auth
        $profileUrl = $browser->driver->getCurrentURL();
        echo "Profile URL: " . $profileUrl . "\n";

        // The issue might be that the middleware 'web.role:admin' specifically requires admin role
        // Let's see if the admin panel provider is configured correctly
        // by manually testing just that check
    });
});
