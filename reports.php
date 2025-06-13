<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
// User counts
$user_counts = $conn->query("SELECT role, COUNT(*) as cnt FROM users GROUP BY role");
// Course count
$course_count = $conn->query("SELECT COUNT(*) as cnt FROM courses")->fetch_assoc();
// Average marks
$avg_marks = $conn->query("SELECT AVG(marks) as avg_marks FROM marks")->fetch_assoc();
// Attendance stats
$attendance = $conn->query("SELECT COUNT(*) as total, SUM(status='present') as present FROM attendance")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">System Reports</div>
    <h3>User Counts</h3>
    <table class="module-table">
        <tr><th>Role</th><th>Count</th></tr>
        <?php while($row = $user_counts->fetch_assoc()): ?>
        <tr>
            <td><?php echo ucfirst($row['role']); ?></td>
            <td><?php echo $row['cnt']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <h3>Course Count</h3>
    <table class="module-table">
        <tr><th>Total Courses</th></tr>
        <tr><td><?php echo $course_count['cnt']; ?></td></tr>
    </table>
    <h3>Average Marks</h3>
    <table class="module-table">
        <tr><th>Average Marks</th></tr>
        <tr><td><?php echo is_null($avg_marks['avg_marks']) ? '-' : round($avg_marks['avg_marks'],1); ?></td></tr>
    </table>
    <h3>Attendance Stats</h3>
    <table class="module-table">
        <tr><th>Attendance %</th></tr>
        <tr><td><?php echo ($attendance['total'] > 0) ? round(($attendance['present']/$attendance['total'])*100,1) : 0; ?>%</td></tr>
    </table>
</div>
</body>
</html> 