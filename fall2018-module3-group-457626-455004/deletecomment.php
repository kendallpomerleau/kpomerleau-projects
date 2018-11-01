<?php
    session_start();
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    require 'database.php';

    $username=$_SESSION['username'];
    $user_id=$_SESSION['user_id'];
    $comment_id = $_POST['comment_id'];
    $article_id = $_POST['article_id'];

    // DELETE COMMENT
    $stmt = $mysqli->prepare("DELETE from comments where comment_id=?");
    if(!$stmt){
        printf("Query Prep Failed q1: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s', $comment_id);
    $stmt->execute();
    $stmt->close();
    
    //CHECK IF USER STILL HAS OTHER COMMENTS ON ARTICLE
    $stmt3 = $mysqli->prepare("SELECT comment_id from comments where user_id=? and article_id=?");
    if(!$stmt3){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt3->bind_param("ss",$user_id,$article_id);
    $stmt3->execute();
    $result = $stmt3->get_result();
    if ($result->num_rows === 0){ //if no comments
        $no='n';
        $stmt2 = $mysqli->prepare("UPDATE $username set commented=? where article_id=?");
        if(!$stmt2){
            printf("Query Prep Failed: q2%s\n", $mysqli->error);
            exit;
        }
        $stmt2->bind_param('ss', $no, $article_id);
        $stmt2->execute();
        $stmt2->close();
    }
    $stmt3->close();



    //SEND TO BACK TO PROFILE
        header("Location: userpage.php");
        exit;


?>