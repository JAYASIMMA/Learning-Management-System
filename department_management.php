<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
$feedback = '';
// Add Department
if (isset($_POST['add_department'])) {
    $name = $_POST['name'];
    $sql = "INSERT INTO departments (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $name);
    if ($stmt->execute()) {
        $feedback = 'Department added!';
    } else {
        $feedback = 'Error adding department.';
    }
}
// Delete Department
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM departments WHERE id=$id");
    $feedback = 'Department deleted.';
}
// Edit Department
if (isset($_POST['edit_department'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $sql = "UPDATE departments SET name=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $name, $id);
    if ($stmt->execute()) {
        $feedback = 'Department updated!';
    } else {
        $feedback = 'Error updating department.';
    }
}
$departments = $conn->query("SELECT * FROM departments");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="module-container">
    <div class="module-title">Department Management</div>
    <?php if ($feedback): ?><div class="module-feedback"><?php echo $feedback; ?></div><?php endif; ?>
    <form class="module-form" method="post">
        <label>Department Name</label>
        <input type="text" name="name" required>
        <button type="submit" name="add_department">Add Department</button>
    </form>
    <table class="module-table">
        <tr><th>ID</th><th>Name</th><th>Actions</th></tr>
        <?php while($row = $departments->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td>
                <a href="?edit=<?php echo $row['id']; ?>">Edit</a> |
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this department?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php if (isset($_GET['edit'])):
        $id = intval($_GET['edit']);
        $edit = $conn->query("SELECT * FROM departments WHERE id=$id")->fetch_assoc(); ?>
        <form class="module-form" method="post">
            <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
            <label>Department Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($edit['name']); ?>" required>
            <button type="submit" name="edit_department">Update Department</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html> 