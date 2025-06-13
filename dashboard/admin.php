<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../assets/navbar.php'; ?>
<?php include '../assets/sidepanel_admin.php'; ?>
<div class="with-side-panel">
    <div class="dashboard-container">
        <div class="blue-strap">Admin Dashboard</div>
        <ul>
            <li><a href="../course_management.php">Manage Courses</a></li>
            <li><a href="../department_management.php">Manage Departments</a></li>
            <li><a href="../subject_management.php">Manage Subjects</a></li>
            <li><a href="../user_management.php">Manage Users & Roles</a></li>
            <li><a href="../notice.php">Publish Notices</a></li>
            <li><a href="../reports.php">View Reports</a></li>
            <li><a href="../profile.php">Profile</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
</div>
</body>
</html> 