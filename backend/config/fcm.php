<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging Configuration
    |--------------------------------------------------------------------------
    |
    | Firebase Cloud Messaging (FCM) configuration for push notifications.
    |
    */

    'credentials' => [
        /*
         * Path to service account JSON file
         * You can also provide the JSON directly as an array
         */
        'service_account' => env('FCM_SERVICE_ACCOUNT_PATH', storage_path('firebase-service-account.json')),
        
        /*
         * Alternatively, provide credentials as array
         */
        'service_account_array' => env('FCM_SERVICE_ACCOUNT_JSON') ? json_decode(env('FCM_SERVICE_ACCOUNT_JSON'), true) : null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Project Settings
    |--------------------------------------------------------------------------
    */

    'project_id' => env('FCM_PROJECT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Default Notification Settings
    |--------------------------------------------------------------------------
    */

    'default_sound' => 'default',
    'default_icon' => 'ic_notification',
    'default_color' => '#007bff',

    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    |
    | Define different notification channels for different types of notifications
    |
    */

    'channels' => [
        'appointments' => [
            'title' => 'Appointment Notifications',
            'description' => 'Receive notifications about your appointments',
            'importance' => 'high',
        ],
        'treatment_plans' => [
            'title' => 'Treatment Plans',
            'description' => 'Receive notifications about treatment plan updates',
            'importance' => 'high',
        ],
        'messages' => [
            'title' => 'Messages',
            'description' => 'Receive notifications for new messages',
            'importance' => 'normal',
        ],
        'general' => [
            'title' => 'General Notifications',
            'description' => 'General app notifications',
            'importance' => 'normal',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Batch Settings
    |--------------------------------------------------------------------------
    */

    'batch_size' => env('FCM_BATCH_SIZE', 100),
    'retry_attempts' => env('FCM_RETRY_ATTEMPTS', 3),

];