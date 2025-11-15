<?php
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/db.php';
require_once __DIR__ . '/../../app/csrf.php';


require_auth();  // forces login
if (!can('admin')) {
    die("You are not authorized to access this page.");
}


if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
    http_response_code(403);
    exit('CSRF validation failed');
}

$roleName = trim($_POST['roleName']);
if ($roleName !== '') {
    $stmt = $pdo->prepare("INSERT INTO roles (RoleName) VALUES (?)");
    if ($stmt->execute([$roleName])) {
        echo "Role saved successfully.";
    } else {
        echo "PDO Error: ";
        print_r($stmt->errorInfo());
    }
} else {
    echo "Role name cannot be empty.";
}
?>


