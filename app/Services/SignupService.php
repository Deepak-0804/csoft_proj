<?php

class SignupService
{
    public static function handle()
    {
        $pdo = $GLOBALS['pdo'];
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => '', 'success' => ''];
        }

        $fullname = trim($_POST['name']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // 1. Password match check
        if ($password !== $confirm_password) {
            return ['error' => showError("Passwords do not match!"), 'success' => ''];
        }

        // 2. Username availability check
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            return ['error' => showError("Username already taken!"), 'success' => ''];
        }

        // 3. Fetch roleId
        $roleStmt = $pdo->prepare("SELECT RoleId FROM roles WHERE RoleName='public'");
        $roleStmt->execute();
        $role = $roleStmt->fetch();

        if (!$role) {
            return ['error' => showError("Public role missing in database."), 'success' => ''];
        }

        $roleId = $role['RoleId'];

        // 4. Insert new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertStmt = $pdo->prepare("
            INSERT INTO users (fullname, email, username, password, RoleId)
            VALUES (?, ?, ?, ?, ?)
        ");

        $insertStmt->execute([$fullname, $email, $username, $hashedPassword, $roleId]);

        return ['error' => '', 'success' => showSuccess("Account created successfully! You can now Login.")];
    }
}
