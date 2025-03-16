<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>

<html>
<head>
    <title>Add Book</title>
    <style>
        .btn { padding: 5px 10px; background: blue; color: white; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Library App</h1>
    <h4>Welcome, <?php echo $_SESSION['name']; ?>!</h4>
    <a href="../BACKEND/php/logout_process.php" class="btn">Log Out</a>

    <h2>Add New Book</h2>
    <form method="post" action="../BACKEND/php/process_add_book.php" enctype="multipart/form-data">
        <p>Title: <input type="text" name="title" required></p>
        <p>Author: <input type="text" name="author" required></p>
        <p>Genre: <input type="text" name="genre" required></p>
        <p>ISBN: <input type="text" name="isbn" required></p>
        <p>Quantity: <input type="number" name="quantity" min="1" value="1" required></p>
        <p>Image: <input type="file" name="image" required></p>
        <button type="submit" class="btn">Add Book</button>
    </form>
</body>
</html>