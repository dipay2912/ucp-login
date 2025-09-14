<?php
include 'db.php';

$token = $_GET['token'] ?? '';

$stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows !== 1) {
    die("Token tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Atur Password Baru</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <form action="update_password.php" method="POST">
      <h2>Password Baru</h2>
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

      <label>Password Baru</label>
      <input type="password" name="password" required>

      <label>Ulangi Password</label>
      <input type="password" name="confirm" required>

      <button type="submit">Ubah Password</button>
    </form>
  </div>
</body>
</html>
