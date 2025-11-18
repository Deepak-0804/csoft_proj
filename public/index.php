<?php

define('APP_INIT', true);
// LOAD AUTH ONCE (this loads config + db)
require_once __DIR__ . '/../app/auth.php';

$page = $_GET['page'] ?? 'home';
$area = $_GET['area'] ?? 'pages';

// where pages are located
$pagesDir = __DIR__ . '/../pages';

if ($area === 'admin') {
    $view   = "$pagesDir/admin/{$page}.php";
    $layout = "$pagesDir/admin/layout.php";
} else {
    $view   = "$pagesDir/{$page}.php";
    $layout = __DIR__ . "/layout.php";
}

if (file_exists($view)) {
    // make $view available inside layout
    include $layout;
} else {
    include __DIR__ . "/404.php";
}

