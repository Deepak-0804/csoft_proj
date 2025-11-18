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
    $fullname = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Check passwords match
    if ($password !== $confirm_password) {
        $error = showError("Passwords do not match!");
    } else {
        // 2. Check if username already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $error = showError("Username already taken!");
        } else {
            // 3. Get RoleId for public user
            $roleStmt = $pdo->prepare("SELECT RoleId FROM roles WHERE RoleName='public'");
            $roleStmt->execute();
            $role = $roleStmt->fetch();
            $roleId = $role['RoleId'];

            // 4. Insert user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $pdo->prepare("INSERT INTO users (fullname, email, username, password, RoleId) VALUES (?, ?, ?, ?, ?)");
            $insertStmt->execute([$fullname, $email, $username, $hashedPassword, $roleId]);

            $success = showSuccess("Account created successfully! You can now Login.");
        }
    }
}
?>


<div class="signup-container">
    <h2>Sign Up</h2>

    <?php
    if (!empty($error)) echo $error;
    if (!empty($success)) echo $success;
    ?>

    <form method="POST" action="index.php?page=signup">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">

        <div>
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Full Name" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
        </div>
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Username" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <div>
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
        </div>
        <button type="submit">Sign Up</button>
    </form>
    <div class="login-link">
        Already have an account?
        <a href="index.php?page=login">Login</a>
    </div>
</div>