<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$feedback = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $sql = "UPDATE users SET name=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $name, $email, $user_id);
    if ($stmt->execute()) {
        $feedback = 'Profile updated successfully!';
    } else {
        $feedback = 'Update failed.';
    }
}
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - LMS Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="module-container">
        <div class="module-title">My Profile</div>
        <?php if ($feedback): ?>
            <div class="module-feedback"><?php echo $feedback; ?></div>
        <?php endif; ?>
        <form class="module-form" method="post">
            <label>Username</label>
            <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
            <label>Full Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <label>Role</label>
            <input type="text" value="<?php echo htmlspecialchars(ucfirst($user['role'])); ?>" disabled>
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html> 