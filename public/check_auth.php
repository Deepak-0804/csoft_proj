<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');

header('Content-Type: application/json');

// If not logged in at all
if (!is_auth()) {
    echo json_encode(['logged_in' => false, 'reason' => 'not_logged_in']);
    exit;
}

$timeout_duration = 60; // 1 minute for testing

// Check if session expired by inactivity
if (isset($_SESSION['last_activity']) &&
    (time() - $_SESSION['last_activity']) > $timeout_duration) {

    // Expire session like in require_auth()
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
                  $params['path'], $params['domain'],
                  $params['secure'], $params['httponly']);
    }
    session_destroy();

    echo json_encode(['logged_in' => false, 'reason' => 'expired']);
    exit;
}

// If session is still valid → update activity timestamp
$_SESSION['last_activity'] = time();

echo json_encode(['logged_in' => true]);
