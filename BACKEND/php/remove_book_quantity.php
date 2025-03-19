<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../FRONTEND/index.php");
    exit();
}
include 'db_connect.php';

if (isset($_POST['book_id']) && isset($_POST['quantity'])) {
    $book_id = $_POST['book_id'];
    $quantity = $_POST['quantity'];

    if (!is_numeric($quantity) || $quantity <= 0) {
        echo "Invalid quantity. Please enter a positive number.";
        exit();
    }

    $stmt = $conn->prepare("UPDATE Books SET quantity = quantity + ? WHERE book_id = ?");
    $stmt->bind_param("ii", $quantity, $book_id);
    if ($stmt->execute()) {
        header("Location: ../../FRONTEND/browse_books.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "Missing book_id or quantity.";
}
$conn->close();
?>