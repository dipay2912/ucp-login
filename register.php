<?php
include 'db.php';
require 'mailer_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password !== $confirm) {
        die("Password tidak cocok.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan user (belum diverifikasi)
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $token = bin2hex(random_bytes(32));

        $insert_token = $conn->prepare("INSERT INTO email_verification_tokens (user_id, token) VALUES (?, ?)");
        $insert_token->bind_param("is", $user_id, $token);
        $insert_token->execute();

        if (send_verification_email($email, $token)) {
            echo "Registrasi berhasil! Silakan cek email untuk verifikasi.";
        } else {
            echo "Gagal mengirim email verifikasi.";
        }
    } else {
        echo "Gagal mendaftar: " . $stmt->error;
    }
}
