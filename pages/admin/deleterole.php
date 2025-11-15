<?php
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/db.php';
require_once __DIR__ . '/../../app/csrf.php';

require_auth();
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
}
?>
