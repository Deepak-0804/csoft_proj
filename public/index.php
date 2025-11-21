<?php

define('APP_INIT', true);
require_once __DIR__ . '/../app/auth.php';

$page = $_GET['page'] ?? 'home';
$area = $_GET['area'] ?? 'pages';

$public_only_pages = [
    'job-applied',
    'cart',
    'save_address'
];

$admin_pages = [
    'contactform',
    'dashboard',
    'roles',
    'saverole',
    'users',
    'deleterole'
];

// 1️⃣ Public-only pages (user must be logged in + must be public role)
if (in_array($page, $public_only_pages)) {

    require_auth(); // must be logged in

    if (!can('public')) {
        $BASE = rtrim($GLOBALS['config']['base_url'], '/');
        header("Location: {$BASE}/index.php?page=login&error=" . urlencode("Access denied."));
        exit();
    }
}

// 2️⃣ Admin-only pages (must be admin)
if (in_array($page, $admin_pages)) {

    require_auth(); // must be logged in

    if (!can('admin')) {
        $BASE = rtrim($GLOBALS['config']['base_url'], '/');
        header("Location: {$BASE}/index.php?page=login&error=" . urlencode("Admin access required"));
        exit();
    }
}

$pagesDir = __DIR__ . '/../pages';

if ($area === 'admin') {
    $view   = "$pagesDir/admin/{$page}.php";
    $layout = "$pagesDir/admin/layout.php";
} else {
    $view   = "$pagesDir/{$page}.php";
    $layout = __DIR__ . "/layout.php";
}

if (file_exists($view)) {
    include $layout;
} else {
    include __DIR__ . "/404.php";
}
