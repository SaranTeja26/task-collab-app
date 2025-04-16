<?php
session_start();

// Destroy all session data
session_unset(); // optional but good for clarity
session_destroy();

// Redirect to login page (index.php)
header("Location: index.php");
exit;
?>
