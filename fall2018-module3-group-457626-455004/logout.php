<?php
session_start();
session_destroy();

header("Location: login.php"); //go to login page
exit;
?>