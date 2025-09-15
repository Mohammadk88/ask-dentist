<?php

use Laravel\Dusk\Browser;
use Tests\TestHelpers;

test('admin login and sees resources', function () {
    $admin = TestHelpers::admin(); // ensures seeded

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin, 'web')
            ->visit('/admin')
            ->assertPathIs('/admin')
            ->assertSee('Users')
            ->assertSee('Clinics')
            ->assertSee('Stories');
    });
});

test('admin can access user management', function () {
    $admin = TestHelpers::admin();

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin, 'web')
            ->visit('/admin')
            ->clickLink('Users')
            ->assertSee('Create')
            ->assertSee($admin->email); // should see own user
    });
});

test('admin can access clinic management', function () {
    $admin = TestHelpers::admin();

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin, 'web')
            ->visit('/admin')
            ->clickLink('Clinics')
            ->assertSee('Create');
    });
});
