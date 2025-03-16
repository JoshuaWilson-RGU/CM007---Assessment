<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../FRONTEND/index.php");
    exit();
}
include 'db_connect.php';

$title = $_POST['title'];
$author = $_POST['author'];
$genre = $_POST['genre'];
$isbn = $_POST['isbn'];
$quantity = $_POST['quantity'];

$sql = "INSERT INTO Books (title, author, isbn, genre, quantity) VALUES ('$title', '$author', '$isbn', '$genre', $quantity)";
if ($conn->query($sql)) {
    $book_id = $conn->insert_id;
    $target_file = "../../FRONTEND/images/books/$book_id.jpg";
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    header("Location: ../../FRONTEND/browse_books.php");
    exit();
}
echo "Error: " . $conn->error;
$conn->close();
?>