<?php
if (!defined('APP_INIT')) {
        http_response_code(403);
        exit("Access denied");
}

$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT u.id, u.username, u.password, r.RoleName , u.email 
                                                FROM users u 
                                                JOIN roles r ON u.RoleId = r.RoleId
                                                WHERE u.username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
                login($user['id'], $user['username'], $user['RoleName'], $user['email']); // create session

                // --- Restore or merge old cart ---
                $userId = $user['id'];

                // 1. Check for existing pending order
                $stmt = $pdo->prepare("SELECT order_id FROM orders WHERE user_id = ? AND status = 'Pending' ORDER BY order_date DESC LIMIT 1");
                $stmt->execute([$userId]);
                $order = $stmt->fetch();

                $mergedCart = $_SESSION['cart'] ?? [];

                // 2. If pending order found, merge its items
                if ($order) {
                        $orderId = $order['order_id'];
                        $stmtItems = $pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
                        $stmtItems->execute([$orderId]);

                        while ($item = $stmtItems->fetch(PDO::FETCH_ASSOC)) {
                                $pid = $item['product_id'];
                                $qty = $item['quantity'];
                                // Merge quantities if exists, else add new
                                $mergedCart[$pid]['quantity'] = ($mergedCart[$pid]['quantity'] ?? 0) + $qty;
                        }

                        // Store order_id in session (for future sync)
                        $_SESSION['current_order_id'] = $orderId;
                } else {
                        $_SESSION['current_order_id'] = null; // no existing pending order
                }

                $_SESSION['cart'] = $mergedCart;


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
        } else {
                $error = "Invalid username or password";
                $error = showError($error);
        }
}

if (is_auth()) {
        // Clear session
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                        session_name(),
                        '',
                        time() - 42000,
                        $params['path'],
                        $params['domain'],
                        $params['secure'],
                        $params['httponly']
                );
        }
        session_destroy();
        // Optionally, redirect to login to refresh page
        //header("Location: /csoft_proj/public/index.php?page=login");
        header("Location: {$BASE}/index.php?page=login");

        exit;
}
?>

<div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
                <?php echo $error; ?>
        <?php endif; ?>
        <form method="POST" action="index.php?page=login">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">
                <div>
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Login</button>
        </form>
        <div class="signup-link">
                Don't have an account?
                <a href="index.php?page=signup">Sign Up</a>
        </div>
</div>