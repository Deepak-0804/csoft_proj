<?php
session_name('csoft_session');
//ini_set('session_auto_start',1);
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
$BASE = rtrim($config['base_url'], '/');

// 1️⃣ Create session after login
function login($userId, $name, $role, $email) {
    // ✅ 1. Backup guest cart (if any)
    $guestCart = $_SESSION['cart'] ?? [];

    // ✅ 2. Regenerate session securely
    session_regenerate_id(true); // prevent fixation

    // ✅ 3. Restore session values
    $_SESSION['user'] = [
        'id'    => $userId,
        'name'  => $name,
        'role'  => $role,
        'email' => $email
    ];

    $_SESSION['last_activity'] = time();
    $_SESSION['csrf'] = bin2hex(random_bytes(32));

    // ✅ 4. Restore cart after regenerating session
    if (!empty($guestCart)) {
        $_SESSION['cart'] = $guestCart;
    }
}

// 2️⃣ Logout user
function logout() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"],
                  $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
}

// 3️⃣ Get current logged-in user
function current_user() {
    return $_SESSION['user'] ?? null;
}

// 4️⃣ Check if a user is authenticated
function is_auth() {
    return isset($_SESSION['user']);
}

// 5️⃣ Force authentication for pages
function require_auth() {
    global $BASE;
        $timeout_duration = 500; // 30 minutes

    if (!is_auth()) {
        header("Location: {$BASE}/index.php?page=login&error=" . urlencode("You must login first"));
        exit;
    }

    // Check for inactivity
    if (isset($_SESSION['last_activity']) &&
        (time() - $_SESSION['last_activity']) > $timeout_duration) {
        
        // Session expired due to inactivity
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                      $params['path'], $params['domain'],
                      $params['secure'], $params['httponly']);
        }
        session_destroy();
        header("Location: {$BASE}/index.php?page=login&error=" . urlencode("Session expired. Please login again."));
        exit();
    }

    // Update last activity
    $_SESSION['last_activity'] = time();
}

// 6️⃣ Check user role
function can($role) {
    $u = current_user();
    if (!$u) return false;
    if ($u['role'] === 'admin') return true; // admin can do everything
    return $u['role'] === $role;
}
