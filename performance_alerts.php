<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header('Location: login.php');
    exit();
}
$faculty_id = $_SESSION['user_id'];
// Get students in faculty's courses
$sql = "SELECT users.id, users.name, users.username, users.email, courses.name AS course_name FROM users JOIN enrollments ON users.id = enrollments.student_id JOIN courses ON enrollments.course_id = courses.id WHERE courses.faculty_id = ? AND users.role = 'student'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $faculty_id);
$stmt->execute();
$students = $stmt->get_result();
function is_at_risk($conn, $sid) {
    $marks = $conn->query("SELECT AVG(marks) as avg_marks FROM marks WHERE student_id=$sid")->fetch_assoc();
    $attendance = $conn->query("SELECT COUNT(*) as total, SUM(status='present') as present FROM attendance WHERE student_id=$sid")->fetch_assoc();
    $tasks = $conn->query("SELECT COUNT(*) as total, SUM(status='submitted') as submitted FROM task_submissions WHERE student_id=$sid")->fetch_assoc();
    $risk = [];
    if ($marks['avg_marks'] !== null && $marks['avg_marks'] < 40) $risk[] = 'Low Marks';
    if ($attendance['total'] > 0 && ($attendance['present']/$attendance['total']) < 0.7) $risk[] = 'Low Attendance';
    if ($tasks['total'] > 0 && ($tasks['submitted']/$tasks['total']) < 0.7) $risk[] = 'Overdue Tasks';
    return $risk;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Alerts</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">Performance Alerts (AI)</div>
    <table class="module-table">
        <tr><th>Name</th><th>Username</th><th>Email</th><th>Course</th><th>Alerts</th></tr>
        <?php while($student = $students->fetch_assoc()):
            $alerts = is_at_risk($conn, $student['id']);
            if (count($alerts) > 0): ?>
        <tr>
            <td><?php echo htmlspecialchars($student['name']); ?></td>
            <td><?php echo htmlspecialchars($student['username']); ?></td>
            <td><?php echo htmlspecialchars($student['email']); ?></td>
            <td><?php echo htmlspecialchars($student['course_name']); ?></td>
            <td><?php echo implode(', ', $alerts); ?></td>
        </tr>
        <?php endif; endwhile; ?>
    </table>
    <div class="module-alert">Students listed above are flagged for low marks, attendance, or overdue tasks. Please consider intervention.</div>
</div>
</body>
</html> 