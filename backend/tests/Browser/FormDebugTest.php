<?php

use Laravel\Dusk\Browser;

test('debug form with aggressive waiting', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/admin/login')
            ->pause(15000) // Even longer wait
            ->screenshot('admin-login-long-wait');

        // Wait for the page to be fully loaded
        $browser->waitUntilMissing('.loading', 30); // Wait for any loading indicators to disappear

        // Try to wait for any input field to appear
        try {
            $browser->waitFor('input', 30);
            dump("Found at least one input field");
        } catch (Exception $e) {
            dump("No input fields found: " . $e->getMessage());
        }

        // Get all elements and their details
        $allElements = $browser->script('
            const inputs = Array.from(document.querySelectorAll("input"));
            const buttons = Array.from(document.querySelectorAll("button"));
            const forms = Array.from(document.querySelectorAll("form"));

            return {
                inputs: inputs.map(input => ({
                    tagName: input.tagName,
                    type: input.type,
                    name: input.name,
                    id: input.id,
                    className: input.className,
                    placeholder: input.placeholder,
                    outerHTML: input.outerHTML
                })),
                buttons: buttons.map(button => ({
                    tagName: button.tagName,
                    type: button.type,
                    className: button.className,
                    textContent: button.textContent.trim(),
                    outerHTML: button.outerHTML
                })),
                forms: forms.map(form => ({
                    tagName: form.tagName,
                    className: form.className,
                    action: form.action,
                    method: form.method,
                    outerHTML: form.outerHTML
                })),
                bodyHTML: document.body.outerHTML
            };
        ');

        dump("Page elements:", $allElements);
    });
});
