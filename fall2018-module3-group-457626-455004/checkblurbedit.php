<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Blurb</title>
</head>
<body>
<?php
    session_start();
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    //$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $article_to_be_edited = $_POST['article_name'];
    $_SESSION['article_name'] = $article_to_be_edited;
    //validate username
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        echo "Invalid username.";
        ?>
        <button onclick="history.go(-1);">Back </button>
        <?php
    exit;
    }

    require 'database.php';

    //getting articles that specific user has uploaded
    $stmt = $mysqli->prepare("SELECT ar.article_name FROM articles ar join $username un on (ar.id=un.article_id) where uploaded='y'");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $user_uploaded_file = false;

    if ($result->num_rows === 0){ //if no files have been uploaded
        header("Location: nofilesuploaded.html");
        exit;    
    }
    else { //if files have been uploaded, check to see if that user was the one who uploaded
        while($row = $result->fetch_assoc()){
            if ($article_to_be_edited == $row["article_name"]){
                $user_uploaded_file=true;
            }
        }
    }
    $stmt->close();

    if ($user_uploaded_file){ //if the user uploaded this file
        //GET ARTICLE BLURB
        $stmt3 = $mysqli->prepare("SELECT blurb from articles where article_name = ?");
        if(!$stmt3){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt3->bind_param('s', $article_to_be_edited);
        $stmt3->execute();
        $result = $stmt3->get_result();
        while($row = $result->fetch_assoc()){
            $article_blurb = $row['blurb'];
        }
        $stmt3->close();

        ?>
        <!-- used https://www.elated.com/articles/html-text-and-textarea-form-fields/ for text area -->
        <form name="input" action="editblurb.php" method="POST">
            Edit your blurb. <br>
            <textarea name="blurbedit" cols="50" rows="10" maxlength="500" > <?=$article_blurb ?> </textarea>
            <br> <input type="submit" value="Edit Blurb"/>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        </form>
        <?php

    }
    else { //if article is not uploaded by that user
        //SEND TO DELETE UNSUCCESSFUL PAGE
        header("Location: editunsuccessful.php");
        exit;
    }
?>

</body>
</html>
