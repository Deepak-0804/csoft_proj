<?php
require_once __DIR__ . '/../app/auth.php';
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/db.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user']['id'];

// 1️⃣ Get or create pending order
$stmt = $pdo->prepare("SELECT order_id  FROM orders WHERE user_id = ? AND status = 'Pending'");
$stmt->execute([$userId]);
$order = $stmt->fetchColumn();

if (!$order) {
    $pdo->prepare("INSERT INTO orders (user_id, status, order_date) VALUES (?, 'Pending', NOW())")->execute([$userId]);
    $orderId = $pdo->lastInsertId();
} else {
    $orderId = $order;
}

$_SESSION['current_order_id'] = $orderId;

// 2️⃣ Sync items
foreach ($_SESSION['cart'] as $pid => $item) {
    $quantity = $item['quantity'];

    // 1. Fetch the discounted price from products table
    $stmt = $pdo->prepare("SELECT discounted_price FROM products WHERE id = ?");
    $stmt->execute([$pid]);
    $price = $stmt->fetchColumn();

    if (!$price) {
        continue; // product not found, skip
    }

    $subtotal = $price * $quantity;


    // Check if exists
    $chk = $pdo->prepare("SELECT COUNT(*) FROM order_items WHERE order_id=? AND product_id=?");
    $chk->execute([$orderId, $pid]);

    if ($chk->fetchColumn()) {
        // Update
        $upd = $pdo->prepare("UPDATE order_items SET quantity=?,  price=?, subtotal=?, item_modified=NOW() WHERE order_id=? AND product_id=?");
        $upd->execute([$quantity, $price, $subtotal, $orderId, $pid]);
    } else {
        // Insert
        $ins = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal, item_added, item_modified)
                              VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $ins->execute([$orderId, $pid, $quantity, $price, $subtotal]);
    }
}

// ✅ Recalculate total after syncing
$total = 0;
if (!empty($_SESSION['cart'])) {
    $productIds = array_keys($_SESSION['cart']);
    $idsString = implode(',', array_map('intval', $productIds));
    $query = "SELECT id, discounted_price FROM products WHERE id IN ($idsString)";
    $stmt = $pdo->query($query);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pid = $row['id'];
        $quantity = $_SESSION['cart'][$pid]['quantity'];
        $total += $row['discounted_price'] * $quantity;
    }
}




// 3️⃣ Remove deleted items
// ✅ Collect product details for summary
$items = [];
if (!empty($_SESSION['cart'])) {
    $productIds = array_keys($_SESSION['cart']);
    $idsString = implode(',', array_map('intval', $productIds));
    $query = "SELECT id, name, discounted_price, image, base_quantity, order_unit 
              FROM products WHERE id IN ($idsString)";
    $stmt = $pdo->query($query);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pid = $row['id'];
        $quantity = $_SESSION['cart'][$pid]['quantity'];
        $subtotal = $row['discounted_price'] * $quantity;

        $items[] = [
            'id' => $pid,
            'name' => $row['name'],
            'base_quantity' => $row['base_quantity'],
            'order_unit' => $row['order_unit'],
            'image' => $row['image'],
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}

// 3️⃣ Remove deleted items (same as before)
$existing = $pdo->prepare("SELECT product_id FROM order_items WHERE order_id=?");
$existing->execute([$orderId]);
$existingItems = $existing->fetchAll(PDO::FETCH_COLUMN);

foreach ($existingItems as $existingPid) {
    if (!isset($_SESSION['cart'][$existingPid])) {
        $del = $pdo->prepare("DELETE FROM order_items WHERE order_id=? AND product_id=?");
        $del->execute([$orderId, $existingPid]);
    }
}

// ✅ Final JSON
echo json_encode([
    'success' => true,
    'message' => 'Cart synced successfully',
    'orderId' => $orderId,
    'total' => $total,
    'items' => $items
]);
