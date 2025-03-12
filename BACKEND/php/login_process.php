<?php
session_start();
require 'db_connect.php'; 

// Sanitize input
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password']; 
$role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);

// Check if all fields are filled
if (empty($email) || empty($password) || empty($role)) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: ../../FRONTEND/index.php");
    exit;
}

// Fetch user matching email and role
$stmt = $conn->prepare("SELECT user_id, name, password FROM users WHERE email = ? AND role = ?");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify hashed password
    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $role;

        // Redirect based on role
        if ($role === 'admin') {
            header("Location: admin_dashboard.php");
            exit;
        } elseif ($role === 'user') {
            header("Location: user_dashboard.php");
            exit;
        } else {
            // Fallback: redirect to index if the role isn't recognized
            hheader("Location: ../../FRONTEND/index.php");
            exit;
        }
    } else {
        // Incorrect password
        $_SESSION['error'] = "Incorrect password.";
        header("Location: ../../FRONTEND/index.php");
        exit;
    }
} else {
    // No user found with that email and role
    $_SESSION['error'] = "No user found with that email and role.";
    header("Location: ../../FRONTEND/index.php");
    exit;
}
?>
