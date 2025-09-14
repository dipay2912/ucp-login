<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password !== $confirm) {
        die("Password tidak cocok.");
    }

    $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows !== 1) {
        die("Token tidak valid.");
    }

    $stmt->bind_result($user_id);
    $stmt->fetch();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update->bind_param("si", $hashed_password, $user_id);
    $update->execute();

    // Hapus token
    $delete = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
    $delete->bind_param("i", $user_id);
    $delete->execute();

    echo "Password berhasil diubah. <a href='login.html'>Login</a>";
}
