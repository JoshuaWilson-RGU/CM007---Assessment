<?php
session_start();

echo "Before logout: user_id = " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set') . "<br>";
session_unset();
session_destroy();
echo "After logout: user_id = " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set') . "<br>";

header("Location: ../../FRONTEND/index.php");
exit();
?>