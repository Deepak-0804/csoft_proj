<?php
//require_once $_SERVER['DOCUMENT_ROOT'] . '/csoft_proj/app/auth.php';
require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/db.php';
require_once __DIR__ . '/../../app/csrf.php';


require_auth();  // forces login
if (!can('admin')) {
    die("You are not authorized to access this page.");
}



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