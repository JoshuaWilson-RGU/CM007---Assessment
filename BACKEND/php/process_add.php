<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../FRONTEND/index.php");
    exit();
}

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $quantity = (int)$_POST['quantity'];
    $blurb = $_POST['blurb'] ?? ''; 

    // Check if directory exists, if not create it
    $upload_dir = '../../FRONTEND/assets/covers/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        // Add some debugging
        error_log("Processing file upload: " . $_FILES['cover_image']['name']);
        
        $file_name = uniqid() . '_' . basename($_FILES['cover_image']['name']);
        $file_path = $upload_dir . $file_name;

        // Add more debugging
        error_log("Attempting to move file to: " . $file_path);
        
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $file_path)) {
            error_log("File moved successfully");
            
            $stmt = $conn->prepare("INSERT INTO Books (title, author, genre, quantity, blurb, cover_image, date_added) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssiss", $title, $author, $genre, $quantity, $blurb, $file_name);

            if ($stmt->execute()) {
                error_log("Database record created successfully");
                header("Location: ../../FRONTEND/browse_books.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
                error_log("Database error: " . $stmt->error);
            }
            $stmt->close();
        } else {
            $error = error_get_last();
            echo "Failed to upload image. Error: " . ($error ? $error['message'] : 'Unknown error');
            error_log("Failed to move uploaded file: " . ($error ? $error['message'] : 'Unknown error'));
        }
    } else {
        $upload_error = $_FILES['cover_image']['error'] ?? 'No file uploaded';
        echo "No image uploaded or upload error: " . $upload_error;
        error_log("Upload error: " . $upload_error);
    }
}

$conn->close();
?>