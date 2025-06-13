<?php
include '../db.php';
// Check if any admin exists
$admin_exists = $conn->query("SELECT id FROM users WHERE role='admin' LIMIT 1")->num_rows > 0;
$feedback = '';
if (!$admin_exists && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $sql = "INSERT INTO users (username, password, name, email, role) VALUES (?, ?, ?, ?, 'admin')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $username, $password, $name, $email);
    if ($stmt->execute()) {
        $feedback = 'Admin registered successfully! You can now <a href="../login.php">login</a>.';
    } else {
        $feedback = 'Registration failed. Username may already exist.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <h2>Admin Registration</h2>
        <?php if ($admin_exists): ?>
            <div class="auth-error">Admin already exists. Registration is disabled.</div>
        <?php else: ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Register Admin</button>
        </form>
        <?php endif; ?>
        <?php if ($feedback): ?>
            <div class="auth-success"><?php echo $feedback; ?></div>
        <?php endif; ?>
    </div>
</div>
</body>
</html> 