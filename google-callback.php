<?php
session_start();
require_once 'config-google.php';
require 'db.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token["error"])) {
        $client->setAccessToken($token['access_token']);

        $oauth = new Google_Service_Oauth2($client);
        $user_info = $oauth->userinfo->get();

        $google_email = $user_info->email;
        $google_name = $user_info->name;

        // Cek apakah user sudah ada
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->bind_param("s", $google_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Login
            $stmt->bind_result($user_id, $username);
            $stmt->fetch();
        } else {
            // Buat user baru
            $username = explode('@', $google_email)[0];
            $username = substr(preg_replace('/[^a-zA-Z0-9_]/', '', $username), 0, 20);
            $password = password_hash(bin2hex(random_bytes(10)), PASSWORD_DEFAULT);

            $stmt_insert = $conn->prepare("INSERT INTO users (username, password, email, email_verified) VALUES (?, ?, ?, 1)");
            $stmt_insert->bind_param("sss", $username, $password, $google_email);
            $stmt_insert->execute();
            $user_id = $stmt_insert->insert_id;
        }

        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $username;

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Terjadi kesalahan saat login.";
    }
}
