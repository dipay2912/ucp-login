<?php
$host = "localhost";
$user = "root";        // Sesuaikan dengan username MySQL
$pass = "";            // Kosong jika pakai XAMPP default
$dbname = "ucp_samp";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
