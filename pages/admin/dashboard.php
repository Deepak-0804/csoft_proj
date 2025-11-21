<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');



?>

<div class="admin-dashboard">
    <h1>Admin Dashboard</h1>
    <div class="dashboard-cards">
        <div class="card">
            <h2>Total Users</h2>
            <p>
                <?php
                $userCountQuery = "SELECT COUNT(*) FROM users WHERE roleid <> 1";
                $userCountStmt = $pdo->query($userCountQuery);
                $userCount = $userCountStmt->fetchColumn();
                echo $userCount;
                ?>
            </p>
        </div>
        <div class="card">
            <h2>Total Roles</h2>
            <p>
                <?php
                $roleCountQuery = "SELECT COUNT(*) FROM roles WHERE roleid <> 1";
                $roleCountStmt = $pdo->query($roleCountQuery);
                $roleCount = $roleCountStmt->fetchColumn();
                echo $roleCount;
                ?>
            </p>
        </div>
        <div class="card">
            <h2>Contact Form Submissions</h2>
            <p>
                <?php
                $contactCountQuery = "SELECT COUNT(*) FROM contact_form";
                $contactCountStmt = $pdo->query($contactCountQuery);
                $contactCount = $contactCountStmt->fetchColumn();
                echo $contactCount;
                ?>
            </p>
        </div>
    </div>