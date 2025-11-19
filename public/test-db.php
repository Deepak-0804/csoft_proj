<?php

require __DIR__ . '/../app/auth.php';

echo "<pre>";

try {
    // Show the PDO object type
    var_dump($GLOBALS['pdo']);

    // Try a simple query to confirm DB connection
    $stmt = $GLOBALS['pdo']->query("SELECT 1");
    echo "\nQuery OK: SELECT 1 returned: ";
    var_dump($stmt->fetch());

} catch (Throwable $e) {
    echo "\nERROR: " . $e->getMessage();
}
