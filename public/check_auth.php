<?php
if (!defined('APP_INIT')) {
    define('APP_INIT', true); // allow standalone API endpoint
}
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/Services/AuthCheckService.php';

header('Content-Type: application/json');

echo json_encode(AuthCheckService::check());
