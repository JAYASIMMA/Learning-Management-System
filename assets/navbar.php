<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>
<div class="navbar">
    <div class="nav-title">LMS Portal</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <?php if ($role): ?>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div> 