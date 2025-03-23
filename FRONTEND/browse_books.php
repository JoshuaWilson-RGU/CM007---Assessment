<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /CM007---Assessment/FRONTEND/index.php");
    exit();
}
$is_admin = $_SESSION['role'] === 'admin';
include '../BACKEND/php/db_connect.php';

// Fetch user's active loans if not admin
if (!$is_admin) {
    $user_id = $_SESSION['user_id'];
    $loan_sql = "SELECT book_id, COUNT(*) as loaned_count FROM loans WHERE user_id = ? AND return_date IS NULL GROUP BY book_id";
    $loan_stmt = $conn->prepare($loan_sql);
    $loan_stmt->bind_param("i", $user_id);
    $loan_stmt->execute();
    $loan_result = $loan_stmt->get_result();
    $user_loans = [];
    while ($loan_row = $loan_result->fetch_assoc()) {
        $user_loans[$loan_row['book_id']] = $loan_row['loaned_count'];
    }
}

// Get filter and sort values
$title = $_GET['title'] ?? '';
$genre = $_GET['genre'] ?? '';
$author = $_GET['author'] ?? '';
$sort_by = $_GET['sort_by'] ?? 'title';
$sort_order = $_GET['sort_order'] ?? 'ASC';

// Validate sort parameters
$allowed_sort_by = ['title', 'author', 'date_added'];
$allowed_sort_order = ['ASC', 'DESC'];
if (!in_array($sort_by, $allowed_sort_by)) $sort_by = 'title';
if (!in_array($sort_order, $allowed_sort_order)) $sort_order = 'ASC';

// Build query with filters and sorting, including total_quantity for admins
$sql = "SELECT *, (SELECT COUNT(*) FROM loans WHERE book_id = Books.book_id AND return_date IS NULL) as loaned_out FROM Books WHERE 1=1";
if ($title) $sql .= " AND title LIKE '%$title%'";
if ($genre) $sql .= " AND genre LIKE '%$genre%'";
if ($author) $sql .= " AND author LIKE '%$author%'";
$sql .= " ORDER BY $sort_by $sort_order";
$result = $conn->query($sql);

// Helper function for sorting links
function sortLink($label, $field, $currentSortBy, $currentSortOrder, $title, $genre, $author) {
    $nextOrder = ($currentSortBy === $field && $currentSortOrder === 'ASC') ? 'DESC' : 'ASC';
    $arrow = ($currentSortBy === $field) ? ($currentSortOrder === 'ASC' ? '↑' : '↓') : '';
    $query = http_build_query([
        'title' => $title,
        'genre' => $genre,
        'author' => $author,
        'sort_by' => $field,
        'sort_order' => $nextOrder
    ]);
    return "<a href='?{$query}' class='btn btn-outline-primary btn-sm me-2'>{$label} {$arrow}</a>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="CSS/indexstyle.css">
    <style>
        .book-card { display: flex; flex-direction: column; }
        .card-body { flex-grow: 1; }
    </style>
</head>
<body>
<div class="app">
    <!-- Top Bar -->
    <div class="top-bar d-flex justify-content-between align-items-center px-3 py-2 bg-dark text-white">
        <span>Welcome<?php echo $is_admin ? ' Admin' : ''; ?>, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
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
                <li class="nav-item"><a href="<?php echo $is_admin ? 'admin_dashboard.php' : 'user_dashboard.php'; ?>" class="nav-link">Home</a></li>
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
            <h2 class="mb-4">Browse Books</h2>
            <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>

            <!-- Filter Form -->
            <form method="get" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="title" placeholder="Title" value="<?php echo htmlspecialchars($title); ?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <select name="genre" class="form-control">
                        <option value="">Select Genre</option>
                        <?php
                        $genreQuery = "SELECT DISTINCT genre FROM Books";
                        $genreResult = $conn->query($genreQuery);
                        while ($genreRow = $genreResult->fetch_assoc()) {
                            $selected = ($genre === $genreRow['genre']) ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($genreRow['genre']) . "\" $selected>" . htmlspecialchars($genreRow['genre']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="author" placeholder="Author" value="<?php echo htmlspecialchars($author); ?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <!-- Sort Buttons -->
            <div class="mb-3">
                <strong>Sort By:</strong>
                <?php
                echo sortLink('Title', 'title', $sort_by, $sort_order, $title, $genre, $author);
                echo sortLink('Author', 'author', $sort_by, $sort_order, $title, $genre, $author);
                echo sortLink('Recently Added', 'date_added', $sort_by, $sort_order, $title, $genre, $author);
                ?>
            </div>

            <!-- Add Book Button and Modal for Admin -->
            <?php if ($is_admin): ?>
                <div class="mb-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
                        Add New Book
                    </button>
                </div>
                <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addBookModalLabel">Add New Book</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="../BACKEND/php/process_add.php" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="author" class="form-label">Author</label>
                                        <input type="text" class="form-control" id="author" name="author" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="genre" class="form-label">Genre</label>
                                        <input type="text" class="form-control" id="genre" name="genre" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="blurb" class="form-label">Description/Blurb</label>
                                        <textarea class="form-control" id="blurb" name="blurb" rows="3"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cover_image" class="form-label">Cover Image</label>
                                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Book</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Books Cards -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col">
                            <div class="card book-card h-100">
                                <img src="assets/covers/<?php echo htmlspecialchars($row['cover_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate"><?php echo htmlspecialchars($row['title']); ?></h5>
                                    <p class="card-text mb-1">Author: <?php echo htmlspecialchars($row['author']); ?></p>
                                    <p class="card-text mb-1">Genre: <?php echo htmlspecialchars($row['genre']); ?></p>
                                    <p class="card-text mb-1">Available: <?php echo (int)$row['quantity']; ?></p>
                                    <?php if ($is_admin): ?>
                                        <p class="card-text mb-1">Loaned Out: <?php echo (int)$row['loaned_out']; ?></p>
                                    <?php endif; ?>
                                    <div class="mt-auto">
                                        <button class="btn btn-primary btn-sm w-100 mb-1" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo (int)$row['book_id']; ?>">Details</button>
                                        <?php if (!$is_admin): ?>
                                            <?php if ($row['quantity'] > 0): ?>
                                                <form method="post" action="../BACKEND/php/loan_book.php" class="w-100 mb-1">
                                                    <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                                    <button type="submit" class="btn btn-success btn-sm w-100" title="Loan this book">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if ($is_admin): ?>
                                            <form method="post">
                                                <input type="hidden" name="book_id" value="<?php echo (int)$row['book_id']; ?>">
                                                <div class="d-flex gap-1 align-items-center">
                                                    <input type="number" name="quantity" min="1" value="1" class="form-control form-control-sm" style="width: 70px;">
                                                    <button type="submit" formaction="../BACKEND/php/add_book_quantity.php" class="btn btn-success btn-sm">+</button>
                                                    <button type="submit" formaction="../BACKEND/php/remove_book_quantity.php" class="btn btn-warning btn-sm">−</button>
                                                    <button type="submit" formaction="../BACKEND/php/delete_book.php" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?');">Delete</button>
                                                </div>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No books found.</p>
                <?php endif; ?>
            </div>

            <!-- Book Detail Modals -->
            <?php if ($result->num_rows > 0): ?>
                <?php
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()): ?>
                    <div class="modal fade" id="detailModal<?php echo (int)$row['book_id']; ?>" tabindex="-1" aria-labelledby="detailModalLabel<?php echo (int)$row['book_id']; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel<?php echo (int)$row['book_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="assets/covers/<?php echo htmlspecialchars($row['cover_image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($row['title']); ?>">
                                        </div>
                                        <div class="col-md-8">
                                            <table class="table">
                                                <tr><th>Title:</th><td><?php echo htmlspecialchars($row['title']); ?></td></tr>
                                                <tr><th>Author:</th><td><?php echo htmlspecialchars($row['author']); ?></td></tr>
                                                <tr><th>Genre:</th><td><?php echo htmlspecialchars($row['genre']); ?></td></tr>
                                                <tr><th>Available:</th><td><?php echo (int)$row['quantity']; ?></td></tr>
                                                <?php if ($is_admin): ?>
                                                    <tr><th>Loaned Out:</th><td><?php echo (int)$row['loaned_out']; ?></td></tr>
                                                <?php endif; ?>
                                                <tr><th>Date Added:</th><td><?php echo date('d/m/Y', strtotime($row['date_added'])); ?></td></tr>
                                            </table>
                                            <div class="mt-3">
                                                <h6>Blurb:</h6>
                                                <p><?php echo htmlspecialchars($row['blurb'] ?? 'No blurb available.'); ?></p>
                                            </div>
                                            <?php if (!$is_admin): ?>
                                                <?php
                                                $loaned_count = $user_loans[$row['book_id']] ?? 0;
                                                echo "<p>You have loaned $loaned_count copies of this book.</p>";
                                                if ($row['quantity'] > 0): ?>
                                                    <form method="post" action="../BACKEND/php/loan_book.php" class="d-inline">
                                                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                                        <button type="submit" class="btn btn-success me-2">Loan this book</button>
                                                    </form>
                                                <?php endif;
                                                if ($loaned_count > 0): ?>
                                                    <form method="post" action="../BACKEND/php/return_book.php" class="d-inline">
                                                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                                        <button type="submit" class="btn btn-warning">Return this book</button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

        </div>
    </main>

    <!-- Footer -->
    <footer class="footer text-center p-3 bg-light">
        <hr />
        <p>LibraryApp® 2025</p>
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>