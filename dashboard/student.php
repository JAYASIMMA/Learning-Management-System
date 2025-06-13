<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../assets/navbar.php'; ?>
<?php include '../assets/sidepanel_student.php'; ?>
<div class="with-side-panel">
    <div class="dashboard-container">
        <div class="blue-strap">Student Dashboard</div>
        <ul>
            <li><a href="../attendance.php">View Attendance</a></li>
            <li><a href="../tasks.php">View Tasks & Assignments</a></li>
            <li><a href="../quizzes.php">Take Quizzes</a></li>
            <li><a href="../progress.php">Track Progress</a></li>
            <li><a href="../feedback.php">View Feedback</a></li>
            <li><a href="../marks.php">View Marks</a></li>
            <li><a href="../notice.php">View Notices</a></li>
            <li><a href="../profile.php">Profile</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
</div>
</body>
</html> 