<?php
include 'db.php';
require 'mailer_config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        $token = bin2hex(random_bytes(32));
        $insert = $conn->prepare("INSERT INTO password_resets (user_id, token) VALUES (?, ?)");
        $insert->bind_param("is", $user_id, $token);
        $insert->execute();

        $reset_link = "http://localhost/ucp-samp/set_new_password.php?token=$token";

        // Kirim email
        $mail = new PHPMailer(true);
        try {
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
            $mail->Subject = 'Reset Password UCP SAMP';
            $mail->Body = "Klik link berikut untuk mengubah password: <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            echo "Link reset password sudah dikirim ke email.";
        } catch (Exception $e) {
            echo "Gagal mengirim email: {$mail->ErrorInfo}";
        }

    } else {
        echo "Email tidak ditemukan.";
    }
}
