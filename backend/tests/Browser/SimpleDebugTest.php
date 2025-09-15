<?php

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\TestHelpers;

test('debug loginAs method', function () {
    $admin = TestHelpers::admin();
    echo "Created admin: " . $admin->email . " with role: " . $admin->role . "\n";

    $this->browse(function (Browser $browser) use ($admin) {
        // Use loginAs to authenticate
        $browser->loginAs($admin, 'web');

        // Let me check what the current status is after loginAs
        $browser->visit('/admin')
            ->pause(2000);

        $currentUrl = $browser->driver->getCurrentURL();
        echo "After loginAs, visiting /admin redirects to: " . $currentUrl . "\n";

        // Let's see if the user context is available in the browser
        $pageSource = $browser->driver->getPageSource();

        if (strpos($pageSource, 'admin') !== false) {
            echo "Found 'admin' in page source\n";
        }

        if (strpos($pageSource, 'login') !== false) {
            echo "Found 'login' in page source\n";
        }

        if (strpos($pageSource, 'Dashboard') !== false) {
            echo "Found 'Dashboard' in page source\n";
        }

        if (strpos($pageSource, '403') !== false || strpos($pageSource, 'Forbidden') !== false) {
            echo "Found 403/Forbidden in page source\n";
        }
    });
});
