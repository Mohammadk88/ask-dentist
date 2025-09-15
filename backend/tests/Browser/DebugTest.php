<?php

use Laravel\Dusk\Browser;

test('debug admin login page', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/admin/login')
            ->screenshot('admin-login-page')
            ->dump(); // This will show the page source
    });
});
