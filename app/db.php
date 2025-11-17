<?php

// ALWAYS load config.php normally (not require_once)
$config = require __DIR__ . '/config.php';

// DEBUG LINE — REMOVE AFTER TESTING
// var_dump($config); exit;

try {
    $pdo = new PDO(
        "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']}",
        $config['db_user'],
        $config['db_pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Could not connect: " . $e->getMessage());
}
