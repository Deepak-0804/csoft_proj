<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

if (is_array($data)) {
    extract($data);
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