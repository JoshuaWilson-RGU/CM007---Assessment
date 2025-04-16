<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: /CM007---Assessment/FRONTEND/index.php");
    exit();
}
include '../BACKEND/php/db_connect.php';

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    header("Location: user_management.php");
    exit();
}
$user_id = $_GET['user_id'];

$sql_user = "SELECT name FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if ($result_user->num_rows == 0) {
    header("Location: user_management.php");
    exit();
}
$user = $result_user->fetch_assoc();
$user_name = $user['name'];

$sql_loans = "SELECT b.title, l.borrow_date, l.due_date, l.return_date
              FROM loans l
              JOIN books b ON l.book_id = b.book_id
              WHERE l.user_id = ?
              ORDER BY l.borrow_date DESC";
$stmt_loans = $conn->prepare($sql_loans);
$stmt_loans->bind_param("i", $user_id);
$stmt_loans->execute();
$result_loans = $stmt_loans->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library App - User Loans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/indexstyle.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app">
        <div class="top-bar d-flex justify-content-between align-items-center px-3 py-2 bg-dark text-white">
            <span>Welcome, Admin: <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
            <div class="ms-auto">
                <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#logoutModal">Log Out</button>
            </div>
        </div>
        <header class="main-header d-flex justify-content-between align-items-center px-3 py-2 bg-white">
            <h1 class="d-flex align-items-center mb-0">
                Library App
                <img width="50" height="50" src="https://img.icons8.com/keek/100/books.png" alt="books" class="ms-2" />
            </h1>
            <nav class="ms-auto">
                <ul class="nav">
                    <li class="nav-item"><a href="admin_dashboard.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="browse_books.php" class="nav-link">Book Management</a></li>
                    <li class="nav-item"><a href="user_management.php" class="nav-link active">User Management</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">About Us</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Contact Us</a></li>
                </ul>
            </nav>
        </header>
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
        <main class="main-container p-4">
            <div class="container content-wrapper p-4">
                <h2 class="text-center mb-4">Loan History for <?php echo htmlspecialchars($user_name); ?></h2>
                <?php if ($result_loans->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Borrow Date</th>
                                    <th>Due Date</th>
                                    <th>Return Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($loan = $result_loans->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($loan['title']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($loan['borrow_date'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($loan['due_date'])); ?></td>
                                        <td><?php echo $loan['return_date'] ? date('d/m/Y', strtotime($loan['return_date'])) : 'N/A'; ?></td>
                                        <td>
                                            <?php
                                            if ($loan['return_date']) {
                                                echo 'Returned';
                                            } elseif (strtotime($loan['due_date']) < time()) {
                                                echo 'Overdue';
                                            } else {
                                                echo 'On Loan';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center">No loans found for this user.</p>
                <?php endif; ?>
            </div>
        </main>
        <footer class="footer text-center p-3 bg-light">
            <hr />
            <p>LibraryApp® 2025</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>