<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Passport Guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify which authentication guard Passport will use when
    | authenticating users. This value should correspond with one of your
    | guards that is already present in your "auth" configuration file.
    |
    */

    'guard' => 'web',

    /*
    |--------------------------------------------------------------------------
    | Encryption Keys
    |--------------------------------------------------------------------------
    |
    | Passport uses encryption keys to generate secure access tokens. These
    | keys are loaded by default from the "storage" directory but they may
    | also be loaded from environment variables. See the documentation.
    |
    */

    'private_key' => env('PASSPORT_PRIVATE_KEY'),

    'public_key' => env('PASSPORT_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Client UUIDs
    |--------------------------------------------------------------------------
    |
    | By default, Passport uses auto-incrementing primary keys when assigning
    | IDs to clients. However, if Passport is installed using the provided
    | --uuids switch, this will be set to "true" and UUIDs will be used.
    |
    */

    'client_uuids' => false,

    /*
    |--------------------------------------------------------------------------
    | Personal Access Client
    |--------------------------------------------------------------------------
    |
    | If you enable client hashing, you should set the personal access client
    | ID and unhashed secret within your environment file. The values will
    | get used while issuing fresh personal access tokens to your users.
    |
    */

    'personal_access_client' => [
        'id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),
        'secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Grant Client
    |--------------------------------------------------------------------------
    |
    | If you enable client hashing, you should set the password grant client
    | ID and unhashed secret within your environment file. The values will
    | get used while issuing fresh tokens to your users via password grants.
    |
    */

    'password_client' => [
        'id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
        'secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Passport Storage Driver
    |--------------------------------------------------------------------------
    |
    | This configuration value allows you to customize the storage options
    | for Passport, such as the database connection that should be used
    | by Passport's internal database models which store tokens, etc.
    |
    */

    'storage' => [
        'database' => [
            'connection' => env('PASSPORT_CONNECTION', env('DB_CONNECTION', 'pgsql')),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Expiration
    |--------------------------------------------------------------------------
    |
    | Here you may define the expiration time for your tokens. The default
    | is one year. Of course, this will be used when generating personal
    | access tokens that are not assigned a specific expiration time.
    |
    */

    'expiration_times' => [
        'access_token' => env('PASSPORT_ACCESS_TOKEN_EXPIRY', 60 * 24), // 24 hours
        'refresh_token' => env('PASSPORT_REFRESH_TOKEN_EXPIRY', 60 * 24 * 30), // 30 days
        'personal_access_token' => env('PASSPORT_PERSONAL_ACCESS_TOKEN_EXPIRY', 60 * 24 * 365), // 1 year
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Scopes
    |--------------------------------------------------------------------------
    |
    | This option controls the default scopes that will be applied to tokens
    | issued by Passport. You may define any scopes that your application
    | supports in the scopes array and specify the default ones here.
    |
    */

    'default_scope' => null,

    'scopes' => [
        'read-profile' => 'Read user profile information',
        'write-profile' => 'Update user profile information',
        'read-consultations' => 'Read consultation data',
        'write-consultations' => 'Create and update consultations',
        'read-messages' => 'Read consultation messages',
        'write-messages' => 'Send messages in consultations',
        'read-medical-records' => 'Read medical records and history',
        'write-medical-records' => 'Update medical records',
        'admin-access' => 'Administrative access to all resources',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pruning
    |--------------------------------------------------------------------------
    |
    | This configuration controls if and when Passport will prune expired
    | tokens from your database. Tokens are considered expired when they
    | are older than the configured expiration time.
    |
    */

    'pruning' => [
        'enabled' => true,
        'keep_expired_for' => env('PASSPORT_KEEP_EXPIRED_TOKENS_FOR', 60 * 24 * 7), // 7 days
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Hashing
    |--------------------------------------------------------------------------
    |
    | Passport allows you to hash client secrets before storing them in the
    | database, similar to how Laravel hashes passwords. When this option
    | is enabled, make sure you never display the plain text secret.
    |
    */

    'hash_client_secrets' => env('PASSPORT_HASH_CLIENT_SECRETS', true),

];
