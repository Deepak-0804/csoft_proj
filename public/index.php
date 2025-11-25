<?php
define('APP_INIT', true);

// Load global things FIRST
require_once __DIR__ . '/../app/auth.php';

// IMPORTANT: define page BEFORE anything else
$page = $_GET['page'] ?? 'home';
$area = $_GET['area'] ?? 'pages';

require_once __DIR__ . '/../app/Services/CareersService.php';
require_once __DIR__ . '/../app/Services/CartService.php';
require_once __DIR__ . '/../app/Services/JobAppliedService.php';
require_once __DIR__ . '/../app/Services/JobDetailsService.php';
require_once __DIR__ . '/../app/Services/LoginService.php';
require_once __DIR__ . '/../app/Services/LogoutService.php';
require_once __DIR__ . '/../app/Services/ProductsService.php';
require_once __DIR__ . '/../app/Services/SignupService.php';
require_once __DIR__ . '/../app/Services/ContactService.php';   // ✅ ADD THIS



// NOW $BASE is guaranteed available
$BASE = rtrim($GLOBALS['config']['base_url'], '/');

// BEFORE ANY HTML — role restrictions
$public_only_pages = ['job-applied', 'cart', 'save_address'];
$admin_pages = ['contactform', 'dashboard', 'roles', 'saverole', 'users', 'deleterole'];

if (in_array($page, $public_only_pages)) {
    require_auth();
    if (!can('public')) {
        header("Location: {$BASE}/index.php?page=login&error=" . urlencode("Access denied."));
        exit;
    }
}

if (in_array($page, $admin_pages)) {
    require_auth();
    if (!can('admin')) {
        header("Location: {$BASE}/index.php?page=login&error=" . urlencode("Admin access required"));
        exit;
    }
}

// Page service handler
switch ($page) {
    case 'login':    $data = LoginService::authenticate(); break;
    case 'logout':   LogoutService::logout(); header("Location: {$BASE}/index.php?page=login"); exit;
    case 'signup':   $data = SignupService::handle(); break;
    case 'contact': ContactService::handleFormSubmit(); break;
    case 'careers':  $data = CareersService::getCareersData(); break;
    case 'cart':     $data = CartService::getCartData(); break;
    case 'job-details': $data = JobDetailsService::getJobDetails($_GET['id'] ?? null); break;
    case 'products': $data = ProductsService::getProductsData(); break;
}

$pagesDir = __DIR__ . '/../pages';

$layout = ($area === 'admin')
    ? "$pagesDir/admin/layout.php"
    : __DIR__ . "/layout.php";

$view = ($area === 'admin')
    ? "$pagesDir/admin/{$page}.php"
    : "$pagesDir/{$page}.php";

if (file_exists($view)) {
    include $layout;
} else {
    include __DIR__ . "/404.php";
}
