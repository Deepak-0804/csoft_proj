<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');

require_once __DIR__ . '/../vendor/autoload.php';

//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
//$dotenv->load();



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$user_email = $_SESSION['user']['email']; // get logged-in user email
$user_name = $_SESSION['user']['name']; // make sure this exists!

$mail = new PHPMailer(true);

try {
    // SMTP settings
    $mail->isSMTP();
    //$mail->Host = $_ENV['MAIL_HOST'];
    $mail->Host = $config['mail_host'];

    $mail->SMTPAuth = true;
    //$mail->Username = $_ENV['MAIL_USERNAME'];
    $mail->Username = $config['mail_username'];

    //$mail->Password = $_ENV['MAIL_PASSWORD'];
    $mail->Password = $config['mail_password'];

    $mail->SMTPSecure = 'tls';
    //mail->Port = $_ENV['MAIL_PORT']; // optionally: (int)$_ENV['MAIL_PORT']
    $mail->Port = $config['mail_port'];


    // Sender and recipient
    //$mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
    $mail->setFrom($config['mail_from'], $config['mail_from_name']);

    $mail->addAddress($user_email); // dynamic user email

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to My App!';
    $mail->Body = '
    <html>
    <body style="font-family: Arial, sans-serif; color: #333;">
        <p>Dear <strong>' . htmlspecialchars($user_name) . '</strong>,</p>
        <p>Thank you for applying for the position with us. We have received your application successfully.</p>
        <p>Our team will review your details and get back to you shortly.</p>
        <p>If you have any questions, feel free to reply to this email.</p>
        <br>
        <p>Best regards,<br>CSOFT Technologies Team</p>
        <img src="https://chandusoft.com/images/logo/chandusoft_logo.png" alt="CSOFT Technologies Logo" style="width:100px; height:auto;">
    </body>
    </html>
    ';

    $mail->send();
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>


<div class="container-fluid vh-100 p-0">
  <img src="assets/images/thankuimg.png" alt="Job Applied" class="img-fluid img-fit">
</div>

<!-- Thank-you message below the image -->
<div class="container text-center my-4">
  <p class="fw-bold fs-5">
    Thank you for Applying  for the Position. We have received your request, and will get back to you soon.
  </p>
</div>
