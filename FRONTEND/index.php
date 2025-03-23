<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../FRONTEND/admin_dashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'user') {
        header("Location: ../FRONTEND/user_dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Library App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/indexstyle.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app">
        <!-- Thin Top Bar -->
        <div class="top-bar d-flex justify-content-between align-items-center px-3 py-2 bg-dark text-white">
            <span>
                <?php echo isset($_SESSION['user_id']) ? "Welcome, " . htmlspecialchars($_SESSION['name']) . "!" : "Welcome to Library App"; ?>
            </span>
            <div class="ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#logoutModal">Log Out</button>
                <?php else: ?>
                    <button class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#signupModal">Sign Up</button>
                    <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#loginModal">Log In</button>
                <?php endif; ?>
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

        <!-- Modals -->
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="signupModalLabel">Create Account</h5>
                            <button type="button" class="btn-close" id="closeSignup" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger text-center">
                                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                                </div>
                            <?php endif; ?>
                            <form action="../BACKEND/php/process_form.php" method="post">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required/>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required/>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" required/>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password" required/>
                                </div>
                                <button type="submit" class="btn btn-dark w-100">Sign Up</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
                            <button type="button" class="btn-close" id="closeLogin" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../BACKEND/php/login_process.php" method="post">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Login as</label>
                                    <select name="role" class="form-select" required>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-dark w-100">Log In</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
            <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="logoutModalLabel">Log Out</h5>
                            <button type="button" class="btn-close" id="closeLogout" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="BACKEND/php/logout_process.php" method="post">
                                <p>Are you sure you want to log out?</p>
                                <button type="submit" class="btn btn-dark w-100">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Content -->
        <main class="main-container flex-grow-1 d-flex justify-content-center align-items-center p-4">
            <div class="content text-center text-white p-3 rounded">
                <h2>Welcome to Your Library</h2>
                <p>Browse, Add, and Manage Your Books with Ease!</p>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
                    <div class="mt-3">
                        <button class="btn btn-secondary">Browse Books</button>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer text-center p-3 bg-light">
            <hr />
            <p>LibraryApp® 2025</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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