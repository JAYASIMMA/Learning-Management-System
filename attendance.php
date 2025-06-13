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
    // Mark attendance
    if (isset($_POST['mark_attendance'])) {
        $subject_id = $_POST['subject_id'];
        $date = $_POST['date'];
        foreach ($_POST['attendance'] as $student_id => $status) {
            $sql = "INSERT INTO attendance (student_id, subject_id, date, status) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE status=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iisss', $student_id, $subject_id, $date, $status, $status);
            $stmt->execute();
        }
        $feedback = 'Attendance marked!';
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
    $attendance = $conn->query("SELECT attendance.*, subjects.name AS subject_name FROM attendance JOIN subjects ON attendance.subject_id = subjects.id WHERE attendance.student_id = $student_id ORDER BY date DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">Attendance</div>
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
            <label>Date</label>
            <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
            <table class="module-table">
                <tr><th>Student</th><th>Status</th></tr>
                <?php foreach ($students as $stu): ?>
                <tr>
                    <td><?php echo htmlspecialchars($stu['name']); ?></td>
                    <td>
                        <select name="attendance[<?php echo $stu['id']; ?>]">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <button type="submit" name="mark_attendance">Mark Attendance</button>
        </form>
        <?php endif; ?>
    <?php elseif ($role == 'student'): ?>
        <table class="module-table">
            <tr><th>Date</th><th>Subject</th><th>Status</th></tr>
            <?php while($row = $attendance->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html> 