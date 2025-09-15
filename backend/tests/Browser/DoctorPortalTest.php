<?php

use Laravel\Dusk\Browser;
use Tests\TestHelpers;

test('doctor login and opens plan builder', function () {
    $ctx = TestHelpers::seedDoctorWithRequestCase(); // returns doctor user + treatment_request id

    $this->browse(function (Browser $browser) use ($ctx) {
        $browser->loginAs($ctx->doctor_user, 'web')
            ->visit("/doctor/requests/{$ctx->request->id}/plan")
            ->assertSee('Treatment Plan Builder')
            ->assertPresent('#t11'); // tooth SVG element
    });
});

test('doctor can access dashboard', function () {
    $doctor = TestHelpers::seedDoctor();

    $this->browse(function (Browser $browser) use ($doctor) {
        $browser->loginAs($doctor->user, 'web')
            ->visit('/doctor')
            ->assertSee('Doctor Dashboard')
            ->assertSee('Active Consultations')
            ->assertSee('Pending Requests');
    });
});

test('doctor cannot access admin or clinic panels', function () {
    $doctor = TestHelpers::seedDoctor();

    $this->browse(function (Browser $browser) use ($doctor) {
        $browser->loginAs($doctor->user, 'web')
            ->visit('/clinic')
            ->assertSee('403'); // Should see forbidden page
    });
});

test('doctor can navigate treatment plan builder', function () {
    $ctx = TestHelpers::seedDoctorWithRequestCase();

    $this->browse(function (Browser $browser) use ($ctx) {
        $browser->loginAs($ctx->doctor_user, 'web')
            ->visit("/doctor/requests/{$ctx->request->id}/plan")
            ->assertSee('Treatment Plan Builder')
            ->assertPresent('#t11') // tooth 11
            ->assertPresent('#t21') // tooth 21
            ->assertPresent('#t31') // tooth 31
            ->assertPresent('#t41') // tooth 41
            ->click('#t11') // click on a tooth
            ->assertSee('Treatment Options'); // should show treatment options
    });
});
