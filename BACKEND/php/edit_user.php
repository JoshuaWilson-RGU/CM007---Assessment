<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: /CM007---Assessment/FRONTEND/index.php");
    exit();
}
include __DIR__ . '/db_connect.php'; // Corrected path

$user_id = $_POST['user_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$role = $_POST['role'];
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

// Update user details
$update_sql = "UPDATE users SET name = ?, email = ?, role = ?";
if ($password) {
    $update_sql .= ", password = ?";
}
$update_sql .= " WHERE user_id = ?";

$stmt = $conn->prepare($update_sql);
if ($stmt) {
    if ($password) {
        $stmt->bind_param("ssssi", $name, $email, $role, $password, $user_id);
    } else {
        $stmt->bind_param("sssi", $name, $email, $role, $user_id);
    }
    if ($stmt->execute()) {
        header("Location: /CM007---Assessment/FRONTEND/user_management.php?success=user_updated");
    } else {
        header("Location: /CM007---Assessment/FRONTEND/user_management.php?error=update_failed");
    }
    $stmt->close();
} else {
    header("Location: /CM007---Assessment/FRONTEND/user_management.php?error=stmt_failed");
}
$conn->close();
?>