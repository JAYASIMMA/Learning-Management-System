<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$role = $_SESSION['role'];
$feedback = '';
if ($role == 'faculty') {
    $faculty_id = $_SESSION['user_id'];
    // Update marks
    if (isset($_POST['update_marks'])) {
        $subject_id = $_POST['subject_id'];
        foreach ($_POST['marks'] as $student_id => $marks) {
            $sql = "INSERT INTO marks (student_id, subject_id, marks) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE marks=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iiii', $student_id, $subject_id, $marks, $marks);
            $stmt->execute();
        }
        $feedback = 'Marks updated!';
    }
    // List subjects
    $subjects = $conn->query("SELECT * FROM subjects WHERE id IN (SELECT subject_id FROM courses WHERE faculty_id = $faculty_id)");
    // List students for selected subject
    $students = [];
    if (isset($_GET['subject_id'])) {
        $subject_id = intval($_GET['subject_id']);
        $students = $conn->query("SELECT users.id, users.name FROM users JOIN enrollments ON users.id = enrollments.student_id WHERE enrollments.course_id = (SELECT course_id FROM subjects WHERE id = $subject_id)");
    }
}
if ($role == 'student') {
    $student_id = $_SESSION['user_id'];
    $marks = $conn->query("SELECT marks.*, subjects.name AS subject_name FROM marks JOIN subjects ON marks.subject_id = subjects.id WHERE marks.student_id = $student_id");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">Marks</div>
    <?php if ($feedback): ?><div class="module-feedback"><?php echo $feedback; ?></div><?php endif; ?>
    <?php if ($role == 'faculty'): ?>
        <form class="module-form" method="get">
            <label>Select Subject</label>
            <select name="subject_id" required onchange="this.form.submit()">
                <option value="">Select</option>
                <?php foreach ($subjects as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php if(isset($_GET['subject_id']) && $_GET['subject_id']==$s['id']) echo 'selected'; ?>><?php echo htmlspecialchars($s['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php if (isset($_GET['subject_id'])): ?>
        <form class="module-form" method="post">
            <input type="hidden" name="subject_id" value="<?php echo $_GET['subject_id']; ?>">
            <table class="module-table">
                <tr><th>Student</th><th>Marks</th></tr>
                <?php foreach ($students as $stu): ?>
                <tr>
                    <td><?php echo htmlspecialchars($stu['name']); ?></td>
                    <td><input type="number" name="marks[<?php echo $stu['id']; ?>]" min="0" max="100" required></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <button type="submit" name="update_marks">Update Marks</button>
        </form>
        <?php endif; ?>
    <?php elseif ($role == 'student'): ?>
        <table class="module-table">
            <tr><th>Subject</th><th>Marks</th></tr>
            <?php while($row = $marks->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                <td><?php echo $row['marks']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html> 