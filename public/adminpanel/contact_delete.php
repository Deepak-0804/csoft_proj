<?php
require_once __DIR__ . '/../../app/auth.php';

$pdo = $GLOBALS['pdo'];

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid id']);
    exit;
}

$stmt = $pdo->prepare("update contact_form set IsArchieved= 0, modifiedt_at = NOW() WHERE id = ?");
$ok = $stmt->execute([$id]);

echo json_encode(['success' => (bool)$ok]);
