<!--!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View</title>
</head>
<body-->
<?php
session_start();

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
    echo "Invalid username";
    ?>
    <button onclick="history.go(-1);">Back </button>
    <?php
	exit;
}
$full_path = sprintf("/home/kendallpomerleau/%s/%s", $username, $filename);



//used http://www.php.net/manual/en/function.file-exists.php
//to find file_exists
    if (file_exists($full_path)){
        //set content-type and display
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($full_path);
        header("Content-Type: ".$mime);
        ob_get_clean();
        readfile($full_path);
        ob_end_flush();
    }
    else {
        header("Location: fileFailure.html");
        exit;
    }
?>
<!--/body>
</html-->