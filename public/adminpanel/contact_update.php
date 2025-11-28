<?php
require_once __DIR__ . '/../../app/auth.php';

$pdo = $GLOBALS['pdo'];

$id = $_POST['id'];
$first = $_POST['first_name'];
$last = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['contact_number'];
$company = $_POST['company_name'];
$message = $_POST['message'];


$sql = "UPDATE contact_form SET 
        first_name=?, last_name=?, email=?, contact_number=?, company_name=?, message=?, modifiedt_at = NOW()
         WHERE id=?";

$stmt = $pdo->prepare($sql);
$ok = $stmt->execute([$first, $last, $email, $phone, $company, $message, $id]);

echo json_encode(["success" => $ok]);
