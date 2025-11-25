<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class JobAppliedService
{

    public static function handle($jobId)
    {
        $pdo = $GLOBALS['pdo'];
        $config = $GLOBALS['config'];
        $BASE = rtrim($config['base_url'], '/');

        // Must have job id
        if (!$jobId) {
            return ['error' => "Invalid job request"];
        }

        // User must be logged in
        if (!isset($_SESSION['user'])) {
            header("Location: {$BASE}/index.php?page=login&error=" . urlencode("Login required"));
            exit;
        }

        $user_email = $_SESSION['user']['email'];
        $user_name  = $_SESSION['user']['name'];

        // Prevent double email – use session lock
        if (!empty($_SESSION['job_applied_once'][$jobId])) {
            header("Location: {$BASE}/index.php?page=thankyou");
            exit;
        }

        //require_once __DIR__ . '/../../vendor/autoload.php';

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'error_log';


        try {
            $mail->isSMTP();
            $mail->Host = $config['mail_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['mail_username'];
            $mail->Password = $config['mail_password'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = $config['mail_port'];

            $mail->setFrom($config['mail_from'], $config['mail_from_name']);
            $mail->addAddress($user_email);

            $mail->isHTML(true);
            $mail->Subject = "Job Application Received";
            $mail->Body = "
                <p>Dear <strong>{$user_name}</strong>,</p>
                <p>Thank you for applying. We have received your application.</p>
                <p>- CSOFT Technologies</p>
            ";

            $mail->send();

            // Stop double email
            $_SESSION['job_applied_once'][$jobId] = true;

            // Redirect to thank you
            header("Location: {$BASE}/index.php?page=thankyou");
            exit;
        } catch (Exception $e) {
            return ['error' => "Email failed: " . $mail->ErrorInfo];
        }
    }
}
