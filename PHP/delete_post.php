<?php
session_start();
require 'db.php';

// Check if user is logged in and is admin
$response = ['success' => false, 'message' => 'Unauthorized'];

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if ($user && $user['is_admin']) {
        if (isset($_POST['post_id'])) {
            try {
                // First delete associated comments
                $stmt = $pdo->prepare("DELETE FROM comments WHERE post_id = ?");
                $stmt->execute([$_POST['post_id']]);
                
                // Then delete the post
                $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
                $stmt->execute([$_POST['post_id']]);
                
                $response = ['success' => true];
            } catch (PDOException $e) {
                $response = ['success' => false, 'message' => 'Database error'];
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
