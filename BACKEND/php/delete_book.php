<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../FRONTEND/index.php");
    exit();
}
include 'db_connect.php';

if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $deletion_log = "Attempting to delete book ID: $book_id\n";

    // Fetch the cover_image path from the database
    $stmt_select = $conn->prepare("SELECT cover_image FROM Books WHERE book_id = ?");
    $stmt_select->bind_param("i", $book_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $image_filename = $row['cover_image'];
        
        if (!empty($image_filename)) {
            // Define the base directory where cover images are stored
            $base_cover_path = '/CM007---Assessment/FRONTEND/assets/covers/';
            $full_image_path = $_SERVER['DOCUMENT_ROOT'] . $base_cover_path . $image_filename;
            
            if (file_exists($full_image_path)) {
                if (is_writable($full_image_path)) {
                    if (!unlink($full_image_path)) {
                        $deletion_log .= "Failed to delete file: " . error_get_last()['message'] . "\n";
                    }
                } else {
                    $deletion_log .= "File is not writable - permission denied: $full_image_path\n";
                }
            } else {
                $deletion_log .= "File not found: $full_image_path\n";
            }
        } else {
            $deletion_log .= "No image filename in database\n";
        }
    } else {
        $deletion_log .= "Book not found in database\n";
    }
    
    $stmt_select->close();
    
    // Delete the book from the database
    $stmt_delete = $conn->prepare("DELETE FROM Books WHERE book_id = ?");
    $stmt_delete->bind_param("i", $book_id);
    if ($stmt_delete->execute()) {
        // Success - redirect
        header("Location: ../../FRONTEND/browse_books.php");
        exit();
    } else {
        $deletion_log .= "Database deletion error: " . $conn->error . "\n";
        file_put_contents('file_deletion_log.txt', $deletion_log, FILE_APPEND);
        echo "Error: " . $conn->error;
        echo "<pre>$deletion_log</pre>";
    }
    $stmt_delete->close();
} else {
    echo "Missing book_id.";
}
$conn->close();
?>