<?php
include 'session_check.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - <?= htmlspecialchars($_SESSION["username"]) ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Selamat datang, <?= htmlspecialchars($_SESSION["username"]) ?>!</h2>
    <p>Ini adalah dashboard UCP SAMP kamu.</p>
    <a href="logout.php">Logout</a>
  </div>
</body>
</html>
