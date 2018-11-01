<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>File Upload Site</title>
    </head>
    <body>
<?php

//check for users
$h = fopen("users.txt", "r");
$username= $_GET['username'];

if( !preg_match('/^[\w_\-]+$/', $username) ){ //validate username
    echo "Invalid username.";
    ?>
    <button onclick="history.go(-1);">Back </button>
    <?php
    exit;
}
$linenumber = 1;
$validUsername=false;
while(!feof($h) and !$validUsername){ //go through users.txt to find usernames
    $attempt=trim(fgets($h));
    if ($attempt==$username){ //if username is found
        $validUsername=true;
    }
    else{
        $linenumber++;  
    }
}
fclose($h);


if (!$validUsername){ //if username is invalid
    echo "Username not accepted.";
    ?>
    <button onclick="history.go(-1);">Back </button>
    <?php
}
else{ //if username is valid
    session_start();
    ?>
        <h1> Available Files: </h1>
    <?php
    //display files
    $_SESSION['username']=$username;
    $log_directory = sprintf("/home/kendallpomerleau/%s", $username);
    // used https://stackoverflow.com/questions/2922954/getting-the-names-of-all-files-in-a-directory-with-php
    // to get the files to display from directory we created
    foreach(glob($log_directory.'/*.*') as $file) {
        //used https://stackoverflow.com/questions/2183486/php-get-file-name-without-file-extension
        // to not display entire path of file and only the filename with extension.
        $path_parts = pathinfo($file);
        echo $path_parts['basename'], "\n<br />";
    }
    echo "\n<br/>";
    ?>

    <form name="input" action="viewFile.php" method="GET">  <!--VIEW FILE-->
        Type Filename to View: <input type="text" name="file"/>
        <input type="submit" value="submit"/>
    </form>

    <form name="input" action="deleteFile.php" method="GET"> <!--DELETE FILE-->
        Type Filename to Delete: <input type="text" name="file"/>
        <input type="submit" value="submit"/>
    </form>

    <form enctype="multipart/form-data" action="uploader.php" method="POST"> <!--UPLOAD FILE-->
        <p>
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
            <label for="uploadfile_input">Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input"/>
            <br><input type="submit" value="Upload File"/>
            </p>
    </form>

    <form name="input" action="uploadURL.php" method="POST"> <!--UPLOAD FROM URL-->
        <br>Type URL of File to Upload from Internet (file must be under 2MB): <input type="text" name="fileURL"/>
        <input type="submit" name="get_file" value="submit"/>
    </form>

    <form enctype="multipart/form-data" action="sharing_success.php" method="POST"> <!--SHARE FILE-->
        <br> Enter File to Share (you can only share files already uploaded): <input type="file" name="sharing_file"/>
        <br> Type Username to Share: <input type="text" name="shared_user"/>
        <input type="submit" value="share with other user"/>
    </form>

    <form name="input" action="logout.php" method="GET"> <!--LOGOUT-->
        <br> <input type="submit" value="Logout"/>
    </form>

    <?php
     
    } //end else
    ?>
    </body>
    </html>

    