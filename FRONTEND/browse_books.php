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

<html>
<head>
    <title>Browse Books</title>
    <link rel="stylesheet" href="CSS/indexstyle.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        .book-card { border: 1px solid #ccc; padding: 10px; margin: 10px; width: 200px; display: inline-block; }
        .btn { padding: 5px 10px; text-decoration: none; }
        .btn-primary { background: blue; color: white; }
        .btn-danger { background: red; color: white; }
    </style>
</head>
<body>
    <h1>Library App</h1>
    <h4>Welcome, <?php echo $_SESSION['name']; ?>!</h4>
    <a href="../BACKEND/php/logout_process.php" class="btn btn-primary">Log Out</a>

    <h2>Browse Books</h2>
    <form method="get">
        <input type="text" name="title" placeholder="Title" value="<?php echo $title; ?>">
        <input type="text" name="genre" placeholder="Genre" value="<?php echo $genre; ?>">
        <input type="text" name="author" placeholder="Author" value="<?php echo $author; ?>">
        <button type="submit">Filter</button>
    </form>
    <?php if ($is_admin): ?>
        <a href="add_book.php" class="btn btn-primary">Add Book</a>
    <?php endif; ?>
    <div>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="book-card">
                <img src="images/books/<?php echo $row['book_id']; ?>.jpg" width="100" alt="<?php echo $row['title']; ?>">
                <h3><?php echo $row['title']; ?></h3>
                <p>Author: <?php echo $row['author']; ?></p>
                <p>Genre: <?php echo $row['genre']; ?></p>
                <p>Available: <?php echo $row['quantity']; ?></p>
                <?php if ($is_admin): ?>
                    <form method="post" action="../BACKEND/php/delete_book.php">
                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>