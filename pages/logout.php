<?php
require_once __DIR__ . '/../app/auth.php';

logout();                   // call the logout function
header("Location: index.php?page=login");  // redirect to login page
exit;
?>
