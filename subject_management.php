<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
$feedback = '';
// Add Subject
if (isset($_POST['add_subject'])) {
    $name = $_POST['name'];
    $course_id = $_POST['course_id'];
    $sql = "INSERT INTO subjects (name, course_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $name, $course_id);
    if ($stmt->execute()) {
        $feedback = 'Subject added!';
    } else {
        $feedback = 'Error adding subject.';
    }
}
// Delete Subject
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM subjects WHERE id=$id");
    $feedback = 'Subject deleted.';
}
// Edit Subject
if (isset($_POST['edit_subject'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $course_id = $_POST['course_id'];
    $sql = "UPDATE subjects SET name=?, course_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $name, $course_id, $id);
    if ($stmt->execute()) {
        $feedback = 'Subject updated!';
    } else {
        $feedback = 'Error updating subject.';
    }
}
$courses = $conn->query("SELECT * FROM courses");
$subjects = $conn->query("SELECT subjects.*, courses.name AS course_name FROM subjects JOIN courses ON subjects.course_id = courses.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">Subject Management</div>
    <?php if ($feedback): ?><div class="module-feedback"><?php echo $feedback; ?></div><?php endif; ?>
    <form class="module-form" method="post">
        <label>Subject Name</label>
        <input type="text" name="name" required>
        <label>Assign to Course</label>
        <select name="course_id" required>
            <option value="">Select Course</option>
            <?php $courses2 = $conn->query("SELECT * FROM courses"); while($c = $courses2->fetch_assoc()): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" name="add_subject">Add Subject</button>
    </form>
    <table class="module-table">
        <tr><th>ID</th><th>Name</th><th>Course</th><th>Actions</th></tr>
        <?php while($row = $subjects->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this subject?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php if (isset($_GET['edit'])):
        $id = intval($_GET['edit']);
        $edit = $conn->query("SELECT * FROM subjects WHERE id=$id")->fetch_assoc(); ?>
        <form class="module-form" method="post">
            <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
            <label>Subject Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($edit['name']); ?>" required>
            <label>Assign to Course</label>
            <select name="course_id" required>
                <?php $courses2 = $conn->query("SELECT * FROM courses"); while($c = $courses2->fetch_assoc()): ?>
                    <option value="<?php echo $c['id']; ?>" <?php if($c['id']==$edit['course_id']) echo 'selected'; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="edit_subject">Update Subject</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html> 