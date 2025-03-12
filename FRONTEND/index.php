<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Library App</title>
    <link rel="stylesheet" href="CSS/indexstyle.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app">
        <header class="header">
            <h1>
                Library App
                <img width="50" height="50" src="https://img.icons8.com/keek/100/books.png" alt="books" />
            </h1>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
                <h4>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h4>
                <div class="nav-buttons">
                    <button class="btn btn-dark" id="logoutButton">Log Out</button>
                </div>
            <?php else: ?>
                <div class="nav-buttons">
                    <button class="btn btn-secondary" id="signupButton">Sign Up</button>
                    <button class="btn btn-dark" id="loginButton">Log In</button>
                </div>
            <?php endif; ?>
        </header>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="modal-overlay" id="signupModal" style="display: none;">
                <div class="modal-box">
                    <div class="modal-header">
                        <h5>Create Account</h5>
                        <button class="modal-close" id="closeSignup">×</button>
                    </div>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
                            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    <form action="../BACKEND/php/process_form.php" method="post">  <!-- Sign-up -->
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
                        <div class="form-field">
                            <label>Confirm Password</label>
                            <input type="password" class="form-input" name="confirm_password" required/>
                        </div>
                        <div class="form-field">
                            <label>
                                <input type="checkbox" name="role_admin" value="admin" />
                                Sign up as Admin
                            </label>
                        </div>
                        <button type="submit" class="btn btn-dark full-width">Sign Up</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="modal-overlay" id="loginModal" style="display: none;">
                <div class="modal-box">
                    <div class="modal-header">
                        <h5>Sign In</h5>
                        <button class="modal-close" id="closeLogin">×</button>
                    </div>
                    <form action="../BACKEND/php/login_process.php" method="post">  <!-- Login -->
                        <div class="form-field">
                            <label>Email</label>
                            <input type="email" class="form-input" name="email" required />
                        </div>
                        <div class="form-field">
                            <label>Password</label>
                            <input type="password" class="form-input" name="password" required />
                        </div>
                        <div class="form-field">
                            <label>Login as</label>
                            <select name="role" class="form-input" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark full-width">Log In</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
            <div class="modal-overlay" id="logoutModal" style="display: none;">
                <div class="modal-box">
                    <div class="modal-header">
                        <h5>Log Out</h5>
                        <button class="modal-close" id="closeLogout">×</button>
                    </div>
                    <form action="BACKEND/php/logout_process.php" method="post">    <!-- Logout -->
                        <div class="form-field">
                            <p>Are you sure you want to log out?</p>
                        </div>
                        <button type="submit" class="btn btn-dark full-width">Log Out</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <main class="main-container">
            <div class="content">
                <div class="background-overlay"></div>
                <h2>Welcome to Your Library</h2>
                <p>Browse, Add, and Manage Your Books with Ease!</p>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
                    <div class="button-group">
                        <button class="btn btn-secondary">Browse Books</button>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <footer class="footer">
            <hr />
            <p>LibraryApp® 2025</p>
        </footer>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <script>
            alert("<?php echo addslashes($_SESSION['success']); ?>");
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            alert("<?php echo addslashes($_SESSION['error']); ?>");
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script src="./js/modaljs.js"></script>
</body>
</html>