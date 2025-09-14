<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;

            header("Location: dashboard.php");
            exit;
        } else {
            echo "Password salah.";
        }
    } else {
        echo "Username tidak ditemukan.";
    }

    $stmt = $conn->prepare("SELECT id, password, email_verified FROM users WHERE username = ?");
    ...
    $stmt->bind_result($user_id, $hashed_password, $email_verified);
    ...
    if (!$email_verified) {
        die("Akun belum diverifikasi. Silakan cek email.");
    }


    $stmt->close();
    $conn->close();
}
?>
