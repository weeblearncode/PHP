<?php
// db.php
$dsn = 'mysql:host=localhost;dbname=forum;charset=utf8mb4';
$username = 'root'; // Change this to your DB username
$password = ''; // Change this to your DB password

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
