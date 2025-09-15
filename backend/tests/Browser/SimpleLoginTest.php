<?php

use Laravel\Dusk\Browser;

test('simple page visit test', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/admin/login')
            ->pause(5000)
            ->assertSee('Sign in')
            ->screenshot('simple-login-test');
    });
});
