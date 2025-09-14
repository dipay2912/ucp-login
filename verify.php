<?php
include 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT user_id FROM email_verification_tokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        // Update status akun
        $update = $conn->prepare("UPDATE users SET email_verified = 1 WHERE id = ?");
        $update->bind_param("i", $user_id);
        $update->execute();

        // Hapus token
        $delete = $conn->prepare("DELETE FROM email_verification_tokens WHERE user_id = ?");
        $delete->bind_param("i", $user_id);
        $delete->execute();

        echo "Verifikasi berhasil! <a href='login.html'>Login sekarang</a>";
    } else {
        echo "Token tidak valid atau sudah digunakan.";
    }
} else {
    echo "Token tidak ditemukan.";
}
