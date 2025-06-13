<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$feedback = '';
// Post notice
if (($role == 'admin' || $role == 'faculty') && isset($_POST['post_notice'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $target_role = $_POST['target_role'];
    $sql = "INSERT INTO notices (title, content, posted_by, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssis', $title, $content, $user_id, $target_role);
    if ($stmt->execute()) {
        $feedback = 'Notice posted!';
    } else {
        $feedback = 'Error posting notice.';
    }
}
// List notices for this user
if ($role == 'admin' || $role == 'faculty') {
    $notices = $conn->query("SELECT notices.*, users.name AS poster FROM notices JOIN users ON notices.posted_by = users.id ORDER BY date DESC");
} else {
    $notices = $conn->query("SELECT notices.*, users.name AS poster FROM notices JOIN users ON notices.posted_by = users.id WHERE notices.role = 'all' OR notices.role = '$role' ORDER BY date DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">Notices & Announcements</div>
    <?php if ($feedback): ?><div class="module-feedback"><?php echo $feedback; ?></div><?php endif; ?>
    <?php if ($role == 'admin' || $role == 'faculty'): ?>
    <form class="module-form" method="post">
        <label>Title</label>
        <input type="text" name="title" required>
        <label>Content</label>
        <textarea name="content" required></textarea>
        <label>Target Role</label>
        <select name="target_role" required>
            <option value="all">All</option>
            <option value="student">Student</option>
            <option value="faculty">Faculty</option>
        </select>
        <button type="submit" name="post_notice">Post Notice</button>
    </form>
    <?php endif; ?>
    <table class="module-table">
        <tr><th>Title</th><th>Content</th><th>Posted By</th><th>Role</th><th>Date</th></tr>
        <?php while($row = $notices->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['content']); ?></td>
            <td><?php echo htmlspecialchars($row['poster']); ?></td>
            <td><?php echo ucfirst($row['role']); ?></td>
            <td><?php echo $row['date']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html> 