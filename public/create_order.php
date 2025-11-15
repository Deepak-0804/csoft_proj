<?php

// include global config + db connection
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';


// load PayPal credentials from config
$paypal = $config['paypal'];

// --- Step 1: Get order info ---
$order_id = $_SESSION['current_order_id'] ?? ($_POST['order_id'] ?? null);

if (!$order_id) {
    http_response_code(400);
    exit(json_encode(['error' => 'Missing order_id (session or POST)']));
}


// --- Step 2: Fetch order and its items ---
$stmt = $pdo->prepare("
    SELECT o.order_id, o.user_id, u.username
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.order_id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    http_response_code(404);
    exit(json_encode(['error' => 'Order not found']));
}

// Fetch all order items
$itemStmt = $pdo->prepare("
    SELECT oi.product_id, p.name, oi.quantity, oi.price, oi.subtotal
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$itemStmt->execute([$order_id]);
$orderItems = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total amount
$totalAmount = 0;
foreach ($orderItems as $item) {
    $totalAmount += $item['subtotal'];
}


// --- Step 3: Get PayPal access token ---
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $paypal['base_url'] . '/v1/oauth2/token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD => $paypal['client_id'] . ':' . $paypal['secret'],
    CURLOPT_POSTFIELDS => 'grant_type=client_credentials'
]);
$response = curl_exec($ch);
if (!$response) exit(json_encode(['error' => 'Error fetching access token: ' . curl_error($ch)]));

$tokenData = json_decode($response, true);
$accessToken = $tokenData['access_token'] ?? null;
curl_close($ch);

if (!$accessToken) {
    http_response_code(500);
    exit(json_encode(['error' => 'Failed to get PayPal access token']));
}


// --- Step 4: Create PayPal order ---
$orderData = [
    'intent' => 'CAPTURE',
    'purchase_units' => [[
        'reference_id' => $order['order_id'],
        'description' => "Order #{$order['order_id']} by {$order['username']}",
        'amount' => [
            'currency_code' => 'USD',   // Change to 'INR' for live mode
            'value' => number_format($totalAmount, 2, '.', '')
        ]
    ]],
    'application_context' => [
        'return_url' => 'https://localhost/csoft_proj/public/paypalsuccess.php',
        'cancel_url' => 'https://localhost/csoft_proj/public/paypalcancel.php'
    ]
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $paypal['base_url'] . '/v2/checkout/orders',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken"
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($orderData)
]);
$response = curl_exec($ch);
if (!$response) exit(json_encode(['error' => 'Error creating PayPal order: ' . curl_error($ch)]));
curl_close($ch);

$paypalResponse = json_decode($response, true);
$paypal_order_id = $paypalResponse['id'] ?? null;

if (!$paypal_order_id) {
    http_response_code(500);
    exit(json_encode(['error' => 'Failed to create PayPal order', 'paypal_response' => $paypalResponse]));
}

// --- Step 5: Insert into order_transactions ---
$stmt = $pdo->prepare("
    INSERT INTO order_transactions 
    (order_id, user_id, amount, paypal_order_id, status, response_json, currency_code) 
    VALUES (?, ?, ?, ?, 'created', ? ,'INR')
");
$stmt->execute([$order['order_id'], $order['user_id'], $totalAmount, $paypal_order_id, json_encode($paypalResponse)]);

// --- Step 6: Send response back to frontend ---
header('Content-Type: application/json');
echo json_encode([
    'paypal_order_id' => $paypal_order_id,
    'paypal_response' => $paypalResponse,
    'items' => $orderItems,
    'total' => $totalAmount
]);