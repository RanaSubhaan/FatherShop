<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Email Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify the email provider configuration for your campaigns.
    | By default, we're set up for SendGrid, but you can customize as needed.
    |
    */
    'mail' => [
        'driver' => env('CAMPAIGN_MAIL_DRIVER', env('MAIL_DRIVER', 'smtp')),
        'host' => env('CAMPAIGN_MAIL_HOST', env('MAIL_HOST', 'smtp.mailersend.net')),
        'port' => env('CAMPAIGN_MAIL_PORT', env('MAIL_PORT', 587)),
        'username' => env('CAMPAIGN_MAIL_USERNAME', env('MAIL_USERNAME')),
        'password' => env('CAMPAIGN_MAIL_PASSWORD', env('MAIL_PASSWORD')),
        'encryption' => env('CAMPAIGN_MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls')),
        'from_address' => env('CAMPAIGN_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS', 'no-reply@example.com')),
        'from_name' => env('CAMPAIGN_MAIL_FROM_NAME', env('MAIL_FROM_NAME', 'FatherShop')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify the queue configuration for sending emails.
    |
    */
    'queue' => [
        'connection' => env('CAMPAIGN_QUEUE_CONNECTION', env('QUEUE_CONNECTION', 'sync')),
        'queue' => env('CAMPAIGN_QUEUE_NAME', 'emails'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Batch Size
    |--------------------------------------------------------------------------
    |
    | The number of emails to process in a single job.
    |
    */
    'batch_size' => env('CAMPAIGN_BATCH_SIZE', 100),

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the route settings for the package API.
    |
    */
    'routes' => [
        'prefix' => 'api/email-campaigns',
        'middleware' => ['api'],
    ],
];