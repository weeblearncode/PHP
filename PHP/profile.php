<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll();

include 'header.php';
?>

<h1><?= htmlspecialchars($user['username']) ?>'s Profile</h1>
<p>Email: <?= htmlspecialchars($user['email']) ?></p>
<p>Member since: <?= $user['created_at'] ?></p>

<h2>Your Posts</h2>
<?php if (empty($posts)): ?>
    <p>You have not created any posts yet.</p>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            <a href="view_post.php?post_id=<?= $post['id'] ?>" class="button">View Post</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'footer.php'; ?>
