<?php
$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

$data = json_decode(file_get_contents('php://input'), true);
$productId = (int)($data['product_id'] ?? 0);
$action    = $data['action'] ?? null;

if (!$productId) {
    echo json_encode(['success' => false, 'message' => 'No product id']);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ---- Update session cart ----
switch ($action) {
    case 'add':
    case 'increase':
        $_SESSION['cart'][$productId]['quantity'] =
            ($_SESSION['cart'][$productId]['quantity'] ?? 0) + 1;
        break;

    case 'decrease':
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']--;
            if ($_SESSION['cart'][$productId]['quantity'] <= 0) {
                unset($_SESSION['cart'][$productId]);
                echo json_encode([
                    'success' => true,
                    'quantity' => 0,
                    'cartCount' => array_sum(array_column($_SESSION['cart'], 'quantity')) // total remaining
                ]);
                exit;
            }
        }
        break;
}



// ---- Recalculate quantities and subtotals ----
$quantity = $_SESSION['cart'][$productId]['quantity'] ?? 0;
$cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));

$subtotal = 0;
$totalsubtotal = 0;
$total = 0;


if ($quantity > 0) {
    $sql = "SELECT discounted_price, original_price FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$productId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $subtotal = $row['discounted_price'] * $quantity;
        $totalsubtotal = $row['original_price'] * $quantity;
    }
}

if (!empty($_SESSION['cart'])) {
    $productIds = array_keys($_SESSION['cart']);
    $idsString = implode(',', array_map('intval', $productIds));
    $sql = "SELECT id, discounted_price FROM products WHERE id IN ($idsString)";
    $result = $pdo->query($sql);
    while ($r = $result->fetch(PDO::FETCH_ASSOC)) {
        $pid = $r['id'];
        $qty = $_SESSION['cart'][$pid]['quantity'] ?? 0;
        $total += $r['discounted_price'] * $qty;
    }
}

echo json_encode([
    'success' => true,
    'quantity' => $quantity,
    'cartCount' => $cartCount,
    'subtotal' => $subtotal,
    'totalsubtotal' => $totalsubtotal,
    'total' => $total
]);
