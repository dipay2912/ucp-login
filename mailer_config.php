<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function send_verification_email($email, $token) {
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi SMTP kamu di sini
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'you@example.com';
        $mail->Password   = 'yourpassword';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('you@example.com', 'UCP SAMP');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Verifikasi Email Akun UCP SAMP';

        $verify_link = "http://localhost/ucp-samp/verify.php?token=$token";

        $mail->Body = "
            <h3>Verifikasi Email</h3>
            <p>Silakan klik link berikut untuk mengaktifkan akun kamu:</p>
            <a href='$verify_link'>$verify_link</a>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
