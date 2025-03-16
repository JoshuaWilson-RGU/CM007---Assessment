<?php
session_start();
require 'db_connect.php'; 

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password']; 
$role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);

if (empty($email) || empty($password) || empty($role)) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: ../../FRONTEND/index.php");
    exit;
}

$stmt = $conn->prepare("SELECT user_id, name, password FROM users WHERE email = ? AND role = ?");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $role;

        if ($role === 'admin') {
            header("Location: ../../FRONTEND/admin_dashboard.php");
            exit;
        } elseif ($role === 'user') {
            header("Location: ../../FRONTEND/user_dashboard.php");
            exit;
        } else {
            header("Location: ../../FRONTEND/index.php"); 
            exit;
        }
    } else {
        $_SESSION['error'] = "Incorrect password.";
        header("Location: ../../FRONTEND/index.php");
        exit;
    }
} else {
    $_SESSION['error'] = "No user found with that email and role.";
    header("Location: ../../FRONTEND/index.php");
    exit;
}
?>