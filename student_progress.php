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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Progress</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">Student Progress</div>
    <table class="module-table">
        <tr><th>Name</th><th>Username</th><th>Email</th><th>Course</th><th>Tasks</th><th>Quizzes</th><th>Marks</th></tr>
        <?php while($student = $students->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($student['name']); ?></td>
            <td><?php echo htmlspecialchars($student['username']); ?></td>
            <td><?php echo htmlspecialchars($student['email']); ?></td>
            <td><?php echo htmlspecialchars($student['course_name']); ?></td>
            <td>
                <?php
                $sid = $student['id'];
                $tasks = $conn->query("SELECT COUNT(*) as cnt FROM task_submissions WHERE student_id=$sid")->fetch_assoc();
                echo $tasks['cnt'];
                ?>
            </td>
            <td>
                <?php
                $quizzes = $conn->query("SELECT AVG(score) as avg_score FROM quiz_submissions WHERE student_id=$sid")->fetch_assoc();
                echo is_null($quizzes['avg_score']) ? '-' : round($quizzes['avg_score'],1);
                ?>
            </td>
            <td>
                <?php
                $marks = $conn->query("SELECT AVG(marks) as avg_marks FROM marks WHERE student_id=$sid")->fetch_assoc();
                echo is_null($marks['avg_marks']) ? '-' : round($marks['avg_marks'],1);
                ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html> 