<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sharing</title>
</head>
<body>
<?php
    session_start();

    $username = $_SESSION['username'];
    
    //validate current username
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        echo "Invalid username.";
        ?>
        <button onclick="history.go(-1);">Back </button>
        <?php
	    exit;
    }

    $shared_username=$_POST['shared_user'];

    //validate username of user to be shared
    if( !preg_match('/^[\w_\-]+$/', $shared_username) ){
        echo "Invalid username.";
        ?>
        <button onclick="history.go(-1);">Back </button>
        <?php
	    exit;
    }

    $filename = basename($_FILES['sharing_file']['name']);
    
    //validate filename
    if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
        echo "Invalid filename.";
        ?>
        <button onclick="history.go(-1);">Back </button>
        <?php
        exit;
    }


    //check if the user exists
    $h = fopen("users.txt", "r");
    $linenumber = 1;
    $validUsername=false;
    while(!feof($h) and !$validUsername){ //go through users.txt to find usernames
        $attempt=trim(fgets($h));
        if ($attempt==$shared_username){ //if username is found
            $validUsername=true;
        }
        else{
            $linenumber++;  
        }
    }
    fclose($h);

    //if username is not valid or is the current user
    if (!$validUsername or $username==$shared_username){
        echo "Username not accepted. Must be a valid different user.";
        ?>
        <button onclick="history.go(-1);">Back </button>
        <?php
    }
    else{ //valid username
        $full_path = sprintf("/home/kendallpomerleau/%s/%s", $shared_username, $filename);
        $original_path = sprintf("/home/kendallpomerleau/%s/%s", $username, $filename);
        if (file_exists($original_path)) {
            if( copy($_FILES['sharing_file']['tmp_name'], $full_path) ){
                header("Location: upload_success.html");
                exit;
            }else{
                header("Location: upload_failure.html");
                exit;
            }
        }
        else {
            header("Location: upload_failure.html");
            exit;
        }
    }
?>
</body>
</html>