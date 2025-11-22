<?php
class LoginService
{

    public static function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => null];
        }

        $pdo = $GLOBALS['pdo'];
        $config = $GLOBALS['config'];
        $BASE = rtrim($config['base_url'], '/');

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("
            SELECT u.id, u.username, u.password, r.RoleName, u.email
            FROM users u
            JOIN roles r ON u.RoleId = r.RoleId
            WHERE u.username = ?
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return ['error' => showError("Invalid username or password")];
        }

        // login user
        login($user['id'], $user['username'], $user['RoleName'], $user['email']);

        // Restore or merge cart
        $userId = $user['id'];

        $stmt = $pdo->prepare("
            SELECT order_id 
            FROM orders 
            WHERE user_id = ? AND status = 'Pending'
            ORDER BY order_date DESC
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        $order = $stmt->fetch();

        $mergedCart = $_SESSION['cart'] ?? [];

        if ($order) {
            $orderId = $order['order_id'];

            $stmtItems = $pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
            $stmtItems->execute([$orderId]);

            while ($item = $stmtItems->fetch(PDO::FETCH_ASSOC)) {
                $pid = $item['product_id'];
                $qty = $item['quantity'];
                $mergedCart[$pid]['quantity'] = ($mergedCart[$pid]['quantity'] ?? 0) + $qty;
            }

            $_SESSION['current_order_id'] = $orderId;
        } else {
            $_SESSION['current_order_id'] = null;
        }

        $_SESSION['cart'] = $mergedCart;

        // Redirect logic
        $redirect = $_GET['redirect'] ?? '';


        if (!empty($redirect) && str_starts_with($redirect, 'index.php')) {
            header("Location: {$BASE}/{$redirect}");
            exit;
        } elseif ($user['RoleName'] === 'admin') {
            //header("Location: index.php?page=dashboard&area=admin");
            header("Location: {$BASE}/index.php?page=dashboard&area=admin");
        } else {
            //header("Location: index.php?page=careers");
            header("Location: {$BASE}/index.php?page=careers");
        }
        exit;
    }

}
