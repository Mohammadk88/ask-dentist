<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SendGrid Configuration
    |--------------------------------------------------------------------------
    |
    | SendGrid API configuration for email sending and template management.
    |
    */

    'api_key' => env('SENDGRID_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    */

    'from' => [
        'email' => env('SENDGRID_FROM_EMAIL', env('MAIL_FROM_ADDRESS', 'noreply@askdentist.com')),
        'name' => env('SENDGRID_FROM_NAME', env('MAIL_FROM_NAME', 'Ask Dentist')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Templates
    |--------------------------------------------------------------------------
    |
    | SendGrid dynamic template IDs for different email types
    |
    */

    'templates' => [
        'treatment_plan_accepted' => env('SENDGRID_TEMPLATE_PLAN_ACCEPTED'),
        'treatment_plan_cancelled' => env('SENDGRID_TEMPLATE_PLAN_CANCELLED'),
        'appointment_reminder' => env('SENDGRID_TEMPLATE_APPOINTMENT_REMINDER'),
        'verification' => env('SENDGRID_TEMPLATE_VERIFICATION'),
        'password_reset' => env('SENDGRID_TEMPLATE_PASSWORD_RESET'),
        'welcome' => env('SENDGRID_TEMPLATE_WELCOME'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sandbox Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, emails will be sent in sandbox mode (not delivered)
    | Useful for testing
    |
    */

    'sandbox_mode' => env('SENDGRID_SANDBOX_MODE', false),

];