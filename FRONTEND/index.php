<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Library App</title>
    <!-- Link to the external CSS file and Google Fonts -->
    <link rel="stylesheet" href="CSS/indexstyle.css" />
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
          <img
            width="50"
            height="50"
            src="https://img.icons8.com/keek/100/books.png"
            alt="books"
          />
        </h1>
        <div class="nav-buttons">
          <button class="btn btn-secondary" id="signupButton">
            Sign Up
          </button>
          <button class="btn btn-dark" id="loginButton">
            Log In
          </button>
        </div>
      </header>

      <!-- Signup Modal -->
      <div class="modal-overlay" id="signupModal">
        <div class="modal-box">
          <div class="modal-header">
            <h5>Create Account</h5>
            <button class="modal-close" id="closeSignup">&times;</button>
          </div>
          <form action="../BACKEND/php/process_form.php" method="post">
            <div class="form-field">
              <label>Name</label>
              <input type="text" class="form-input" name="name" required/>
            </div>
            <div class="form-field">
              <label>Email</label>
              <input type="email" class="form-input" name="email" required/>
            </div>
            <div class="form-field">
              <label>Password</label>
              <input type="password" class="form-input" name="password" required/>
            </div>
            <button type="submit" class="btn btn-dark full-width">
              Sign Up
            </button>
          </form>
        </div>
      </div>

      <!-- Login Modal -->
      <div class="modal-overlay" id="loginModal">
        <div class="modal-box">
          <div class="modal-header">
            <h5>Sign In</h5>
            <button class="modal-close" id="closeLogin">x</button>
          </div>
          <form action="../BACKEND/php/login_process.php" method="post">
            <div class="form-field">
              <label>Email</label>
              <input type="email" class="form-input" name="email" required />
            </div>
            <div class="form-field">
              <label>Password</label>
              <input type="password" class="form-input" name="password" required />
            </div>
            <button type="submit" class="btn btn-dark full-width">
              Log In
            </button>
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
<script type="text/javascript" src="../FRONTEND/js/modaljs.js"></script>