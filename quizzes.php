<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit();
}
$student_id = $_SESSION['user_id'];
$feedback = '';
// Submit quiz
if (isset($_POST['submit_quiz'])) {
    $quiz_id = $_POST['quiz_id'];
    $answer = $_POST['answer'];
    $sql = "INSERT INTO quiz_submissions (quiz_id, student_id, answer) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE answer=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiss', $quiz_id, $student_id, $answer, $answer);
    if ($stmt->execute()) {
        $feedback = 'Quiz submitted!';
    } else {
        $feedback = 'Submission failed.';
    }
}
// List assigned quizzes
$sql = "SELECT quizzes.*, courses.name AS course_name, subjects.name AS subject_name, qs.answer FROM quizzes JOIN courses ON quizzes.course_id = courses.id JOIN subjects ON quizzes.subject_id = subjects.id LEFT JOIN quiz_submissions qs ON qs.quiz_id = quizzes.id AND qs.student_id = ? WHERE quizzes.course_id IN (SELECT course_id FROM enrollments WHERE student_id = ?) ORDER BY quizzes.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $student_id, $student_id);
$stmt->execute();
$quizzes = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Quizzes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">My Quizzes</div>
    <?php if ($feedback): ?><div class="module-feedback"><?php echo $feedback; ?></div><?php endif; ?>
    <table class="module-table">
        <tr><th>Title</th><th>Course</th><th>Subject</th><th>Action</th></tr>
        <?php while($row = $quizzes->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
            <td>
                <?php if (!$row['answer']): ?>
                <form method="post" class="module-form" style="margin:0;">
                    <input type="hidden" name="quiz_id" value="<?php echo $row['id']; ?>">
                    <textarea name="answer" placeholder="Your answer..." required></textarea>
                    <button type="submit" name="submit_quiz">Submit</button>
                </form>
                <?php else: ?>
                <span>Submitted</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html> 