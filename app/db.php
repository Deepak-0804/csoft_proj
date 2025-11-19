<?php

$config = $GLOBALS['config'];

try {
    $dsn = "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => null,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];

    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);

    return $pdo;

} catch (PDOException $e) {
    die("Could not connect: " . $e->getMessage());
}
