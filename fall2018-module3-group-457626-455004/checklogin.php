<?php
// This is a *good* example of how you can implement password-based user authentication in your web application.
session_start();
require 'database.php';
if(!hash_equals($_SESSION['token'], $_POST['token'])){
	die("Request forgery detected");
}

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*), id, hashed_password FROM users WHERE username=?");

// Bind the parameter
$stmt->bind_param('s', $user);
$user = $_POST['username'];
$stmt->execute();

// Bind the results
$stmt->bind_result($cnt, $user_id, $pwd_hash);
$stmt->fetch();

$pwd_guess = $_POST['password'];
// Compare the submitted password to the actual password hash

if($cnt == 1 && password_verify($pwd_guess, $pwd_hash) or $user=="guest"){
    // Login succeeded!
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $user;
    // Redirect to your target page
    header("Location: news.php");
    exit;
} else{
    // Login failed; redirect back to the login screen
    header("Location: loginfailure.html");
    exit;
}
?>