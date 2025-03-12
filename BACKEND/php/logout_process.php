<?php
session_start();

session_unset();   // Clear session variables
session_destroy(); // End the session

// Redirect to index.php in FRONTEND/
header("Location: ../../FRONTEND/index.php");
exit();
?>