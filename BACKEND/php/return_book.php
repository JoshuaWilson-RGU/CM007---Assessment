<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../FRONTEND/index.php");
    exit();
}
include 'db_connect.php';

$book_id = $_POST['book_id'];
$user_id = $_SESSION['user_id'];

// Check if the user has an active loan for this book
$loan_sql = "SELECT loan_id FROM loans WHERE user_id = ? AND book_id = ? AND return_date IS NULL LIMIT 1";
$loan_stmt = $conn->prepare($loan_sql);
$loan_stmt->bind_param("ii", $user_id, $book_id);
$loan_stmt->execute();
$loan_result = $loan_stmt->get_result();
if ($loan_result->num_rows == 0) {
    $_SESSION['error'] = "You have not loaned this book.";
    header("Location: /CM007---Assessment/FRONTEND/user_dashboard.php");
    exit();
}
$loan_row = $loan_result->fetch_assoc();
$loan_id = $loan_row['loan_id'];

// Update the loan record
$update_loan_sql = "UPDATE loans SET return_date = NOW() WHERE loan_id = ?";
$update_loan_stmt = $conn->prepare($update_loan_sql);
$update_loan_stmt->bind_param("i", $loan_id);
$update_loan_stmt->execute();

// Increase book quantity
$increase_sql = "UPDATE Books SET quantity = quantity + 1 WHERE book_id = ?";
$increase_stmt = $conn->prepare($increase_sql);
$increase_stmt->bind_param("i", $book_id);
$increase_stmt->execute();

$_SESSION['success'] = "Book returned successfully.";
header("Location: /CM007---Assessment/FRONTEND/user_dashboard.php");
exit();
?>