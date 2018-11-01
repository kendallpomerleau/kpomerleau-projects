<?php
session_start();

$username = $_SESSION['username'];

//validate username
if( !preg_match('/^[\w_\-]+$/', $username) ){
    echo "Invalid username.";
    ?>
    <button onclick="history.go(-1);">Back </button>
    <?php
	exit;
}

//used http://talkerscode.com/webtricks/upload-image-from-url-using-php.php
//to find how to upload image from url
if (isset($_POST['get_file'])){
    $url = $_POST['fileURL'];
    //used https://www.sitepoint.com/community/t/url-validation-with-preg-match/3255
    //for url validation using preg_match
    if(preg_match( '/^https?:\/\/(www\.)?$reg_type/' ,$url)){
        echo "Invalid URL.";
        ?>
        <button onclick="history.go(-1);">Back </button>
        <?php
    exit;
    }

    $name = basename($url); //get basename
    $data=file_get_contents($url); //get contents of the file url
    $full_path = sprintf("/home/kendallpomerleau/%s/%s", $username, $name);
    file_put_contents("$full_path", $data); //put contents into the path

    $filesize = filesize($full_path);
    if ($filesize > 2000000) {
        header("Location: failedURLUpload.html");
        exit;
    }
    header("Location: upload_success.html");
    exit;
}
?>

