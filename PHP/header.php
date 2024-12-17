<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="container">
        <a href="index.php" class="logo">Forum</a>
        <nav class="nav-bar">
            <button class="theme-toggle" onclick="toggleTheme()">🌓 Theme</button>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php" class="nav-link">Profile</a>
                <a href="create_module.php" class="nav-link">Create Module</a>
                <a href="create_post.php" class="nav-link">Create Post</a>
                <div class="dropdown">
                    <button class="dropdown-button">Modules</button>
                    <div class="dropdown-content">
                        <?php
                        $stmt = $pdo->query("SELECT * FROM modules ORDER BY created_at DESC");
                        $modules = $stmt->fetchAll();
                        foreach ($modules as $module): ?>
                            <a href="index.php?module_id=<?= $module['id'] ?>" class="dropdown-item">
                                <?= htmlspecialchars($module['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a href="logout.php" class="nav-link">Logout</a>
            <?php else: ?>
                <a href="login.php" class="nav-link">Login</a>
                <a href="register.php" class="nav-link">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
<script>
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

// Set initial theme based on localStorage
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
});
</script>
