<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: /CM007---Assessment/FRONTEND/index.php");
    exit();
}
include 'db_connect.php';

// Validate form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$role = $_POST['role'] ?? '';

if (empty($name) || empty($email) || empty($password) || empty($role)) {
    header("Location: /CM007---Assessment/FRONTEND/user_management.php?error=missing_data");
    exit();
}

if (!in_array($role, ['admin', 'user'])) {
    header("Location: /CM007---Assessment/FRONTEND/user_management.php?error=invalid_role");
    exit();
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);
if ($password_hash === false) {
    header("Location: /CM007---Assessment/FRONTEND/user_management.php?error=password_hash_failed");
    exit();
}

// Check if email exists
$check_sql = "SELECT COUNT(*) FROM users WHERE email = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    header("Location: /CM007---Assessment/FRONTEND/user_management.php?error=email_exists");
    exit();
}

// Insert new user
$insert_sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($insert_sql);
$stmt->bind_param("ssss", $name, $email, $password_hash, $role);

if ($stmt->execute()) {
    header("Location: /CM007---Assessment/FRONTEND/user_management.php?success=user_added");
} else {
    header("Location: /CM007---Assessment/FRONTEND/user_management.php?error=add_failed&details=" . urlencode($stmt->error));
}
$stmt->close();
$conn->close();
?>