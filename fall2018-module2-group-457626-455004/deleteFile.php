<?php
session_start();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File Deleted</title>
</head>
<?php
$filename = $_GET['file'];

//validate filename
if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
    echo "Invalid filename.";
    ?>
    <button onclick="history.go(-1);">Back </button>
    <?php
    exit;
}

$username = $_SESSION['username'];

//validate username
if( !preg_match('/^[\w_\-]+$/', $username) ){
    echo "Invalid username.";
    ?>
    <button onclick="history.go(-1);">Back </button>
    <?php
	exit;
}

$full_path = sprintf("/home/kendallpomerleau/%s/%s", $username, $filename);

//used http://www.php.net/manual/en/function.file-exists.php
//to find file_exists
if (file_exists($full_path)){ //if file exists
    unlink($full_path); //delete file
    ?>
    <body>
        <p>File Successfully Deleted.</p>
        <!-- code for back button from https://stackoverflow.com/questions/3659782/code-for-back-button -->
        <button onclick="history.go(-1);">Back </button>
    </body>
    </html>
<?php
}
else { //if file does not exist
    header("Location: fileFailure.html");
    exit;
}

?>
