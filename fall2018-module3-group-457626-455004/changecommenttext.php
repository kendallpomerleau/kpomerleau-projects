<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Comment</title>
</head>
<body>
    <?php
    session_start();
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    //$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    require 'database.php';    
    $user_id=$_SESSION['user_id'];
    $comment_id=$_POST['comment_id'];

    //CHECK TO MAKE SURE THIS USER UPLOADED THIS COMMENT
    $stmt = $mysqli->prepare("SELECT user_id from comments where comment_id=?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s',$comment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $user_uploaded_comment=false;
    if ($result->num_rows === 0){ //if no comments
        echo "Comment ID does not exist.";
        ?>
        <form name="input" action="userpage.php">
            <input type="submit" value="Back to Profile"/>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        </form> 
        <?php
        exit;
    }
    else { //if comments have been made on that article
        while($row = $result->fetch_assoc()){
            if ($user_id == $row["user_id"]){
                $user_uploaded_comment=true;
            }
            else {
                echo "You did not upload this comment.";
                ?>
                <form name="input" action="userpage.php">
                    <input type="submit" value="Back to Profile"/>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                </form> 
                <?php
                exit;
            }
        }
    }
    $stmt->close();


    //GET THE COMMENT TEXT FROM COMMENTS TABLE USING COMMENT ID
    //STORE IN VARIABLE TO PUT INTO FORM
    if ($user_uploaded_comment){
        $stmt2 = $mysqli->prepare("SELECT comment from comments where comment_id=?");
        if(!$stmt2){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt2->bind_param('s',$comment_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        while($row2 = $result2->fetch_assoc()){
            $comment_to_display = $row2['comment'];
        }
        
    }

    ?>


    <form name="input" action="editcomment.php" method="POST">
        Edit your comment. <br>
        <textarea name="commentedit" cols="50" rows="10" maxlength="500" > <?=$comment_to_display?> </textarea>
        <br> <input type="submit" value="Edit Comment"/>
        <input type="hidden" name="comment_id" value="<?=$comment_id?>"/>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
</body>
</html>