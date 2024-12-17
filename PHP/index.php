<?php
session_start();
require 'db.php';

// Fetch all modules (anyone can see)
$stmt = $pdo->query("SELECT * FROM modules ORDER BY created_at DESC");
$modules = $stmt->fetchAll();

// Fetch every posts
$module_id = isset($_GET['module_id']) ? $_GET['module_id'] : null;
if ($module_id) {
    $stmt = $pdo->prepare("SELECT posts.*, users.username, modules.name AS module_name 
                           FROM posts 
                           JOIN users ON posts.user_id = users.id
                           JOIN modules ON posts.module_id = modules.id
                           WHERE posts.module_id = ? 
                           ORDER BY posts.created_at DESC");
    $stmt->execute([$module_id]);
    $posts = $stmt->fetchAll();
} else {
    $stmt = $pdo->query("SELECT posts.*, users.username, modules.name AS module_name 
                         FROM posts 
                         JOIN users ON posts.user_id = users.id
                         JOIN modules ON posts.module_id = modules.id
                         ORDER BY posts.created_at DESC");
    $posts = $stmt->fetchAll();
}

include 'header.php';
?>

<h1>Welcome to the Forum</h1>

<div class="module-navigation">
    <h2>Modules</h2>
    <div class="modules-list">
        <a href="index.php" class="button <?= !$module_id ? 'active' : '' ?>">All Modules</a>
        <?php foreach ($modules as $module): ?>
            <a href="index.php?module_id=<?= $module['id'] ?>" class="button <?= $module_id == $module['id'] ? 'active' : '' ?>">
                <?= htmlspecialchars($module['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="posts-header">
    <h2><?= $module_id ? "Posts in Module: " . htmlspecialchars($modules[array_search($module_id, array_column($modules, 'id'))]['name']) : "All Posts" ?></h2>
    <button class="view-toggle" onclick="toggleView(this)">
        <span class="view-icon">⊞</span>
        <span>Grid View</span>
    </button>
</div>

<?php if (empty($posts)): ?>
    <p>No posts available in this module.</p>
<?php else: ?>
    <div class="posts-container list-view">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="post-header">
                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                    <button class="toggle-post" onclick="togglePost(this)">Hide</button>
                </div>
                <div class="post-content">
                    <p>
                        <strong><?= htmlspecialchars($post['username']) ?></strong> in 
                        <em><?= htmlspecialchars($post['module_name']) ?></em> at <?= $post['created_at'] ?>
                    </p>
                    <?php if ($post['image_path']): ?>
                        <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image">
                    <?php endif; ?>
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    <a href="view_post.php?post_id=<?= $post['id'] ?>" class="button">View Comments</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function togglePost(button) {
    const post = button.closest('.post');
    const content = post.querySelector('.post-content');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        button.textContent = 'Hide';
    } else {
        content.style.display = 'none';
        button.textContent = 'Show';
    }
}

function toggleView() {
    const container = document.querySelector('.posts-container');
    container.classList.toggle('grid-view');
    container.classList.toggle('list-view');
    
    // Save preference
    const isGrid = container.classList.contains('grid-view');
    localStorage.setItem('postsView', isGrid ? 'grid' : 'list');
}

// Set initial view based on localStorage
document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.posts-container');
    const savedView = localStorage.getItem('postsView') || 'list';
    if (savedView === 'grid') {
        container.classList.remove('list-view');
        container.classList.add('grid-view');
    }
});
</script>

<?php include 'footer.php'; ?>
