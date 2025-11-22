<?php

class AuthCheckService {
    public static function check() {
        $result = [];

        if (!is_auth()) {
            return ['logged_in' => false, 'reason' => 'not_logged_in'];
        }

        $timeout = 60;

        if (isset($_SESSION['last_activity']) &&
            (time() - $_SESSION['last_activity']) > $timeout) {

            // expire
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $p = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $p['path'], $p['domain'], $p['secure'], $p['httponly']);
            }

            session_destroy();

            return ['logged_in' => false, 'reason' => 'expired'];
        }

        $_SESSION['last_activity'] = time();

        return ['logged_in' => true];
    }
}
