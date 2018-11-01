<?php
    session_start();
    require 'database.php';

    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $comment_edit = $_POST['commentedit'];
    $comment_id = $_POST['comment_id'];

    

    //update comments
    $stmt = $mysqli->prepare("UPDATE comments set comment=? where comment_id=?");
    if(!$stmt){
        printf("Query Prep Failed hi: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('ss', $comment_edit, $comment_id);
    $stmt->execute();
    $stmt->close();
    
    //SEND TO BACK TO PROFILE
        header("Location: userpage.php");
        exit;


?>