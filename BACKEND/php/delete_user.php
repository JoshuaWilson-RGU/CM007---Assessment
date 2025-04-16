<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: /CM007---Assessment/FRONTEND/index.php");
    exit();
}
include __DIR__ . '/db_connect.php';

$user_id = $_POST['user_id'];

// Delete user
$delete_sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($delete_sql); // Prepare the SQL statement
if ($stmt) {
    $stmt->bind_param("i", $user_id); // Bind the parameter
    if ($stmt->execute()) { // Execute the statement
        header("Location: /CM007---Assessment/FRONTEND/user_management.php?success=user_deleted");
    } else {
        header("Location: /CM007---Assessment/FRONTEND/user_management.php?error=delete_failed");
    }
    $stmt->close(); // Close the statement
} else {
    header("Location: /CM007---Assessment/FRONTEND/user_management.php?error=stmt_failed");
}
$conn->close(); // Close the database connection
?>