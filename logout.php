<?php
// Start the session to access session variables
session_start();

// Destroy the session to log out the user
session_unset();
session_destroy();

// Redirect to the login page after logout
header("Location: signup_login.php");  // Replace 'login.php' with your actual login page
exit();
?>
