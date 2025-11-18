<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');

/* require_auth();
if (!can('admin')) die("You are not authorized");

if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
    http_response_code(403);
    exit('CSRF validation failed');
}

$roleId = (int) ($_POST['roleId'] ?? 0);
if ($roleId > 0) {
    $stmt = $pdo->prepare("DELETE FROM roles WHERE RoleId = ?");
    if ($stmt->execute([$roleId])) {
        echo "Role deleted successfully.";
    } else {
        echo "Failed to delete role.";
    }
} else {
    echo "Invalid role ID.";
} */

header('Content-Type: application/json');

require_auth();

if (!can('admin')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// CSRF validation
if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF validation failed']);
    exit;
}

$roleId = (int) ($_POST['roleId'] ?? 0);

if ($roleId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid role ID']);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM roles WHERE RoleId = ?");
$ok = $stmt->execute([$roleId]);

echo json_encode([
    'success' => $ok,
    'message' => $ok ? 'Role deleted successfully' : 'Failed to delete role'
]);
