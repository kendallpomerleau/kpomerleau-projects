<?php
session_start();
session_destroy();

header("Location: login.html"); //go to login page
exit;
?>