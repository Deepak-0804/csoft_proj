<?php

// ENABLE FULL DEBUG OUTPUT (TEMPORARY)
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>CONFIG DEBUG START</h2>";

// SHOW WHAT RENDER IS ACTUALLY PROVIDING
echo "DB_HOST = " . getenv('DB_HOST') . "<br>";
echo "DB_NAME = " . getenv('DB_NAME') . "<br>";
echo "DB_USER = " . getenv('DB_USER') . "<br>";
echo "DB_PASS = " . getenv('DB_PASS') . "<br>";
echo "DB_PORT = " . getenv('DB_PORT') . "<br>";

echo "<hr>";

function env($key, $default = null) {
    return $_ENV[$key] ?? getenv($key) ?? $default;
}

$configArray = [
    'db_host' => env('DB_HOST'),
    'db_name' => env('DB_NAME'),
    'db_user' => env('DB_USER'),
    'db_pass' => env('DB_PASS'),
    'db_port' => env('DB_PORT', 3306),

    'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY'),
    'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY'),

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

echo "<h2>RETURNING ARRAY...</h2>";
var_dump($configArray);

return $configArray;
