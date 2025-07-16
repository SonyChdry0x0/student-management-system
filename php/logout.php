<?php
session_start();
session_unset();
session_destroy();
header("Location:../signup_login.html");
exit();
?>
