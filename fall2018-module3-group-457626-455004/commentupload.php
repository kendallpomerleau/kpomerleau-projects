<?php
    session_start();
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    require 'database.php';

    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $article_name = $_POST['article_name'];

    //select article_id from articles table using article_name
    $stmt3 = $mysqli->prepare("SELECT id from articles where article_name = ?");
    if(!$stmt3){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt3->bind_param('s', $article_name);
    $stmt3->execute();
    $result = $stmt3->get_result();
    
    if ($result->num_rows === 0){
        header("Location: commentfailure.html");
        exit;
    }
    else {
        while($row = $result->fetch_assoc()){
            $article_id = $row['id'];
        }
    }    
    $stmt3->close();


    //insert comment into comments table using article link to get article_id from articles table
    $stmt2 = $mysqli->prepare("INSERT into comments(article_id, user_id, comment) values (?, ?, ?)");
    if(!$stmt2){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt2->bind_param('sss', $article_id, $user_id, $_POST['comment']);
    $stmt2->execute();
    $stmt2->close();


    //FIND IF ARTICLE IS ALREADY IN USER SPECIFIC TABLE
    $article_present=false;
    $stmt4 = $mysqli->prepare("SELECT article_id from $username");
    if(!$stmt4){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt4->execute();
    $results = $stmt4->get_result();
    while($row = $results->fetch_assoc()){
        //if article id is found in the user's specific table, that means they uploaded it or already commented
        if ($article_id==$row['article_id']){
            $article_present=true;
        }
    }



    //IF THE USER ALREADY HAS THE ARTICLE, CHANGE COMMENTED FIELD IN SPECIFIC USER TABLE TO BE 'Y'
    if ($article_present==1){
        $yes = 'y';
        $stmt5 = $mysqli->prepare("UPDATE $username set commented=? where article_id = ?");
        if(!$stmt5){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt5->bind_param('ss', $yes, $article_id);
        $stmt5->execute();
        $stmt5->close();
    }
    else { //ADD THE ARTICLE TO THE USER SPECIFIC TABLE
        $stmt6 = $mysqli->prepare("INSERT into $username values (?, ?, ?, ?, ?)");
        if(!$stmt6){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $yes = 'y';
        $no = 'n';


        $stmt6->bind_param('sssss', $article_id, $no, $yes, $no, $no);
        $stmt6->execute();
        $stmt6->close();
    }

    header("Location: commentsuccess.php");
    exit;

?>
