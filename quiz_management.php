<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header('Location: login.php');
    exit();
}
$faculty_id = $_SESSION['user_id'];
$feedback = '';
// Add Quiz
if (isset($_POST['add_quiz'])) {
    $title = $_POST['title'];
    $course_id = $_POST['course_id'];
    $subject_id = $_POST['subject_id'];
    $sql = "INSERT INTO quizzes (title, course_id, subject_id, faculty_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siii', $title, $course_id, $subject_id, $faculty_id);
    if ($stmt->execute()) {
        $feedback = 'Quiz created!';
    } else {
        $feedback = 'Error creating quiz.';
    }
}
// Delete Quiz
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM quizzes WHERE id=$id AND faculty_id=$faculty_id");
    $feedback = 'Quiz deleted.';
}
// Edit Quiz
if (isset($_POST['edit_quiz'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $course_id = $_POST['course_id'];
    $subject_id = $_POST['subject_id'];
    $sql = "UPDATE quizzes SET title=?, course_id=?, subject_id=? WHERE id=? AND faculty_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siiii', $title, $course_id, $subject_id, $id, $faculty_id);
    if ($stmt->execute()) {
        $feedback = 'Quiz updated!';
    } else {
        $feedback = 'Error updating quiz.';
    }
}
// List quizzes for this faculty
$quizzes = $conn->query("SELECT quizzes.*, courses.name AS course_name, subjects.name AS subject_name FROM quizzes JOIN courses ON quizzes.course_id = courses.id JOIN subjects ON quizzes.subject_id = subjects.id WHERE quizzes.faculty_id = $faculty_id");
// For dropdowns
$courses = $conn->query("SELECT * FROM courses WHERE faculty_id = $faculty_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
    function loadSubjects(courseId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_subjects.php?course_id=' + courseId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('subject_select').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
    </script>
</head>
<body>
<div class="module-container">
    <div class="module-title">Quiz Management</div>
    <?php if ($feedback): ?><div class="module-feedback"><?php echo $feedback; ?></div><?php endif; ?>
    <form class="module-form" method="post">
        <label>Quiz Title</label>
        <input type="text" name="title" required>
        <label>Course</label>
        <select name="course_id" required onchange="loadSubjects(this.value)">
            <option value="">Select Course</option>
            <?php while($c = $courses->fetch_assoc()): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
            <?php endwhile; ?>
        </select>
        <label>Subject</label>
        <select name="subject_id" id="subject_select" required>
            <option value="">Select Subject</option>
        </select>
        <button type="submit" name="add_quiz">Create Quiz</button>
    </form>
    <table class="module-table">
        <tr><th>Title</th><th>Course</th><th>Subject</th><th>Actions</th></tr>
        <?php while($row = $quizzes->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
            <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this quiz?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php if (isset($_GET['edit'])):
        $id = intval($_GET['edit']);
        $edit = $conn->query("SELECT * FROM quizzes WHERE id=$id AND faculty_id=$faculty_id")->fetch_assoc();
        $courses2 = $conn->query("SELECT * FROM courses WHERE faculty_id = $faculty_id");
        $subjects2 = $conn->query("SELECT * FROM subjects WHERE course_id = {$edit['course_id']}");
    ?>
        <form class="module-form" method="post">
            <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
            <label>Quiz Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($edit['title']); ?>" required>
            <label>Course</label>
            <select name="course_id" required>
                <?php while($c = $courses2->fetch_assoc()): ?>
                    <option value="<?php echo $c['id']; ?>" <?php if($c['id']==$edit['course_id']) echo 'selected'; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                <?php endwhile; ?>
            </select>
            <label>Subject</label>
            <select name="subject_id" required>
                <?php while($s = $subjects2->fetch_assoc()): ?>
                    <option value="<?php echo $s['id']; ?>" <?php if($s['id']==$edit['subject_id']) echo 'selected'; ?>><?php echo htmlspecialchars($s['name']); ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="edit_quiz">Update Quiz</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html> 