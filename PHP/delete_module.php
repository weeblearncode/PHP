<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$module_id = $_GET['module_id'];
$stmt = $pdo->prepare("DELETE FROM modules WHERE id = ? AND user_id = ?");
$stmt->execute([$module_id, $_SESSION['user_id']]);

header("Location: profile.php");
exit;
?>
