<?php
require_once __DIR__ . '/../../app/auth.php';

$id = intval($_GET['id'] ?? 0);
$pdo = $GLOBALS['pdo'];

$stmt = $pdo->prepare("SELECT * FROM contact_form WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($data);
