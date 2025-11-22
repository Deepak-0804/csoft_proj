<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$error = $data['error'] ?? null;
?>

<div class="login-container">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <?= $error ?>
    <?php endif; ?>

    <form method="POST" action="index.php?page=login">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">

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
