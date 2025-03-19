<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../FRONTEND/index.php");
    exit();
}
include 'db_connect.php';

if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    // Fetch the cover_image path from the database
    $stmt_select = $conn->prepare("SELECT cover_image FROM Books WHERE book_id = ?");
    $stmt_select->bind_param("i", $book_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if ($row = $result->fetch_assoc()) {
        $image_path = $row['cover_image'];
        // Delete the image file if it exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    $stmt_select->close();

    // Delete the book from the database
    $stmt_delete = $conn->prepare("DELETE FROM Books WHERE book_id = ?");
    $stmt_delete->bind_param("i", $book_id);
    if ($stmt_delete->execute()) {
        header("Location: ../../FRONTEND/browse_books.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt_delete->close();
} else {
    echo "Missing book_id.";
}
$conn->close();
?>