<?php

use Laravel\Dusk\Browser;

test('check what text is on admin login page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/admin/login')
                ->pause(3000) // Wait for page to fully load
                ->dump(); // This will output the page source
    });
});
