<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: /CM007---Assessment/FRONTEND/index.php");
    exit();
}
include 'db_connect.php';

$book_id = $_POST['book_id'];
$user_id = $_SESSION['user_id'];

// Check user's current loan count
$loan_count_sql = "SELECT COUNT(*) as loan_count FROM loans WHERE user_id = ? AND return_date IS NULL";
$loan_count_stmt = $conn->prepare($loan_count_sql);
$loan_count_stmt->bind_param("i", $user_id);
$loan_count_stmt->execute();
$loan_count_result = $loan_count_stmt->get_result();
$loan_count_row = $loan_count_result->fetch_assoc();
if ($loan_count_row['loan_count'] >= 3) {
    $_SESSION['error'] = "You have reached your loan limit of 3 books.";
    header("Location: /CM007---Assessment/FRONTEND/browse_books.php");
    exit();
}

// Check if the book is available
$sql = "SELECT quantity FROM Books WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    $_SESSION['error'] = "Book not found.";
    header("Location: /CM007---Assessment/FRONTEND/browse_books.php");
    exit();
}
$row = $result->fetch_assoc();
if ($row['quantity'] <= 0) {
    $_SESSION['error'] = "This book is not available for loan.";
    header("Location: /CM007---Assessment/FRONTEND/browse_books.php");
    exit();
}

// Decrease quantity
$new_quantity = $row['quantity'] - 1;
$update_sql = "UPDATE Books SET quantity = ? WHERE book_id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ii", $new_quantity, $book_id);
$update_stmt->execute();

// Insert loan record with 1-month duration
$due_date = date('Y-m-d H:i:s', strtotime('+1 month')); // Changed from +14 days to +1 month
$loan_sql = "INSERT INTO loans (user_id, book_id, borrow_date, due_date) VALUES (?, ?, NOW(), ?)";
$loan_stmt = $conn->prepare($loan_sql);
$loan_stmt->bind_param("iis", $user_id, $book_id, $due_date);
$loan_stmt->execute();

$_SESSION['success'] = "Book loaned successfully.";
header("Location: /CM007---Assessment/FRONTEND/browse_books.php");
exit();
?>