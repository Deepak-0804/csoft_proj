<?php

if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}


$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');
?>
<style>
    header {
        position: sticky;
        top: 0;
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid black;
    }

    nav a {
        margin: 0 15px;
        text-decoration: none;
        color: #333;
        font-weight: bold;
    }
</style>
<header>
    <img src="<?= $BASE ?>/assets/images/Csoft_Logo_3_x_2 Trans.png" alt="Company Logo" width="100">
    <div class="logout-container">
        <img src="<?= $BASE ?>/assets/images/avatar.png" alt="Profile" class="profile-icon">
        <span class="username"><?php echo $_SESSION['user']['name']; ?></span>
        <div class="logout-popup">
            <a href="index.php?page=logout">Logout</a>
        </div>
    </div>
</header>