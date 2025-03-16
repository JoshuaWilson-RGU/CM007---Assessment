<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$is_admin = $_SESSION['role'] === 'admin';
include '../BACKEND/php/db_connect.php';

// Get filter values
$title = $_GET['title'] ?? '';
$genre = $_GET['genre'] ?? '';
$author = $_GET['author'] ?? '';

// Build query
$sql = "SELECT * FROM Books WHERE 1=1";
if ($title) $sql .= " AND title LIKE '%$title%'";
if ($genre) $sql .= " AND genre LIKE '%$genre%'";
if ($author) $sql .= " AND author LIKE '%$author%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/indexstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app">
        <!-- Top Bar -->
        <div class="top-bar d-flex justify-content-between align-items-center px-3 py-2 bg-dark text-white">
            <span>Welcome, <?php echo $_SESSION['name']; ?>!</span>
            <div class="ms-auto">
                <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#logoutModal">Log Out</button>
            </div>
        </div>

        <!-- Main Header -->
        <header class="main-header d-flex justify-content-between align-items-center px-3 py-2 bg-white">
            <h1 class="d-flex align-items-center mb-0">
                Library App
                <img width="50" height="50" src="https://img.icons8.com/keek/100/books.png" alt="books" class="ms-2">
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

        <!-- Main Content -->
        <main class="main-container flex-grow-1 p-4">
            <div class="container content-wrapper p-4">
                <h2 class="mb-4">Browse Books</h2>
                <form method="get" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <input type="text" name="title" placeholder="Title" value="<?php echo $title; ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <select name="genre" class="form-control">
                            <option value="">Select Genre</option>
                            <?php
                            $genreQuery = "SELECT DISTINCT genre FROM Books";
                            $genreResult = $conn->query($genreQuery);
                            if ($genreResult->num_rows > 0) {
                                while ($genreRow = $genreResult->fetch_assoc()) {
                                    $selected = ($genre === $genreRow['genre']) ? 'selected' : '';
                                    echo "<option value=\"{$genreRow['genre']}\" $selected>{$genreRow['genre']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="author" placeholder="Author" value="<?php echo $author; ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
                <?php if ($is_admin): ?>
                    <div class="mb-4">
                        <a href="add_book.php" class="btn btn-primary">Add Book</a>
                    </div>
                <?php endif; ?>
                <div class="cards-container">
                    <div class="row">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="col-md-3 mb-4 d-flex">
                                    <div class="card book-card w-100">
                                        <img src="assets/covers/<?php echo $row['cover_image']; ?>" class="card-img-top" alt="<?php echo $row['title']; ?>">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title text-truncate"><?php echo $row['title']; ?></h5>
                                            <p class="card-text mb-1">Author: <?php echo $row['author']; ?></p>
                                            <p class="card-text mb-1">Genre: <?php echo $row['genre']; ?></p>
                                            <p class="card-text mb-1">Available: <?php echo $row['quantity']; ?></p>
                                            <?php if ($is_admin): ?>
                                                <form method="post" action="../BACKEND/php/delete_book.php" class="mt-auto">
                                                    <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-center">No books found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>

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

        <!-- Footer -->
        <footer class="footer text-center p-3 bg-light">
            <hr>
            <p>LibraryApp® 2025</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php $conn->close(); ?>