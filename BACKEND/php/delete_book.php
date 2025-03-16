<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../FRONTEND/index.php");
    exit();
}
include 'db_connect.php';

$book_id = $_POST['book_id'];
$sql = "DELETE FROM Books WHERE book_id = $book_id";
if ($conn->query($sql)) {
    $image_path = "../../FRONTEND/images/books/$book_id.jpg";
    if (file_exists($image_path)) unlink($image_path);
    header("Location: ../../FRONTEND/browse_books.php");
    exit();
}
echo "Error: " . $conn->error;
$conn->close();
?>