<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'db_connect.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT b.title, l.borrow_date, l.due_date, l.book_id
        FROM loans l
        JOIN books b ON l.book_id = b.book_id
        WHERE l.user_id = ? AND l.return_date IS NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Loans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>My Loans</h2>
        <?php if ($result->num_rows > 0): ?>
            <ul class="list-group">
                <?php while ($loan = $result->fetch_assoc()): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                            <strong><?php echo htmlspecialchars($loan['title']); ?></strong><br>
                            Borrowed: <?php echo date('d/m/Y', strtotime($loan['borrow_date'])); ?><br>
                            Due: <?php echo date('d/m/Y', strtotime($loan['due_date'])); ?>
                        </div>
                        <form method="post" action="return_book.php">
                            <input type="hidden" name="book_id" value="<?php echo $loan['book_id']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Return</button>
                        </form>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>You have no loaned books.</p>
        <?php endif; ?>
    </div>
</body>
</html>