<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$module_id = $_GET['module_id'];
$stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ? AND user_id = ?");
$stmt->execute([$module_id, $_SESSION['user_id']]);
$module = $stmt->fetch();

if (!$module) {
    die("Module not found or you do not have permission to edit this module.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE modules SET name = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$name, $description, $module_id, $_SESSION['user_id']]);

    header("Location: profile.php");
    exit;
}

include 'header.php';
?>

<h1>Edit Module</h1>
<div class="form-container">
    <form method="POST">
        <label>Module Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($module['name']) ?>" required>
        <label>Description:</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($module['description']) ?></textarea>
        <input type="submit" value="Update Module">
    </form>
</div>

<?php include 'footer.php'; ?>
