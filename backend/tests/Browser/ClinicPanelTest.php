<?php

use Laravel\Dusk\Browser;
use Tests\TestHelpers;

test('clinic manager login and scoped data', function () {
    [$clinic, $mgr] = TestHelpers::seedClinicWithManager();

    $this->browse(function (Browser $browser) use ($mgr, $clinic) {
        $browser->loginAs($mgr, 'web')
            ->visit('/clinic')
            ->assertPathIs('/clinic')
            ->clickLink('Doctors')
            ->assertSee($clinic->name)
            ->assertDontSee('Users'); // not visible in clinic panel
    });
});

test('clinic manager sees only clinic-scoped data', function () {
    [$clinic1, $mgr1] = TestHelpers::seedClinicWithManager();
    [$clinic2, $mgr2] = TestHelpers::seedClinicWithManager();

    $this->browse(function (Browser $browser) use ($mgr1, $clinic1, $clinic2) {
        $browser->loginAs($mgr1, 'web')
            ->visit('/clinic')
            ->assertSee($clinic1->name)
            ->assertDontSee($clinic2->name); // should not see other clinic's data
    });
});

test('clinic manager cannot access admin panel', function () {
    [$clinic, $mgr] = TestHelpers::seedClinicWithManager();

    $this->browse(function (Browser $browser) use ($mgr) {
        $browser->loginAs($mgr, 'web')
            ->visit('/admin')
            ->assertSee('403'); // Should see forbidden page
    });
});
