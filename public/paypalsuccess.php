<?php
// include global config + db connection
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';



$paypal = $config['paypal'];
$paypal_order_id = $_GET['token'] ?? null;  // PayPal sends ?token=<order_id>

if (!$paypal_order_id) {
  die("Invalid payment token");
}

// 1. Get access token again
$ch = curl_init();
curl_setopt_array($ch, [
  CURLOPT_URL => $paypal['base_url'] . '/v1/oauth2/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_USERPWD => $paypal['client_id'] . ':' . $paypal['secret'],
  CURLOPT_POSTFIELDS => 'grant_type=client_credentials'
]);
$response = curl_exec($ch);
$accessToken = json_decode($response, true)['access_token'] ?? null;
curl_close($ch);

// 2. Capture the order
$ch = curl_init();
curl_setopt_array($ch, [
  CURLOPT_URL => $paypal['base_url'] . "/v2/checkout/orders/$paypal_order_id/capture",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken"
  ],
  CURLOPT_POST => true
]);
$response = curl_exec($ch);
$data = json_decode($response, true);
curl_close($ch);

$paypal_transaction_id = $data['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;
$status = $data['status'] ?? null;

// 3. Update DB
if ($paypal_transaction_id && $status === 'COMPLETED') {
    $stmt = $pdo->prepare("
        UPDATE order_transactions t join orders o ON t.order_id=o.order_id
        SET t.status='captured', t.paypal_transaction_id=?, o.status='Confirmed'
        WHERE t.paypal_order_id=?
    ");
    $stmt->execute([$paypal_transaction_id, $paypal_order_id]);
    echo "✅ Payment successful! Transaction ID: $paypal_transaction_id";

    // 4. Clear cart and current order
unset($_SESSION['cart']);
unset($_SESSION['current_order_id']);


} else {
    echo "❌ Payment not completed.";
}

