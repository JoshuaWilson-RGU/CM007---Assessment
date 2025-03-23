<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: /CM007---Assessment/FRONTEND/index.php");
    exit();
}
include '../BACKEND/php/db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch user's currently loaned books
$loaned_books_sql = "SELECT b.book_id, b.title, b.author, l.borrow_date
                     FROM loans l
                     JOIN Books b ON l.book_id = b.book_id
                     WHERE l.user_id = ? AND l.return_date IS NULL";
$loaned_books_stmt = $conn->prepare($loaned_books_sql);
if (!$loaned_books_stmt) {
    die("Prepare failed: " . $conn->error);
}
$loaned_books_stmt->bind_param("i", $user_id);
$loaned_books_stmt->execute();
$loaned_books_result = $loaned_books_stmt->get_result();

// Get the count of loaned books
$loan_count = $loaned_books_result->num_rows;
$max_loans = 3; // Define the maximum loan limit
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library App - User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/indexstyle.css">
</head>
<body>
<div class="app">
    <!-- Top Bar -->
    <div class="top-bar d-flex justify-content-between align-items-center px-3 py-2 bg-dark text-white">
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
        <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#logoutModal">Log Out</button>
    </div>

    <!-- Main Header -->
    <header class="main-header d-flex justify-content-between align-items-center px-3 py-2 bg-white">
        <h1 class="d-flex align-items-center mb-0">
            Library App
            <img width="50" height="50" src="https://img.icons8.com/keek/100/books.png" alt="books" class="ms-2">
        </h1>
        <nav class="ms-auto">
            <ul class="nav">
                <li class="nav-item"><a href="user_dashboard.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="browse_books.php" class="nav-link">Book Catalogue</a></li>
                <li class="nav-item"><a href="#" class="nav-link">About Us</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Log Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../BACKEND/php/logout_process.php" method="post">
                        <p>Are you sure you want to log out?</p>
                        <button type="submit" class="btn btn-dark w-100">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-container flex-grow-1 p-4">
        <div class="container content-wrapper p-4">
            <h2 class="mb-2">Your Dashboard</h2>
            <!-- Display loan tally directly under "Your Dashboard" -->
            <p class="mb-3">Loaned Books: <?php echo $loan_count; ?>/<?php echo $max_loans; ?></p>
            <!-- Warning only when exactly 3 books are loaned -->
            <?php if ($loan_count == $max_loans): ?>
                <div class="alert alert-warning mb-4">You have reached your loan limit of 3 books. Please return a book to loan another.</div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <h3>Your Currently Loaned Books</h3>
                    <?php if ($loaned_books_result->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while ($loan = $loaned_books_result->fetch_assoc()): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo htmlspecialchars($loan['title']); ?></strong> by <?php echo htmlspecialchars($loan['author']); ?><br>
                                        Borrowed on: <?php echo date('d/m/Y', strtotime($loan['borrow_date'])); ?>
                                    </div>
                                    <form method="post" action="../BACKEND/php/return_book.php">
                                        <input type="hidden" name="book_id" value="<?php echo $loan['book_id']; ?>">
                                        <button type="submit" class="btn btn-warning btn-sm">Return</button>
                                    </form>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>You have no currently loaned books.</p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <h3>Browse Books</h3>
                    <p>Explore our book catalogue and loan books.</p>
                    <a href="browse_books.php" class="btn btn-primary">Browse Books</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer text-center p-3 bg-light">
        <hr />
        <p>LibraryApp® 2025</p>
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>