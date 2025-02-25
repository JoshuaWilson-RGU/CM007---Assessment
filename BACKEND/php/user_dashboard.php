<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../FRONTEND/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Library App - User Dashboard</title>
    <!-- Link to the external CSS file and Google Fonts -->
    <link rel="stylesheet" href="../../FRONTEND/CSS/indexstyle.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
  <body>
    <div class="app">
      <!-- Header Section -->
      <header class="header">
        <h1>
          Library App
          <img width="50" height="50" src="https://img.icons8.com/keek/100/books.png" alt="books"/>
        </h1>
        <h4>Welcome, <?php echo $_SESSION['name']; ?>!</h4>
        <div class="nav-buttons">
          <button class="btn btn-dark" id="logoutButton">
            Log Out
          </button>
        </div>
      </header>


      

<!-- Logout Modal -->
<div class="modal-overlay" id="logoutModal">
    <div class="modal-box">
        <div class="modal-header">
            <h5>Log Out</h5>
            <button class="modal-close" id="closeLogout">×</button>
        </div>
        <form action="../BACKEND/php/logout_process.php" method="post">
            <div class="form-field">
                <p>Are you sure you want to log out?</p>
            </div>
            <button type="submit" class="btn btn-dark full-width">Log Out</button>
        </form>
    </div>
</div>

      <!-- Main Content -->
      <main class="main-container">
        <div class="content">
          <!-- Add this div -->
          <div class="background-overlay"></div>
          <h2>Welcome to Your Library</h2>
          <p>Browse, Add, and Manage Your Books with Ease!</p>
          <div class="button-group">
            <button class="btn btn-secondary">Browse Books</button>
          </div>
        </div>
      </main>

      <!-- Footer -->
      <footer class="footer">
        <hr />
        <p>LibraryApp® 2025</p>
      </footer>
    </div>
  </body>
</html>
<!-- JavaScript linked-->
<script type="text/javascript" src="../../FRONTEND/js/modaljs.js"></script>