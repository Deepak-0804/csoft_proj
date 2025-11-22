<?php
class ContactService {

    public static function handleFormSubmit() {
        $pdo = $GLOBALS['pdo'];
        $config = $GLOBALS['config'];
        $BASE = rtrim($config['base_url'], '/');

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => null]; // no submit
        }

        // 1️⃣ Recaptcha validation
        $recaptcha = $_POST['g-recaptcha-response'] ?? '';
        $verify_url = "https://www.google.com/recaptcha/api/siteverify";

        $response = file_get_contents($verify_url . "?secret={$config['recaptcha_secret_key']}&response={$recaptcha}");
        $result = json_decode($response, true);

        if (empty($result['success'])) {
            return ['error' => "Please verify that you are not a robot."];
        }

        // 2️⃣ Extract form fields
        $data = [
            ':first_name'     => $_POST['first-name'] ?? '',
            ':last_name'      => $_POST['last-name'] ?? '',
            ':email'          => $_POST['email'] ?? '',
            ':contact_number' => $_POST['contact'] ?? '',
            ':company_name'   => $_POST['company'] ?? '',
            ':country'        => $_POST['country'] ?? '',
            ':industry'       => $_POST['industry'] ?? '',
            ':services'       => $_POST['services'] ?? '',
            ':referred_by'    => $_POST['referred'] ?? '',
            ':message'        => $_POST['message'] ?? '',
            ':marketing'      => isset($_POST['marketing']) ? 1 : 0,
        ];

        // 3️⃣ Insert into DB
        $sql = "INSERT INTO contact_form 
                (first_name, last_name, email, contact_number, company_name, 
                 country, industry, services, referred_by, message, marketing)
                VALUES 
                (:first_name, :last_name, :email, :contact_number, :company_name,
                 :country, :industry, :services, :referred_by, :message, :marketing)";

        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute($data);

        if ($ok) {
            header("Location: {$BASE}/index.php?page=thankyou");
            exit;
        }

        return ['error' => "Form submission failed. Please try again."];
    }
}
