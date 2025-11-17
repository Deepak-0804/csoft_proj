<?php

function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?? $default;
}

return [
    'db_host' => env('DB_HOST'),
    'db_name' => env('DB_NAME'),
    'db_user' => env('DB_USER'),
    'db_pass' => env('DB_PASS'),
    'db_port' => env('DB_PORT', 3306),

    'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY'),

    'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY'),

    // MAIL SETTINGS
    'mail_host'        => env('MAIL_HOST'),
    'mail_port'        => env('MAIL_PORT'),
    'mail_username'    => env('MAIL_USERNAME'),
    'mail_password'    => env('MAIL_PASSWORD'),
    'mail_from'        => env('MAIL_FROM'),
    'mail_from_name'   => env('MAIL_FROM_NAME'),

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret'    => env('PAYPAL_SECRET'),
        'base_url'  => env('PAYPAL_BASE_URL'),
    ],

    'base_url' => env('BASE_URL'),
];

