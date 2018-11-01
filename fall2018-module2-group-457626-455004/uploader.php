<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Uploader</title>
</head>
<body>
<?php
session_start();
$filename = basename($_FILES['uploadedfile']['name']);

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

if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path) ){ //if file is uploaded
	header("Location: upload_success.html");
	exit;
}else{
	header("Location: upload_failure.html");
	exit;
}
?>
</body>
</html>