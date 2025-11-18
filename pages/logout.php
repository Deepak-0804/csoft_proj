<?php
$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');

logout();                   // call the logout function
//header("Location: index.php?page=login");  // redirect to login page
header("Location: {$BASE}/index.php?page=login");

exit;
?>
