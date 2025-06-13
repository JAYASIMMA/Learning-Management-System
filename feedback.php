<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit();
}
$student_id = $_SESSION['user_id'];
// Get feedback for tasks
$task_feedback = $conn->query("SELECT t.title, ts.feedback FROM task_submissions ts JOIN tasks t ON ts.task_id = t.id WHERE ts.student_id = $student_id AND ts.feedback IS NOT NULL");
// Get feedback for quizzes
$quiz_feedback = $conn->query("SELECT q.title, qs.feedback FROM quiz_submissions qs JOIN quizzes q ON qs.quiz_id = q.id WHERE qs.student_id = $student_id AND qs.feedback IS NOT NULL");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Feedback</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">My Feedback</div>
    <h3>Task Feedback</h3>
    <table class="module-table">
        <tr><th>Task</th><th>Feedback</th></tr>
        <?php while($row = $task_feedback->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['feedback']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <h3>Quiz Feedback</h3>
    <table class="module-table">
        <tr><th>Quiz</th><th>Feedback</th></tr>
        <?php while($row = $quiz_feedback->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['feedback']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html> 