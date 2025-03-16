<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../FRONTEND/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Library App - Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="CSS/indexstyle.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app">
        <!-- Thin Top Bar -->
        <div class="top-bar d-flex justify-content-between align-items-center px-3 py-2 bg-dark text-white">
            <span>Welcome, Admin: <?php echo $_SESSION['name']; ?>!</span>
            <div class="ms-auto">
                <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#logoutModal">Log Out</button>
            </div>
        </div>

        <!-- Main Header -->
        <header class="main-header d-flex justify-content-between align-items-center px-3 py-2 bg-white">
            <h1 class="d-flex align-items-center mb-0">
                Library App
                <img width="50" height="50" src="https://img.icons8.com/keek/100/books.png" alt="books" class="ms-2" />
            </h1>
            <nav class="ms-auto">
                <ul class="nav">
                    <li class="nav-item"><a href="#" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Book Catalogue</a></li>
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
        <main class="main-container flex-grow-1 d-flex justify-content-center align-items-center p-4">
            <div class="content text-center text-white p-3 rounded">
                <h2>Welcome to Your Library</h2>
                <p>Browse, Add, and Manage Your Books with Ease!</p>
                <div class="mt-3">
                    <a href="browse_books.php" class="btn btn-secondary">Browse Books</a>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer text-center p-3 bg-light">
            <hr />
            <p>LibraryApp® 2025</p>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JS (optional, retained from original) -->
    <script src="./js/modaljs.js"></script>
</body>
</html>