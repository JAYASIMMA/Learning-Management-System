<?php
include 'db.php';
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $sql = "INSERT INTO users (username, password, name, email, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $username, $password, $name, $email, $role);
    if ($stmt->execute()) {
        $success = 'Registration successful! You can now <a href="login.php">login</a>.';
    } else {
        $error = 'Registration failed. Username may already exist.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LMS Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Register for LMS Portal</h2>
            <form method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <select name="role" required>
                    <option value="student">Student</option>
                    <option value="faculty">Faculty</option>
                </select>
                <button type="submit">Register</button>
            </form>
            <div class="auth-links">
                <span>Already have an account? <a href="login.php">Login</a></span>
            </div>
            <?php if ($success): ?>
                <div class="auth-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="auth-error"><?php echo $error; ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 