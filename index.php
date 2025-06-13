<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include 'db.php';
$role = $_SESSION['role'];
switch ($role) {
    case 'admin':
        header('Location: dashboard/admin.php');
        break;
    case 'faculty':
        header('Location: dashboard/faculty.php');
        break;
    case 'student':
        header('Location: dashboard/student.php');
        break;
    default:
        echo 'Invalid role.';
}
?> 