<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Activity Log Settings
    |--------------------------------------------------------------------------
    |
    | This file contains settings for the Laravel Activity Log package.
    | You can configure logging behavior, database settings, and other
    | options to control how activities are tracked in your application.
    |
    */

    /*
     * If set to false, no activities will be saved to the database.
     */
    'enabled' => env('ACTIVITY_LOGGER_ENABLED', false),

    /*
     * When the clean-command is executed, all recording activities older than
     * the number of days specified here will be deleted.
     */
    'delete_records_older_than_days' => 365,

    /*
     * If no log name is passed to the activity() helper
     * we use this default log name.
     */
    'default_log_name' => 'default',

    /*
     * You can specify an auth driver here that gets user models.
     * If this is null we'll use the default Laravel auth driver.
     */
    'default_auth_driver' => null,

    /*
     * If set to true, the subject returns soft deleted models.
     */
    'subject_returns_soft_deleted_models' => false,

    /*
     * This model will be used to log activity.
     * It should implement the Spatie\Activitylog\Contracts\Activity interface
     * and extend Illuminate\Database\Eloquent\Model.
     */
    'activity_model' => \Spatie\Activitylog\Models\Activity::class,

    /*
     * This is the name of the table that will be created by the migration and
     * used by the Activity model shipped with this package.
     */
    'table_name' => 'activity_log',

    /*
     * This is the database connection that will be used by the migration and
     * the Activity model shipped with this package.
     */
    'database_connection' => env('ACTIVITY_LOGGER_DB_CONNECTION'),

    /*
     * Here you can specify which fields should be searchable when
     * using the ActivitylogServiceProvider's search functionality.
     */
    'searchable_fields' => [
        'log_name',
        'description',
        'subject_type',
        'causer_type',
        'properties',
    ],

    /*
     * When set to true, the package will log all fillable attributes
     * of the subject when it's created.
     */
    'log_fillable' => false,

    /*
     * When set to true, the package will log all changed attributes
     * of the subject when it's updated.
     */
    'log_only_dirty' => true,

    /*
     * When set to true, empty logs will not be submitted.
     */
    'submit_empty_logs' => false,

    /*
     * Define specific events that should be logged for sensitive actions
     */
    'sensitive_actions' => [
        'authentication' => [
            'login' => 'User login attempt',
            'logout' => 'User logout',
            'password_reset' => 'Password reset requested',
            'password_changed' => 'Password changed',
            'failed_login' => 'Failed login attempt',
        ],
        'consultation' => [
            'created' => 'Consultation created',
            'assigned' => 'Consultation assigned to doctor',
            'started' => 'Consultation started',
            'completed' => 'Consultation completed',
            'cancelled' => 'Consultation cancelled',
        ],
        'file_access' => [
            'viewed' => 'File/attachment viewed',
            'downloaded' => 'File/attachment downloaded',
            'uploaded' => 'File/attachment uploaded',
            'deleted' => 'File/attachment deleted',
        ],
        'medical_records' => [
            'viewed' => 'Medical record accessed',
            'updated' => 'Medical record updated',
            'exported' => 'Medical record exported',
        ],
        'permissions' => [
            'role_assigned' => 'User role assigned',
            'permission_granted' => 'Permission granted',
            'permission_revoked' => 'Permission revoked',
        ],
    ],

];
