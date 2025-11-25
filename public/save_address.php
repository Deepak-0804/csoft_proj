<?php
require_once __DIR__ . '/../app/auth.php';

$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');


header('Content-Type: application/json');

// 1️⃣ Collect data safely
$orderId = $_SESSION['current_order_id'] ?? null;

$userId   = $_SESSION['user']['id'] ?? null;

$fullName = trim($_POST['full_name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$email    = trim($_POST['email'] ?? '');
$stateID  = (int)($_POST['state'] ?? 0);
$districtID = (int)($_POST['district'] ?? 0);
$zip      = trim($_POST['zip'] ?? '');
$flat     = trim($_POST['flat'] ?? '');
$colony   = trim($_POST['colony'] ?? '');
$addressTypeID = (int)($_POST['address_type'] ?? 0);

if (!$orderId || !$userId) {
    echo json_encode(['success' => false, 'message' => 'Missing Order or User']);
    exit;
}

try {
    // 2️⃣ Check if address already exists for this order + user
    $check = $pdo->prepare("
        SELECT OrderAddressID 
        FROM orderaddressdetails 
        WHERE OrderID = :orderId AND UserID = :userId
    ");
    $check->execute([':orderId' => $orderId, ':userId' => $userId]);
    $existing = $check->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // 3️⃣ Update existing address
        $stmt = $pdo->prepare("
            UPDATE orderaddressdetails
            SET FullName = :fullName,
                Phone = :phone,
                Email = :email,
                StateID = :stateID,
                DistrictID = :districtID,
                ZipCode = :zip,
                FlatBuilding = :flat,
                ColonyStreet = :colony,
                AddressTypeID = :addressTypeID,
                ModifiedAt = CURRENT_TIMESTAMP
            WHERE OrderID = :orderId AND UserID = :userId
        ");

        $stmt->execute([
            ':fullName' => $fullName,
            ':phone' => $phone,
            ':email' => $email,
            ':stateID' => $stateID,
            ':districtID' => $districtID,
            ':zip' => $zip,
            ':flat' => $flat,
            ':colony' => $colony,
            ':addressTypeID' => $addressTypeID ?: null,
            ':orderId' => $orderId,
            ':userId' => $userId
        ]);

        $message = 'Address updated successfully.';
    } else {
        // 4️⃣ Insert new address
        $stmt = $pdo->prepare("
            INSERT INTO orderaddressdetails
            (OrderID, UserID, FullName, Phone, Email, StateID, DistrictID, ZipCode, FlatBuilding, ColonyStreet, AddressTypeID)
            VALUES 
            (:orderId, :userId, :fullName, :phone, :email, :stateID, :districtID, :zip, :flat, :colony, :addressTypeID)
        ");

        $stmt->execute([
            ':orderId' => $orderId,
            ':userId' => $userId,
            ':fullName' => $fullName,
            ':phone' => $phone,
            ':email' => $email,
            ':stateID' => $stateID,
            ':districtID' => $districtID,
            ':zip' => $zip,
            ':flat' => $flat,
            ':colony' => $colony,
            ':addressTypeID' => $addressTypeID ?: null,
        ]);

        $message = 'Address saved successfully.';
    }

    echo json_encode(['success' => true, 'message' => $message, 'order_id' => $orderId]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
