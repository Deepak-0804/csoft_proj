<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}
?>

<footer>
    <div>
        <img src="../assets/images/Csoft_Logo_3_x_2 Trans.png" alt="Csoft Logo" style="height:40px;">
    </div>
    <div>
        &copy; <?php echo date('Y'); ?> Csoft. All rights reserved.
    </div>
    <div>
        <a href="privacy-policy.php" style="color:#fff; margin:0 10px;">Privacy Policy</a> |
        <a href="terms-of-service.php" style="color:#fff; margin:0 10px;">Terms of Service</a>
    </div>
</footer>