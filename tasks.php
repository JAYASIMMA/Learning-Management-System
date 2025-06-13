<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit();
}
$student_id = $_SESSION['user_id'];
$feedback = '';
// Submit task
if (isset($_POST['submit_task'])) {
    $task_id = $_POST['task_id'];
    $answer = $_POST['answer'];
    $sql = "INSERT INTO task_submissions (task_id, student_id, answer, status) VALUES (?, ?, ?, 'submitted') ON DUPLICATE KEY UPDATE answer=?, status='submitted'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiss', $task_id, $student_id, $answer, $answer);
    if ($stmt->execute()) {
        $feedback = 'Task submitted!';
    } else {
        $feedback = 'Submission failed.';
    }
}
// List assigned tasks
$sql = "SELECT tasks.*, courses.name AS course_name, subjects.name AS subject_name, ts.status, ts.answer FROM tasks JOIN courses ON tasks.course_id = courses.id JOIN subjects ON tasks.subject_id = subjects.id LEFT JOIN task_submissions ts ON ts.task_id = tasks.id AND ts.student_id = ? WHERE tasks.course_id IN (SELECT course_id FROM enrollments WHERE student_id = ?) ORDER BY tasks.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $student_id, $student_id);
$stmt->execute();
$tasks = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks & Assignments</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">My Tasks & Assignments</div>
    <?php if ($feedback): ?><div class="module-feedback"><?php echo $feedback; ?></div><?php endif; ?>
    <table class="module-table">
        <tr><th>Title</th><th>Course</th><th>Subject</th><th>Description</th><th>Status</th><th>Action</th></tr>
        <?php while($row = $tasks->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo $row['status'] ? ucfirst($row['status']) : 'Pending'; ?></td>
            <td>
                <?php if ($row['status'] !== 'submitted'): ?>
                <form method="post" class="module-form" style="margin:0;">
                    <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                    <textarea name="answer" placeholder="Your answer..." required><?php echo htmlspecialchars($row['answer']); ?></textarea>
                    <button type="submit" name="submit_task">Submit</button>
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