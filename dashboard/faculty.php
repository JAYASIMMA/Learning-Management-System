<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../assets/navbar.php'; ?>
<?php include '../assets/sidepanel_faculty.php'; ?>
<div class="with-side-panel">
    <div class="dashboard-container">
        <div class="blue-strap">Faculty Dashboard</div>
        <ul>
            <li><a href="../attendance.php">Mark Attendance</a></li>
            <li><a href="../task_management.php">Manage Tasks & Assignments</a></li>
            <li><a href="../quiz_management.php">Manage Quizzes</a></li>
            <li><a href="../student_progress.php">Student Progress</a></li>
            <li><a href="../performance_alerts.php">Performance Alerts (AI)</a></li>
            <li><a href="../marks.php">Update Student Marks</a></li>
            <li><a href="../notice.php">Post Notices</a></li>
            <li><a href="../profile.php">Profile</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
</div>
</body>
</html> 