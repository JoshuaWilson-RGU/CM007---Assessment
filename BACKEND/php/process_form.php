<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and trim inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Determine role: if checkbox is ticked, set role to 'admin', otherwise default to 'user'
    $role = (isset($_POST['role_admin']) && $_POST['role_admin'] === 'admin') ? 'admin' : 'user';

    // Validate required fields
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
        exit;
    }

    // Check that both passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement
    $sql = "INSERT INTO Users (email, password, role, name) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error in statement preparation: " . $conn->error;
        exit;
    }
    $stmt->bind_param("ssss", $email, $hashed_password, $role, $name);

    // Execute and set success message
    if ($stmt->execute()) {
        $_SESSION['success'] = "Sign-up successful! You can now log in.";
        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: ../index.php");
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
